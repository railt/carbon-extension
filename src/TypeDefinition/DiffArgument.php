<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\CarbonExtension\TypeDefinition;

use Railt\SDL\Base\Dependent\BaseArgument;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Contracts\Dependent\FieldDefinition;
use Railt\SDL\Schema\CompilerInterface;
use Railt\SDL\Schema\Configuration;

/**
 * Class DiffArgument
 */
class DiffArgument extends BaseArgument
{
    public const ARGUMENT_NAME        = 'diff';
    public const ARGUMENT_DESCRIPTION = '
        An argument that matches the date of the time difference.
        
        If a `NULL` is passed (or the value is not passed),
        then the current time is taken.
    ';

    /**
     * @var CompilerInterface|Configuration
     */
    private $compiler;

    /**
     * DiffArgument constructor.
     * @param FieldDefinition $field
     * @param CompilerInterface $compiler
     */
    public function __construct(FieldDefinition $field, CompilerInterface $compiler)
    {
        $this->compiler    = $compiler;
        $this->name        = self::ARGUMENT_NAME;
        $this->description = self::ARGUMENT_DESCRIPTION;

        $this->parent = $field;

        $this->defaultValue    = null;
        $this->hasDefaultValue = true;
    }

    /**
     * @return TypeDefinition
     */
    public function getTypeDefinition(): TypeDefinition
    {
        return $this->compiler->getDictionary()->get('Carbon', $this);
    }
}
