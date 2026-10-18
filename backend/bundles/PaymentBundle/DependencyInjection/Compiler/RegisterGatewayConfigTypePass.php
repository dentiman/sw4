<?php

namespace Dentiman\PaymentBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterGatewayConfigTypePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('payment.form_registry.form_type_registry')) {
            return;
        }

        $formRegistry = $container->findDefinition('payment.form_registry.form_type_registry');
        $gatewayFactories = [];

        $gatewayConfigurationTypes = $container->findTaggedServiceIds('payment.gateway_configuration_type');

        foreach ($gatewayConfigurationTypes as $id => $attributes) {
            if (!isset($attributes[0]['type']) || !isset($attributes[0]['label'])) {
                throw new \InvalidArgumentException('Tagged gateway configuration type needs to have `type` and `label` attributes.');
            }

            $gatewayFactories[$attributes[0]['type']] = $attributes[0]['label'];

            $formRegistry->addMethodCall(
                'add',
                ['gateway_config', $attributes[0]['type'], $container->getDefinition($id)->getClass()]
            );
        }

//        $gatewayFactories = array_merge($gatewayFactories, ['offline' => 'sylius.payum_gateway_factory.offline']);
//        ksort($gatewayFactories);

        $container->setParameter('dentiman.gateway_factories', $gatewayFactories);
    }
}
