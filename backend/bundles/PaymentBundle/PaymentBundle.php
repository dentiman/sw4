<?php

namespace Dentiman\PaymentBundle;

use Dentiman\PaymentBundle\DependencyInjection\Compiler\BuildSetupGatewaysPass;
use Dentiman\PaymentBundle\DependencyInjection\Compiler\RegisterGatewayConfigTypePass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PaymentBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new RegisterGatewayConfigTypePass());
        $container->addCompilerPass(new BuildSetupGatewaysPass());
    }
}
