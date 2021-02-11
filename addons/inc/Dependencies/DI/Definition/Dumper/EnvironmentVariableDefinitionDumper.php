<?php
/**
 * PHP-DI
 *
 * @link      http://php-di.org/
 * @copyright Matthieu Napoli (http://mnapoli.fr/)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace Cybot\Dependencies\DI\Definition\Dumper;

use Cybot\Dependencies\DI\Debug;
use Cybot\Dependencies\DI\Definition\Definition;
use Cybot\Dependencies\DI\Definition\EntryReference;
use Cybot\Dependencies\DI\Definition\EnvironmentVariableDefinition;
use Cybot\Dependencies\DI\Definition\Helper\DefinitionHelper;

/**
 * Dumps environment variable definitions.
 *
 * @author James Harris <james.harris@icecave.com.au>
 */
class EnvironmentVariableDefinitionDumper implements DefinitionDumper
{
    /**
     * {@inheritdoc}
     */
    public function dump(Definition $definition)
    {
        if (! $definition instanceof EnvironmentVariableDefinition) {
            throw new \InvalidArgumentException(sprintf(
                'This definition dumper is only compatible with EnvironmentVariableDefinition objects, %s given',
                get_class($definition)
            ));
        }

        $str = "    variable = " . $definition->getVariableName();
        $str .= "\n    optional = " . ($definition->isOptional() ? 'yes' : 'no');

        if ($definition->isOptional()) {
            $defaultValue = $definition->getDefaultValue();

            if ($defaultValue instanceof DefinitionHelper) {
                $nestedDefinition = Debug::dumpDefinition($defaultValue->getDefinition(''));
                $defaultValueStr = $this->indent($nestedDefinition);
            } else {
                $defaultValueStr = var_export($defaultValue, true);
            }

            $str .= "\n    default = " . $defaultValueStr;
        }

        return sprintf(
            "Environment variable (\n%s\n)",
            $str
        );
    }

    private function indent($str)
    {
        return str_replace("\n", "\n    ", $str);
    }
}
