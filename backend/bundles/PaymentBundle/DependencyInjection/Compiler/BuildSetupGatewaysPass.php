<?php
namespace Dentiman\PaymentBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
class BuildSetupGatewaysPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('payment.service_processor_registry');


        foreach ($container->findTaggedServiceIds('service_processor') as  $id => $tags) {

            foreach ($tags as $attributes) {
                if (false == isset($attributes['alias'])) {
                    throw new \LogicException('The service_processor tag require alias attribute.');
                }

                $definition->addMethodCall('addProcessor', [
                    new Reference($id),
                    $attributes['alias']
                ]);
            }
        }
    }
}
