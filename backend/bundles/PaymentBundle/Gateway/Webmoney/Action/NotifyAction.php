<?php
namespace Dentiman\PaymentBundle\Gateway\Webmoney\Action;

use Dentiman\PaymentBundle\Entity\Payment;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Notify;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Reply\HttpResponse;

class NotifyAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param Notify $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());
        /** @var Payment $payment */
        $payment = $request->getFirstModel();
        $this->gateway->execute($httpRequest = new GetHttpRequest());

        // TODO:: https://wiki.webmoney.ru/projects/webmoney/wiki/Web_Merchant_Interface Проверка целостности данных
        if ('POST' == $httpRequest->method &&
            false == empty($httpRequest->request['LMI_PAYMENT_AMOUNT']) &&
            false == empty($httpRequest->request['LMI_SYS_INVS_NO']) &&
            false == empty($httpRequest->request['LMI_SYS_TRANS_NO']) &&
            false == empty($httpRequest->request['LMI_HASH']) &&
            $payment->getId() == $httpRequest->request['LMI_PAYMENT_NO']

        ) {
            $details['success'] = true;
        }

//        if ($details['AMOUNT'] != $httpRequest->query['AMOUNT']) {
//            throw new HttpResponse('The notification is invalid. Code 2', 400);
//        }


        throw new HttpResponse('OK', 200);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Notify &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
