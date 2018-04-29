<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\CarbonExtension\TypeDefinition;

use Railt\CarbonExtension\In\CarbonFormat;
use Railt\Reflection\Base\Dependent\BaseArgument;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\SDL\Schema\CompilerInterface;
use Railt\SDL\Schema\Configuration;

/**
 * Class FormatArgument
 */
class FormatArgument extends BaseArgument
{
    public const ARGUMENT_NAME = 'format';
    public const ARGUMENT_DESCRIPTION = '
        An argument that provides a format of the given value that 
        are contained in a CarbonFormat enumeration type
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
        $this->compiler = $compiler;
        $this->name = self::ARGUMENT_NAME;
        $this->description = self::ARGUMENT_DESCRIPTION;

        $this->parent = $field;

        $this->defaultValue = CarbonFormat::DEFAULT;
        $this->hasDefaultValue = true;
    }

    /**
     * @return TypeDefinition
     */
    public function getTypeDefinition(): TypeDefinition
    {
        return $this->compiler->getDictionary()->get('CarbonFormat', $this);
    }
}
