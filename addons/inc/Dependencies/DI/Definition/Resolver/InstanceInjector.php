<?php
/**
 * PHP-DI
 *
 * @link      http://mnapoli.github.com/PHP-DI/
 * @copyright Matthieu Napoli (http://mnapoli.fr/)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace Cybot\Dependencies\DI\Definition\Resolver;

use Cybot\Dependencies\DI\Definition\Definition;
use Cybot\Dependencies\DI\Definition\InstanceDefinition;
use Cybot\Dependencies\DI\DependencyException;
use Cybot\Dependencies\Interop\Container\Exception\NotFoundException;

/**
 * Injects dependencies on an existing instance.
 *
 * @since 5.0
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class InstanceInjector extends ObjectCreator
{
    /**
     * Injects dependencies on an existing instance.
     *
     * @param InstanceDefinition $definition
     *
     * {@inheritdoc}
     */
    public function resolve(Definition $definition, array $parameters = [])
    {
        $this->assertIsInstanceDefinition($definition);

        try {
            $this->injectMethodsAndProperties($definition->getInstance(), $definition->getObjectDefinition());
        } catch (NotFoundException $e) {
            $message = sprintf(
                "Error while injecting dependencies into %s: %s",
                get_class($definition->getInstance()),
                $e->getMessage()
            );
            throw new DependencyException($message, 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isResolvable(Definition $definition, array $parameters = [])
    {
        $this->assertIsInstanceDefinition($definition);

        return true;
    }

    private function assertIsInstanceDefinition(Definition $definition)
    {
        if (!$definition instanceof InstanceDefinition) {
            throw new \InvalidArgumentException(sprintf(
                'This definition resolver is only compatible with InstanceDefinition objects, %s given',
                get_class($definition)
            ));
        }
    }
}
