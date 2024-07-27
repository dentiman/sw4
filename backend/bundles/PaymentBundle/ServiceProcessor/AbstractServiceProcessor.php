<?php


namespace Dentiman\PaymentBundle\ServiceProcessor;

use Doctrine\Persistence\ObjectManager;
use Dentiman\PaymentBundle\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

abstract class AbstractServiceProcessor implements ServiceProcessorInterface
{


    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;


    public function __construct(
        EntityManagerInterface $manager,
        TokenStorageInterface $tokenStorage
    )
    {
        $this->manager = $manager;
        $this ->tokenStorage = $tokenStorage;
    }


    public function runSetup(Order $order)
    {
        if($order->isProcessed() === true || $order->isProcessable() === false)   return;

        $this->setup($order);
        $order->setProcessed(true);

        $this->manager->persist($order);
        $this->manager->flush();

    }

    protected function setup(Order $order)
    {
       return new \LogicException('not implemented');
    }

    public function isActive()
    {
        return false;
    }

    protected function getUser()
    {

        if (null === $token = $this->tokenStorage->getToken()) {
            return;
        }

        if (!\is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }


}
