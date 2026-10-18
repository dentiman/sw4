<?php
/**
 * Created by PhpStorm.
 * User: guest
 * Date: 20.12.18
 * Time: 15:07
 */

namespace Dentiman\PaymentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Model\BankAccountInterface;
use Payum\Core\Model\CreditCardInterface;
use Payum\Core\Model\DirectDebitPaymentInterface;
use Payum\Core\Model\Payment as BasePayment;
use Payum\Core\Model\PaymentInterface;

/**
 * @ORM\Table(name="payment_payment")
 * @ORM\Entity
 */
class Payment extends BasePayment
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
     * @ORM\ManyToOne(targetEntity="Dentiman\PaymentBundle\Entity\Order", inversedBy="payments", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="cascade")
     */
    private $relatedOrder;

    /**
     * @ORM\ManyToOne(targetEntity="Dentiman\PaymentBundle\Entity\GatewayConfig")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gateway;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status = 'new';

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var CreditCardInterface|null
     * @ORM\OneToOne(targetEntity="CreditCard", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $creditCard;

    /**
     * @ORM\OneToMany(targetEntity="Dentiman\PaymentBundle\Entity\Notify", mappedBy="payment", orphanRemoval=true)
     */
    private $notifies;

    public function __construct()
    {
        parent::__construct();
        $this->createdAt = new \DateTime();
        $this->notifies = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getRelatedOrder(): ?Order
    {
        return $this->relatedOrder;
    }

    public function setRelatedOrder(?Order $relatedOrder): self
    {
        $this->relatedOrder = $relatedOrder;

        return $this;
    }

    public function getGateway(): ?GatewayConfig
    {
        return $this->gateway;
    }

    public function setGateway(?GatewayConfig $gateway): self
    {
        $this->gateway = $gateway;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setupWithOrder(Order $order)
    {
        $this->setTotalAmount($order->getTotal());
        $this->setDescription($order->getServiceVariant()->getName());
        $this->setClientEmail($order->getOwner()->getEmail());
        $this->setClientId($this->clientEmail);
        $this->setRelatedOrder($order);
    }

    public function __toString()
    {
        return (string)$this->getId();
    }

    /**
     * @return Collection|Notify[]
     */
    public function getNotifies(): Collection
    {
        return $this->notifies;
    }

    public function addNotify(Notify $notify): self
    {
        if (!$this->notifies->contains($notify)) {
            $this->notifies[] = $notify;
            $notify->setPayment($this);
        }

        return $this;
    }

    public function removeNotify(Notify $notify): self
    {
        if ($this->notifies->contains($notify)) {
            $this->notifies->removeElement($notify);
            // set the owning side to null (unless already changed)
            if ($notify->getPayment() === $this) {
                $notify->setPayment(null);
            }
        }

        return $this;
    }

}
