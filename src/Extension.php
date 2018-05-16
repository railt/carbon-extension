<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\CarbonExtension;

use Railt\CarbonExtension\TypeDefinition\FormatArgument;
use Railt\Foundation\Events\TypeBuilding;
use Railt\Foundation\Extensions\BaseExtension;
use Railt\Io\File;
use Railt\Routing\Contracts\RouterInterface;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Contracts\Dependent\FieldDefinition;
use Railt\SDL\Reflection\Builder\Dependent\FieldBuilder;
use Railt\SDL\Schema\CompilerInterface as Compiler;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as Events;

/**
 * Class Extension
 */
class Extension extends BaseExtension
{
    /**
     * @var string Path to graphql schema file
     */
    private const GRAPHQL_DATETIME = __DIR__ . '/../resources/datetime.graphqls';

    /**
     * @param Compiler $compiler
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function boot(Compiler $compiler): void
    {
        $compiler->compile(File::fromPathname(self::GRAPHQL_DATETIME));

        $this->call(\Closure::fromCallable([$this, 'listen']));
    }

    /**
     * @param Events $listener
     * @param Compiler $compiler
     * @param RouterInterface $router
     */
    private function listen(Events $listener, Compiler $compiler, RouterInterface $router): void
    {
        $controller = $this->make(CarbonController::class);

        $invoke = function (TypeBuilding $event) use ($compiler, $router, $controller): void {
            if ($this->isCarbonField($event->getType())) {
                $this->extend($compiler, $event->getType());

                $router->route($event->getType())->then(\Closure::fromCallable([$controller, 'getDateTime']));
            }
        };


        $listener->addListener(TypeBuilding::class, $invoke);
    }

    /**
     * @param TypeDefinition $type
     * @return bool
     */
    private function isCarbonField(TypeDefinition $type): bool
    {
        return $type instanceof FieldDefinition && $type->getTypeDefinition()->getName() === 'Carbon';
    }

    /**
     * @param Compiler $compiler
     * @param FieldDefinition|FieldBuilder|TypeDefinition $field
     */
    private function extend(Compiler $compiler, FieldDefinition $field): void
    {
        (function () use ($field, $compiler): void {
            $this->arguments[FormatArgument::ARGUMENT_NAME] = new FormatArgument($field, $compiler);
        })->call($field);
    }
}
