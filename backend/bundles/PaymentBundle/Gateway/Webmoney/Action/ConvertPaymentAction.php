<?php
namespace Dentiman\PaymentBundle\Gateway\Webmoney\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;
use Payum\Core\Bridge\Spl\ArrayObject;
use Dentiman\PaymentBundle\Entity\Payment;
use Dentiman\PaymentBundle\Entity\Order;

class ConvertPaymentAction implements ActionInterface
{
    use GatewayAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param Convert $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        /** @var Order $order */
        $order = $payment->getRelatedOrder();
        $gatewayConfig = $payment->getGateway()->getConfig();

        $details = ArrayObject::ensureArrayObject($payment->getDetails());

        if(substr($gatewayConfig['LMI_PAYEE_PURSE'], 0, 1) == 'Z' ) {
            $details['LMI_PAYMENT_AMOUNT'] = $payment->getTotalAmount();
        }

        if(substr($gatewayConfig['LMI_PAYEE_PURSE'], 0, 1) == 'R' ) {
            $details['LMI_PAYMENT_AMOUNT'] = $payment->getTotalAmount()*65;
        }


        $details['LMI_PAYEE_PURSE'] = $gatewayConfig['LMI_PAYEE_PURSE'];// 'Z296056246139';
        $details['LMI_PAYMENT_NO'] = $payment->getId();
        $details['LMI_PAYMENT_DESC'] = $order->getServiceVariant()->getName().' '.$order->getServiceVariant()->getService()->getName(); //$order->getId();
        $details['LMI_SIM_MODE'] = 0;
        $details['LMI_PAYMER_EMAIL'] = $payment->getClientEmail();

        $details['at'] = $gatewayConfig['authtype'] ?? '';

        $request->setResult((array) $details);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof Payment &&
            $request->getTo() == 'array'
        ;
    }
}
