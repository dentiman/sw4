<?php

namespace Dentiman\PaymentBundle\Controller;


use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Request\Capture;
use Payum\Core\Request\GetHumanStatus;
use Dentiman\PaymentBundle\Entity\GatewayConfig;
use Dentiman\PaymentBundle\Entity\Order;
use Dentiman\PaymentBundle\Entity\ServiceVariant;
use Dentiman\PaymentBundle\Entity\Payment;
use Dentiman\PaymentBundle\ServiceProcessor\OrderPaymentProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PaymentController extends AbstractController
{

    /**
     * @Route("/capture/order/{order}/gateway/{gatewayConfig}", name="paymentbundle_capture_order", requirements={"order"="\d+","gatewayConfig"="\d+"})
     */
    public function captureOrderAction(Order $order, GatewayConfig $gatewayConfig, OrderPaymentProcessor $orderPaymentProcessor)
    {
        if($order->getStatus() !== Order::STATUS_NEW) {
            $this->addFlash('warning',$this->get('translator')->trans('app.entity.order.capture_warning.only_new_order'));
           return  $this->redirectToRoute('easyadmin',['id'=>$order->getId()]);
        }

        $captureToken = $orderPaymentProcessor->createPaymentCaptureToken( $order,  $gatewayConfig);

        return $this->redirect($captureToken->getTargetUrl());
    }

    /**
     * @Route( "/action/done", name="paymentbundle_payment_action_done")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function doneAction(Request $request)
    {
        $token = $this->get('payum')->getHttpRequestVerifier()->verify($request);
        $gateway = $this->get('payum')->getGateway($token->getGatewayName());
        $gateway->execute($status = new GetHumanStatus($token));
        $payment = $status->getFirstModel();

        if($status->getValue() == GetHumanStatus::STATUS_CAPTURED) {

            $this->addFlash('success',$this->get('translator')->trans('Order payed successfully'));
        }

        return $this->redirectToOrder($payment->getRelatedOrder());
    }


    protected function redirectToOrder(Order $order,$message = null, $type = 'success')
    {
        if ($message) $this->addFlash($type,$this->get('translator')->trans($message));
        return $this->redirect("https://www.stock-watcher.com/premium");
    }

}
