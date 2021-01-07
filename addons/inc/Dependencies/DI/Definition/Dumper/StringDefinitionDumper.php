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
use Cybot\Dependencies\DI\Definition\StringDefinition;

/**
 * Dumps string definitions.
 *
 * @since 5.0
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class StringDefinitionDumper implements DefinitionDumper
{
    /**
     * {@inheritdoc}
     */
    public function dump(Definition $definition)
    {
        if (! $definition instanceof StringDefinition) {
            throw new \InvalidArgumentException(sprintf(
                'This definition dumper is only compatible with StringDefinition objects, %s given',
                get_class($definition)
            ));
        }

        return $definition->getExpression();
    }
}
