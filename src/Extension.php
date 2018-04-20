<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\CarbonExtension;

use Railt\Foundation\Extensions\BaseExtension;
use Railt\Io\File;
use Railt\SDL\Schema\CompilerInterface;

/**
 * Class DateTimeExtension
 */
class Extension extends BaseExtension
{
    /**
     * @var string Path to graphql schema file
     */
    private const GRAPHQL_DATETIME = __DIR__ . '/../datetime.graphqls';

    /**
     * @param CompilerInterface $compiler
     */
    public function boot(CompilerInterface $compiler): void
    {
        $compiler->compile(File::fromPathname(self::GRAPHQL_DATETIME));
    }
}
