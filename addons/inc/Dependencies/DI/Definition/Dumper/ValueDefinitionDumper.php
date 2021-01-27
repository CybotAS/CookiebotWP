<?php
/**
 * PHP-DI
 *
 * @link      http://php-di.org/
 * @copyright Matthieu Napoli (http://mnapoli.fr/)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace Cybot\Dependencies\DI\Definition\Dumper;

use Cybot\Dependencies\DI\Definition\Definition;
use Cybot\Dependencies\DI\Definition\ValueDefinition;

/**
 * Dumps value definitions.
 *
 * @since 4.1
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ValueDefinitionDumper implements DefinitionDumper
{
    /**
     * {@inheritdoc}
     */
    public function dump(Definition $definition)
    {
        if (! $definition instanceof ValueDefinition) {
            throw new \InvalidArgumentException(sprintf(
                'This definition dumper is only compatible with ValueDefinition objects, %s given',
                get_class($definition)
            ));
        }

        ob_start();

        var_dump($definition->getValue());

        return sprintf(
            "Value (\n    %s\n)",
            trim(ob_get_clean())
        );
    }
}
