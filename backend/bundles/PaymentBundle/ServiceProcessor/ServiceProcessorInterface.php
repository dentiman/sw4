<?php


namespace Dentiman\PaymentBundle\ServiceProcessor;


use Dentiman\PaymentBundle\Entity\Order;

interface ServiceProcessorInterface
{
    public function runSetup(Order $order);

    public function isActive();
}
