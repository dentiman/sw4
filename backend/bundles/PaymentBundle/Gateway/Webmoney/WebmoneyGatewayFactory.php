<?php
namespace Dentiman\PaymentBundle\Gateway\Webmoney;

use Dentiman\PaymentBundle\Gateway\Webmoney\Action\AuthorizeAction;
use Dentiman\PaymentBundle\Gateway\Webmoney\Action\CancelAction;
use Dentiman\PaymentBundle\Gateway\Webmoney\Action\ConvertPaymentAction;
use Dentiman\PaymentBundle\Gateway\Webmoney\Action\CaptureAction;
use Dentiman\PaymentBundle\Gateway\Webmoney\Action\NotifyAction;
use Dentiman\PaymentBundle\Gateway\Webmoney\Action\RefundAction;
use Dentiman\PaymentBundle\Gateway\Webmoney\Action\StatusAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

class WebmoneyGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'webmoney',
            'payum.factory_title' => 'webmoney',
            'payum.action.convert_payment' => new ConvertPaymentAction(),
            'payum.action.capture' => new CaptureAction(),
            'payum.action.authorize' => new AuthorizeAction(),
            'payum.action.refund' => new RefundAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.status' => new StatusAction(),

        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = array(
                'sandbox' => true,
            );
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = [];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return new Api((array) $config, $config['payum.http_client'], $config['httplug.message_factory']);
            };
        }
    }
}
