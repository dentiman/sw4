<?php
namespace Dentiman\PaymentBundle\Gateway\Liqpay\Action;

use Dentiman\PaymentBundle\Gateway\Liqpay\LiqPay;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Capture;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Reply\HttpPostRedirect;
use Dentiman\PaymentBundle\Gateway\Liqpay\Action\Api\BaseApiAwareAction;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Payum\Core\Security\GenericTokenFactoryAwareTrait;


class CaptureAction extends BaseApiAwareAction implements  GenericTokenFactoryAwareInterface
{
    use GenericTokenFactoryAwareTrait;
    /**
     * {@inheritDoc}
     *
     * @param Capture $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);
        $details = ArrayObject::ensureArrayObject($request->getModel());

        if (!$this->tokenFactory) {
            throw new \LogicException('GenericTokenFactoryExtension must be enabled.');
        }
        $notifyToken = $this->tokenFactory->createNotifyToken(
            $request->getToken()->getGatewayName(),
            $request->getToken()->getDetails()
        );

        $liqPay = new LiqPay($details['public_key'], $details['private_key']);
        $result_url = 'https://'. str_replace(['https://','http://'],'',$notifyToken->getTargetUrl());
        $fields =  $liqPay->cnb_form_raw([
            'action'         => 'pay',
            'amount'         => $details['amount'],
            'currency'       => 'USD',
            'description'    => $details['description'],
            'order_id'       => $details['order_id'],
            'version'        => '3',
            'language'        => 'en',
            'server_url'     =>  $result_url,
            'info'     =>  $details['email'],
        ]);

        throw new HttpPostRedirect($fields['url'],$fields );
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
