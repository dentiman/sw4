<?php


namespace Dentiman\PaymentBundle\Registry;


use Dentiman\PaymentBundle\ServiceProcessor\ServiceProcessorInterface;

class ServiceSetupGatewayRegistry
{
    private $setupProcessors;

    public function __construct()
    {
        $this->setupProcessors = [];
    }

    public function addProcessor( $setupGateway, $alias)
    {
        $this->setupProcessors[$alias] = $setupGateway;
    }

    /**
     * @param $alias
     * @return ServiceProcessorInterface
     */
    public function getProcessor($alias) : ServiceProcessorInterface
    {
        if (!array_key_exists($alias, $this->setupProcessors)) {
            throw new \LogicException("The service processor $alias was not registered");
        }
        return $this->setupProcessors[$alias];
    }
}
