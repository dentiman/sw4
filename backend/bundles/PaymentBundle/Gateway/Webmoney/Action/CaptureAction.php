<?php
namespace Dentiman\PaymentBundle\Gateway\Webmoney\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Capture;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Reply\HttpPostRedirect;
use Dentiman\PaymentBundle\Gateway\Webmoney\Action\Api\BaseApiAwareAction;
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

        $fields = array(
            'LMI_PAYEE_PURSE' =>  $details['LMI_PAYEE_PURSE'],
            'LMI_PAYMENT_AMOUNT' => $details['LMI_PAYMENT_AMOUNT'],
            'LMI_PAYMENT_NO' => $details['LMI_PAYMENT_NO'],
            'LMI_PAYMENT_DESC' => $details['LMI_PAYMENT_DESC'],
            'LMI_SIM_MODE' =>  $details['LMI_SIM_MODE'],
            'LMI_PAYMER_EMAIL' =>  $details['LMI_PAYMER_EMAIL'],
            'LMI_RESULT_URL' => 'https://'. str_replace(['https://','http://'],'',$notifyToken->getTargetUrl()),
        );
        $details[ 'request'] = $fields;

        $url = 'https://merchant.webmoney.ru/lmi/payment_utf.asp?at='. $details[ 'at'];

        throw new HttpPostRedirect($url, $fields);
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
