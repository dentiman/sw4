<?php

namespace App\Controller\Api;


use App\Entity\User;
use Dentiman\PaymentBundle\Entity\GatewayConfig;
use Dentiman\PaymentBundle\Entity\Order;
use Dentiman\PaymentBundle\ServiceProcessor\OrderPaymentProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class OrderPostAction
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    protected $tokenStorage;

    protected $orderPaymentProcessor;


    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage, OrderPaymentProcessor $orderPaymentProcessor)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->orderPaymentProcessor = $orderPaymentProcessor;
    }


    public function __invoke(Order $data): Order
    {
        if(!$data->getGatewayConfigId()) {
            throw new BadRequestHttpException('gatewayConfig Id required');
        }
        $gatewayConfig = $this->em->getRepository(GatewayConfig::class)->find($data->getGatewayConfigId());
        if(!$gatewayConfig) {
            throw new BadRequestHttpException('gatewayConfig not found');
        }
        if(!$this->tokenStorage->getToken()->getUser()) {
            throw new \Exception('11');
        }

        $data->setTotal( $data->getServiceVariant()->getPrice());
        $data->setOwner($this->tokenStorage->getToken()->getUser());
        $this->em->persist($data);
        $this->em->flush();
        $captureToken = $this->orderPaymentProcessor->createPaymentCaptureToken( $data,  $gatewayConfig);
        $captureToken->getTargetUrl();
        $data->setTargetUrl($captureToken->getTargetUrl());
        return $data;
    }
}
