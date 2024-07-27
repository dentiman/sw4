composer require "payum/payum-bundle" "payum/offline" "php-http/guzzle6-adapter"
composer require ramsey/uuid-doctrine
payum/paypal-express-checkout-nvp


routes.yaml

payment:
  resource: "@PaymentBundle/Controller/"
  type:         annotation
  prefix:       /
payum_all:
  resource: "@PayumBundle/Resources/config/routing/all.xml"


config/packages/payum.yaml
payum:
  security:
    token_storage:
      Dentiman\PaymentBundle\Entity\PaymentToken: { doctrine: orm }
  storages:
    Dentiman\PaymentBundle\Entity\Payment: { doctrine: orm }
  dynamic_gateways:
    config_storage:
      Dentiman\PaymentBundle\Entity\GatewayConfig: { doctrine: orm }
