<?php
/**
 * PHP-DI
 *
 * @link      http://php-di.org/
 * @copyright Matthieu Napoli (http://mnapoli.fr/)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace Cybot\Dependencies\DI\Definition\Dumper;

use Cybot\Dependencies\DI\Definition\DecoratorDefinition;
use Cybot\Dependencies\DI\Definition\Definition;

/**
 * Dumps decorator definitions.
 *
 * @since 5.0
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class DecoratorDefinitionDumper implements DefinitionDumper
{
    /**
     * {@inheritdoc}
     */
    public function dump(Definition $definition)
    {
        if (! $definition instanceof DecoratorDefinition) {
            throw new \InvalidArgumentException(sprintf(
                'This definition dumper is only compatible with DecoratorDefinition objects, %s given',
                get_class($definition)
            ));
        }

        return 'Decorate(' . $definition->getSubDefinitionName() . ')';
    }
}
