<?php

namespace Dentiman\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Exception\InvalidArgumentException;
use Payum\Core\Model\CreditCardInterface;
use Payum\Core\Security\SensitiveValue;
use Payum\Core\Security\Util\Mask;

/**
 * @ORM\Entity()
 * @ORM\Table(name="payment_creadit_card")
 */
class CreditCard implements CreditCardInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $brand;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $maskedHolder;

    /**
     * @var string
     */
    private $holder;

    /**
     * @var SensitiveValue
     *
     * @deprecated
     */
    protected $securedHolder;

    /**
     * @var string
     */
    private $number;

    /**
     * @var SensitiveValue
     *
     * @deprecated
     */
    protected $securedNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="maskedNumber", type="string", length=255, nullable=true, unique=false)
     */
    protected $maskedNumber;

    /**
     * @var string
     *
     */
    private $securityCode;

    /**
     * @var SensitiveValue
     *
     * @deprecated
     */
    protected $securedSecurityCode;

    /**
     * @var \DateTime
     *
     */
    private $expireAt;

    /**
     * @var SensitiveValue
     *
     * @deprecated
     */
    protected $securedExpireAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;


    public function __construct()
    {
        $this->securedHolder = SensitiveValue::ensureSensitive(null);
        $this->securedSecurityCode = SensitiveValue::ensureSensitive(null);
        $this->securedNumber = SensitiveValue::ensureSensitive(null);
        $this->securedExpireAt = SensitiveValue::ensureSensitive(null);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHolder(): ?string
    {
        return $this->holder;
    }

    public function setHolder($holder): self
    {
        $this->securedHolder = SensitiveValue::ensureSensitive($holder);
        $this->maskedHolder = Mask::mask($this->securedHolder->peek());

        // BC
        $this->holder = $this->securedHolder->peek();
        return $this;
    }

    public function setMaskedHolder($maskedHolder)
    {
        $this->maskedHolder = $maskedHolder;
    }

    /**
     * {@inheritDoc}
     */
    public function getMaskedHolder()
    {
        return $this->maskedHolder;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * {@inheritDoc}
     */
    public function setNumber($number)
    {
        $this->securedNumber = SensitiveValue::ensureSensitive($number);
        $this->maskedNumber = Mask::mask($this->securedNumber->peek());

        //BC
        $this->number = $this->securedNumber->peek();
    }

    /**
     * {@inheritDoc}
     */
    public function setMaskedNumber($maskedNumber)
    {
        return $this->maskedNumber = $maskedNumber;
    }

    /**
     * {@inheritDoc}
     */
    public function getMaskedNumber()
    {
        return $this->maskedNumber;
    }

    public function getSecurityCode(): ?string
    {
        return $this->securityCode;
    }

    public function setSecurityCode( $securityCode): self
    {
        $this->securedSecurityCode = SensitiveValue::ensureSensitive($securityCode);

        // BC
        $this->securityCode = $this->securedSecurityCode->peek();

        return $this;
    }

    public function getExpireAt()
    {
        return $this->expireAt;
    }

    public function setExpireAt($date = null)
    {
        $date = SensitiveValue::ensureSensitive($date);

        if (false == (null === $date->peek() || $date->peek() instanceof \DateTime)) {
            throw new InvalidArgumentException('The date argument must be either instance of DateTime or null');
        }

        $this->securedExpireAt = $date;

        // BC
        $this->expireAt = $this->securedExpireAt->peek();

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken($token): self
    {
        $this->token = $token;

        return $this;
    }


    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }


    /**
     * @param string $brand
     */
    public function setBrand($brand): void
    {
        $this->brand = $brand;
    }

    public function secure()
    {
        $this->holder = $this->number = $this->expireAt = $this->securityCode = null;
    }

    public function isValidNumber()
    {
        if( is_numeric($this->number) && strlen($this->number) == 16) return true;

        return false;

    }

}
