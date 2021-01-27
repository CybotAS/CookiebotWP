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
use Cybot\Dependencies\DI\Definition\ArrayDefinition;
use Cybot\Dependencies\DI\Definition\Definition;
use Cybot\Dependencies\DI\Definition\Helper\DefinitionHelper;

/**
 * Dumps array definitions.
 *
 * @since 5.0
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ArrayDefinitionDumper implements DefinitionDumper
{
    /**
     * {@inheritdoc}
     */
    public function dump(Definition $definition)
    {
        if (! $definition instanceof ArrayDefinition) {
            throw new \InvalidArgumentException(sprintf(
                'This definition dumper is only compatible with ArrayDefinition objects, %s given',
                get_class($definition)
            ));
        }

        $str = '[' . PHP_EOL;

        foreach ($definition->getValues() as $key => $value) {
            if (is_string($key)) {
                $key = "'" . $key . "'";
            }

            $str .= '    ' . $key . ' => ';

            if ($value instanceof DefinitionHelper) {
                $nestedDefinition = Debug::dumpDefinition($value->getDefinition(''));
                $str .= $this->indent($nestedDefinition);
            } else {
                $str .= var_export($value, true);
            }

            $str .= ',' . PHP_EOL;
        }

        $str .= ']';

        return $str;
    }

    private function indent($str)
    {
        return str_replace("\n", "\n    ", $str);
    }
}
