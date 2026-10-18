<?php
namespace Dentiman\PaymentBundle\Gateway\Liqpay\Action;

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

        $details['amount'] = $payment->getTotalAmount();
        $details['order_id'] = 'sw4-'. $payment->getId();
        $details['description'] = $order->getServiceVariant()->getService()->getName().' '.$order->getServiceVariant()->getName();
        $details['email'] =  $payment->getClientEmail();
        //TODO:: replace real data
        $details['public_key'] =  $gatewayConfig['public_key'];
        $details['private_key'] = $gatewayConfig['private_key'];


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
