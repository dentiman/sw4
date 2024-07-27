<?php
namespace Dentiman\PaymentBundle\Gateway\Liqpay\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;

class StatusAction implements ActionInterface
{
    /**
     * {@inheritDoc}
     * @param GetStatusInterface $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());
        if (count(iterator_to_array($details)) == 0) {
            $request->markNew();
            return;
        }

        if(isset($details['status'])) {
            switch ($details['status']) {
                case 'success':
                $request->markCaptured();
                break;
                case 'failure':
                $request->markFailed();
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
