<?php

namespace Cybot\Dependencies\DI\Definition\Resolver;

use Cybot\Dependencies\DI\Definition\Definition;
use Cybot\Dependencies\DI\Definition\Exception\DefinitionException;
use Cybot\Dependencies\DI\Proxy\ProxyFactory;
use Cybot\Dependencies\Interop\Container\ContainerInterface;

/**
 * Dispatches to more specific resolvers.
 *
 * Dynamic dispatch pattern.
 *
 * @since 5.0
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ResolverDispatcher implements DefinitionResolver
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ProxyFactory
     */
    private $proxyFactory;

    private $valueResolver;
    private $arrayResolver;
    private $factoryResolver;
    private $decoratorResolver;
    private $aliasResolver;
    private $objectResolver;
    private $instanceResolver;
    private $envVariableResolver;
    private $stringResolver;

    public function __construct(ContainerInterface $container, ProxyFactory $proxyFactory)
    {
        $this->container = $container;
        $this->proxyFactory = $proxyFactory;
    }

    /**
     * Resolve a definition to a value.
     *
     * @param Definition $definition Object that defines how the value should be obtained.
     * @param array      $parameters Optional parameters to use to build the entry.
     *
     * @throws DefinitionException If the definition cannot be resolved.
     *
     * @return mixed Value obtained from the definition.
     */
    public function resolve(Definition $definition, array $parameters = [])
    {
        $definitionResolver = $this->getDefinitionResolver($definition);

        return $definitionResolver->resolve($definition, $parameters);
    }

    /**
     * Check if a definition can be resolved.
     *
     * @param Definition $definition Object that defines how the value should be obtained.
     * @param array      $parameters Optional parameters to use to build the entry.
     *
     * @return bool
     */
    public function isResolvable(Definition $definition, array $parameters = [])
    {
        $definitionResolver = $this->getDefinitionResolver($definition);

        return $definitionResolver->isResolvable($definition, $parameters);
    }

    /**
     * Returns a resolver capable of handling the given definition.
     *
     * @param Definition $definition
     *
     * @throws \RuntimeException No definition resolver was found for this type of definition.
     * @return DefinitionResolver
     */
    private function getDefinitionResolver(Definition $definition)
    {
        $definitionType = get_class($definition);

        switch ($definitionType) {
            case 'Cybot\Dependencies\DI\Definition\ValueDefinition':
                if (! $this->valueResolver) {
                    $this->valueResolver = new ValueResolver();
                }
                return $this->valueResolver;
            case 'Cybot\Dependencies\DI\Definition\ArrayDefinition':
            case 'Cybot\Dependencies\DI\Definition\ArrayDefinitionExtension':
                if (! $this->arrayResolver) {
                    $this->arrayResolver = new ArrayResolver($this);
                }
                return $this->arrayResolver;
            case 'Cybot\Dependencies\DI\Definition\FactoryDefinition':
                if (! $this->factoryResolver) {
                    $this->factoryResolver = new FactoryResolver($this->container);
                }
                return $this->factoryResolver;
            case 'Cybot\Dependencies\DI\Definition\DecoratorDefinition':
                if (! $this->decoratorResolver) {
                    $this->decoratorResolver = new DecoratorResolver($this->container, $this);
                }
                return $this->decoratorResolver;
            case 'Cybot\Dependencies\DI\Definition\AliasDefinition':
                if (! $this->aliasResolver) {
                    $this->aliasResolver = new AliasResolver($this->container);
                }
                return $this->aliasResolver;
            case 'Cybot\Dependencies\DI\Definition\ObjectDefinition':
                if (! $this->objectResolver) {
                    $this->objectResolver = new ObjectCreator($this, $this->proxyFactory);
                }
                return $this->objectResolver;
            case 'Cybot\Dependencies\DI\Definition\InstanceDefinition':
                if (! $this->instanceResolver) {
                    $this->instanceResolver = new InstanceInjector($this, $this->proxyFactory);
                }
                return $this->instanceResolver;
            case 'Cybot\Dependencies\DI\Definition\EnvironmentVariableDefinition':
                if (! $this->envVariableResolver) {
                    $this->envVariableResolver = new EnvironmentVariableResolver($this);
                }
                return $this->envVariableResolver;
            case 'Cybot\Dependencies\DI\Definition\StringDefinition':
                if (! $this->stringResolver) {
                    $this->stringResolver = new StringResolver($this->container);
                }
                return $this->stringResolver;
            default:
                throw new \RuntimeException("No definition resolver was configured for definition of type $definitionType");
        }
    }
}
