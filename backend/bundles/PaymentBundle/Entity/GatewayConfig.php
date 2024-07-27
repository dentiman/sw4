<?php

namespace Dentiman\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Model\GatewayConfig as BaseGatewayConfig;
use Payum\Core\Model\GatewayConfigInterface;
use Payum\Core\Security\CryptedInterface;
use Payum\Core\Security\CypherInterface;


/**
 * @ORM\Table(name="payment_gateway_config")
 * @ORM\Entity
 */
class GatewayConfig implements GatewayConfigInterface, CryptedInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $id
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="factory_name", type="string", length=255, nullable=false, unique=false)
     */
    protected $factoryName;

    /**
     * @var string
     *
     * @ORM\Column(name="gateway_name", type="string", length=255, nullable=false, unique=false)
     */
    protected $gatewayName;


    /**
     * Note: This should not be persisted to database
     * @ORM\Column(name="decrypted_config", type="json_array")
     */
    protected $decryptedConfig;

    /**
     * @var array
     * @ORM\Column(name="config", type="json_array")
     */
    protected $config;


    /**
     * @var bool
     * @ORM\Column( type="boolean")
     */
    protected $isEnabled;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    public function __construct()
    {
        $this->config = [];
        $this->decryptedConfig = [];
    }

    /**
     * {@inheritDoc}
     */
    public function getFactoryName()
    {
        return $this->factoryName;
    }

    /**
     * {@inheritDoc}
     */
    public function setFactoryName($factoryName)
    {
        $this->factoryName = $factoryName;
    }

    /**
     * @return string
     */
    public function getGatewayName()
    {
        return $this->gatewayName;
    }

    /**
     * @param string $gatewayName
     */
    public function setGatewayName($gatewayName)
    {
        $this->gatewayName = $gatewayName;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        if (isset($this->config['encrypted'])) {
            return $this->decryptedConfig;
        }

        return $this->config;
    }

    /**
     * {@inheritDoc}
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        $this->decryptedConfig = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt(CypherInterface $cypher)
    {
        if (empty($this->config['encrypted'])) {
            return;
        }

        foreach ($this->config as $name => $value) {
            if ('encrypted' == $name || is_bool($value)) {
                $this->decryptedConfig[$name] = $value;

                continue;
            }

            $this->decryptedConfig[$name] = $cypher->decrypt($value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt(CypherInterface $cypher)
    {
        $this->decryptedConfig['encrypted'] = true;

        foreach ($this->decryptedConfig as $name => $value) {
            if ('encrypted' == $name || is_bool($value)) {
                $this->config[$name] = $value;

                continue;
            }

            $this->config[$name] = $cypher->encrypt($value);
        }
    }

    public function getLabel()
    {
        if(is_array($this->config) && isset($this->config['label']))  { return $this->config['label'];}
        return $this->getFactoryName();
    }

    /**
     * @return bool
     */
    public function getIsEnabled(): bool
    {
        if(!$this->isEnabled) return  false;
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     */
    public function setIsEnabled(bool $isEnabled): void
    {
        $this->isEnabled = $isEnabled;
    }


}
