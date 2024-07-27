<?php
namespace Dentiman\PaymentBundle\Gateway\Liqpay\Action;

use Dentiman\PaymentBundle\Entity\Payment;
use Longman\TelegramBot\Request as TelegramRequest;
use Longman\TelegramBot\Telegram;
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

        if ('POST' == $httpRequest->method && $httpRequest->content) {
            parse_str($httpRequest->content,$result);
            //check if this is post request from liqpay to server url
            if(isset($result['data']) && isset($result['signature'])) {
                $telegram = new Telegram("613211651:AAGZ-wT07vzJDANhbWHzASwTV9aMEs0ftk8", "dentiman_bot");
                $dataEncoded = base64_decode($result['data']);
                $result = TelegramRequest::sendMessage([
                    'chat_id' => '-1001370496379',
                    'text' => $dataEncoded
                ]);
                $data = json_decode($dataEncoded,true);
                if(isset($data['status'])) {
                    $details['status'] = $data['status'];
                }
            }
        }

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
