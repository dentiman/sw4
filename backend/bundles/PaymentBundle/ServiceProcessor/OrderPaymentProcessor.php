<?php


namespace Dentiman\PaymentBundle\ServiceProcessor;

use Dentiman\PaymentBundle\Registry\ServiceSetupGatewayRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Payum\Core\Extension\Context;
use Payum\Core\Extension\ExtensionInterface;
use Payum\Core\Payum;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Request\Capture;
use Payum\Core\Request\Generic;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Request\Notify;
use Payum\Core\Security\TokenInterface;
use Dentiman\PaymentBundle\Entity\GatewayConfig;
use Dentiman\PaymentBundle\Entity\Order;
use Dentiman\PaymentBundle\Entity\Payment;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class OrderPaymentProcessor implements ExtensionInterface
{
    /**
     * @var Payum
     */
    protected $payum;

    /**
     * @var \Payum\Core\Storage\StorageInterface
     */
    protected $storage;
    /**
     * @var AuthorizationCheckerInterface
     */
    protected $security;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ServiceSetupGatewayRegistry
     */
    private $serviceSetupGatewayRegistry;

    public function __construct(
        Payum $payum,
        AuthorizationChecker $security,
        EntityManagerInterface $entityManager,
        ServiceSetupGatewayRegistry $serviceSetupGatewayRegistry
    )
    {
        $this->payum = $payum;
        $this->storage = $this->payum->getStorage('Dentiman\PaymentBundle\Entity\Payment');
        $this->security= $security;
        $this->entityManager = $entityManager;
        $this->serviceSetupGatewayRegistry = $serviceSetupGatewayRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function onPreExecute(Context $context): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function onExecute(Context $context): void
    {
    }

    /**  update payment status in and activate order (if status success) after  GetStatus or Notify request has been executed
     * {@inheritdoc}
     */
    public function onPostExecute(Context $context): void
    {
        $previousStack = $context->getPrevious();
        $previousStackSize = count($previousStack);

        if ($previousStackSize > 0) {
            return;
        }

        /** @var Generic $request */
        $request = $context->getRequest();

        if (false === $request instanceof Generic) {
            return;
        }

        if (false === $request instanceof GetStatusInterface && false === $request instanceof Notify) {
            return;
        }

        /** @var Payment $payment */
        $payment = $request->getFirstModel();
        if (false === $payment instanceof Payment) {
            return;
        }

        $gateway = $context->getGateway();
        if ( $request instanceof Notify) {
            $gateway ->execute($httpRequest = new GetHttpRequest());
            $notify = new \Dentiman\PaymentBundle\Entity\Notify();
            $notify->setPayment($payment);

            $notify->setRequest($httpRequest->request);
            $this->entityManager->persist($notify);
            $this->entityManager->flush();
        }

        $gateway->execute($status = new GetHumanStatus($payment));
        $order = $payment->getRelatedOrder();
        $payment->setStatus($status->getValue());
        $this->storage->update($payment);

        //TODO:: unvalidate token
        //TODO:: prevent setup 2 times
        if($status->getValue() == GetHumanStatus::STATUS_CAPTURED) {

            $this->activateOrder($order,$payment);
            if($order->isProcessable()) {
                $serviceProcessor = $this->serviceSetupGatewayRegistry->getProcessor($order->getProcessAlias());
                $serviceProcessor->runSetup($order);
            }
        }
    }


    /**
     * @param Order $order
     * @param GatewayConfig $gatewayConfig
     * @return TokenInterface
     */
    public function createPaymentCaptureToken(Order $order, GatewayConfig $gatewayConfig)
    {
        /** @var Payment $payment */
        $payment = $this->storage->create();
        $payment->setNumber(uniqid());
        $payment->setCurrencyCode('USD');
        $payment->setupWithOrder($order);
        $payment->setGateway($gatewayConfig);
        $this->storage->update($payment);

        return $this->payum->getTokenFactory()->createCaptureToken(
            $gatewayConfig->getFactoryName(),
            $payment,
            'paymentbundle_payment_action_done'
        );
    }

    /** not used for this time
     *  needs for regular payment from cron (without redirect to payment system )
     * @param Order $order
     * @param GatewayConfig $gatewayConfig
     * @return \Payum\Core\Reply\ReplyInterface|GetHumanStatus|null
     */
    public function captureOrderAndGetReply(Order $order, GatewayConfig $gatewayConfig)
    {
        $captureToken = $this->createPaymentCaptureToken( $order,  $gatewayConfig);
        $gateway = $this->payum->getGateway($captureToken->getGatewayName());

        if ($reply = $gateway->execute(new Capture($captureToken), true)) {
            if ($reply instanceof HttpRedirect) {
                return $reply;
            }
            throw new \LogicException('Unsupported reply', null, $reply);
        }

        $gateway->execute($status = new GetHumanStatus($captureToken));
        $this->payum->getHttpRequestVerifier()->invalidate($captureToken);

        return $status;
    }


    /** Can be used for activate order without payment
     * @param Order $order
     * @param Payment|null $payment
     */
    public function activateOrder(Order $order, Payment $payment = null)
    {
        if($order->getStatus() == Order::STATUS_NEW) {
            $order->setStatus(Order::STATUS_ACTIVE);
            $order->setFulfilledPayment($payment);
            $order->setFulfilledAt(new \DateTime('now'));
            $this->entityManager->persist($order);
            $this->entityManager->flush();
            //TODO:: generate Invoice number
        }
    }
}
