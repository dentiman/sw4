<?php

namespace Dentiman\PaymentBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="payment_order")
 * @ORM\HasLifecycleCallbacks()
 */
class Order
{

    const STATUS_NEW = 'new';
    const STATUS_ACTIVE = 'active';
    const STATUS_CANCELED = 'canceled';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /** @var integer $gatewayConfigId */
    private $gatewayConfigId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updatedAt", type="datetime", precision=0, scale=0, nullable=true, unique=false)
     */
    private $updatedAt;


    /**
     * @ORM\ManyToOne(targetEntity="ServiceVariant")
     * @ORM\JoinColumn(nullable=false)
     */
    private $serviceVariant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status = 'new';

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fulfilledAt;

    /**
     * @var \App\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orders")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     * })
     */
    private $owner;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $invoiceNumber;

    /**
     * @ORM\OneToMany(targetEntity="Dentiman\PaymentBundle\Entity\Payment", mappedBy="relatedOrder", orphanRemoval=true)
     */
    private $payments;

    /**
     * @ORM\OneToOne(targetEntity="Dentiman\PaymentBundle\Entity\Payment", cascade={"persist", "remove"})
     */
    private $fulfilledPayment;

    /**
     * @var CustomerInterface $customer
     */
    private $customer;

    /** @var ?string $targetUrl  */
    protected $targetUrl;

    /**
     * @ORM\Column(type="boolean")
     */
    private $processed = false;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * Pre persist event listener
     * @ORM\PrePersist
     */
    public function beforeSave()
    {
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));

    }

    /**
     * Invoked before the entity is updated.
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

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

    /**
     * @return mixed
     */
    public function getServiceVariant(): ServiceVariant
    {
        return $this->serviceVariant;
    }

    /**
     * @param mixed $serviceVariant
     */
    public function setServiceVariant($serviceVariant): void
    {
        $this->serviceVariant = $serviceVariant;
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

    public function getFulfilledAt(): ?\DateTimeInterface
    {
        return $this->fulfilledAt;
    }

    public function setFulfilledAt(?\DateTimeInterface $fulfilledAt): self
    {
        $this->fulfilledAt = $fulfilledAt;

        return $this;
    }

    /**
     * @return \App\Entity\User
     */
    public function getOwner(): ?\App\Entity\User
    {
        return $this->owner;
    }

    /**
     * @param \App\Entity\User $owner
     */
    public function setOwner(\App\Entity\User $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     */
    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }



    /**
     * @return Collection|Payment[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setRelatedOrder($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->contains($payment)) {
            $this->payments->removeElement($payment);
            // set the owning side to null (unless already changed)
            if ($payment->getRelatedOrder() === $this) {
                $payment->setRelatedOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * @param mixed $invoiceNumber
     */
    public function setInvoiceNumber($invoiceNumber): void
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    public function getFulfilledPayment(): ?Payment
    {
        return $this->fulfilledPayment;
    }

    public function setFulfilledPayment(?Payment $fulfilledPayment): self
    {
        $this->fulfilledPayment = $fulfilledPayment;
        return $this;
    }

    public function setUp()
    {
        $this->total = $this->serviceVariant->getPrice();
    }

    /**
     * @return bool
     */
    public function isProcessable()
    {
        if($this->isProcessed() === true) return false;
        return $this->getServiceVariant()
        ->getService()
        ->getCode() !== null;
    }

    /**
     * @return string|null
     *
     */
    public function getProcessAlias()
    {
        return $this->getServiceVariant()
            ->getService()
            ->getCode();
    }

    /**
     * @return CustomerInterface
     */
    public function getCustomer(): ?CustomerInterface
    {
        return $this->customer;
    }

    /**
     * @param CustomerInterface $customer
     */
    public function setCustomer(?CustomerInterface $customer): void
    {
        $this->customer = $customer;
    }

    public function isProcessed(): ?bool
    {
        return $this->processed;
    }

    public function setProcessed(bool $processed): self
    {
        $this->processed = $processed;

        return $this;
    }

    /**
     * @return int
     */
    public function getGatewayConfigId(): int
    {
        return $this->gatewayConfigId;
    }

    /**
     * @param int $gatewayConfigId
     */
    public function setGatewayConfigId(int $gatewayConfigId): void
    {
        $this->gatewayConfigId = $gatewayConfigId;
    }

    /**
     * @return string|null
     */
    public function getTargetUrl(): ?string
    {
        return $this->targetUrl;
    }

    /**
     * @param string|null $targetUrl
     */
    public function setTargetUrl(?string $targetUrl): void
    {
        $this->targetUrl = $targetUrl;
    }


}
