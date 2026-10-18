<?php

namespace App\Entity;

use App\Entity\Feed\MainTickers;
use App\Entity\FeedImport\Basic\FeedBasicTickers;
use App\Entity\Presets\PanelLayout;
use App\Entity\Presets\ScreenerFilter;
use Dentiman\PaymentBundle\Entity\Order;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherAwareInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\UuidV1;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"})
 */
class User implements UserInterface, PasswordHasherAwareInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    private $username;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $roles = [];


    private $plainPassword;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var ?integer $validateId
     * @ORM\Column(type="integer", length=8, nullable=true )
     */
    private $validateId;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $language;

    /**
     * @var ?string $resetCode
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $resetCode;

    /**
     * @var ?string $clientIp
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clientIp;

    /** @var Order[]
     * @ORM\OneToMany(targetEntity="Dentiman\PaymentBundle\Entity\Order", mappedBy="owner", orphanRemoval=true)
     */
    private $orders;

    /**
     * @var ?\DateTime $premiumExpiration
     * @ORM\Column(type="date", nullable=true)
     */
    protected $premiumExpiration;

    /**
     * @var boolean $primaryDrawerOn
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $primaryDrawerOn = true;

    /**
     * @var boolean $lightMode
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $lightMode = false;

    /**
     * @var boolean $watchListByTabs
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $watchListByTabs = false;

    /**
     * @var ?string $newTicker
     */
    protected $newTicker;

    /**
     * @var MainTickers[] $historyQuotes
     */
    private $historyQuotes = [];

    /**
     * @var array $historyTickers
     * @ORM\Column(type="array", nullable=true)
     */
    private $historyTickers = [];


    /**
     * @var ?string $activeFilterPresetId
     * @ORM\Column(type="string", nullable=true)
     */
    private $activeFilterPresetId;

    /**
     * @var ?string $activeChartPresetId
     * @ORM\Column(type="string", nullable=true)
     */
    private $activeChartPresetId;

    /**
     * @var ?string $activeTablePreset
     * @ORM\Column(type="string", nullable=true)
     */
    private $activeTablePresetId;

    /**
     * @var ?string $watchlistId
     * @ORM\Column(type="string", nullable=true)
     */
    private $watchlistId;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $salt;


    /**
     * @var ?\DateTime $emailing
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $emailing;

    /**
     * @var ?\DateTime $lastLogin
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastLogin;

    public function getPasswordHasherName(): ?string
    {
         return  'app_hasher';
    }

    public function __construct()
    {
        $this->salt = Uuid::uuid4();
    }


    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        if($this->roles === null) {
            $roles = [];
        } else {
            $roles = $this->roles;
        }

        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        if($this->isPremium()) {
            $roles[] = 'ROLE_PREMIUM';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
       return $this->salt;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
         $this->plainPassword = null;
    }

    public function __toString()
    {
        return $this->getEmail();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }


    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getValidateId(): ?int
    {
        return $this->validateId;
    }

    /**
     * @param int|null $validateId
     */
    public function setValidateId(?int $validateId): void
    {
        $this->validateId = $validateId;
    }

    /**
     * @return string|null
     */
    public function getClientIp(): ?string
    {
        return $this->clientIp;
    }

    /**
     * @param string|null $clientIp
     */
    public function setClientIp(?string $clientIp): void
    {
        $this->clientIp = $clientIp;
    }

    /**
     * @return string|null
     */
    public function getResetCode(): ?string
    {
        return $this->resetCode;
    }

    /**
     * @param string|null $resetCode
     */
    public function setResetCode(?string $resetCode): void
    {
        $this->resetCode = $resetCode;
    }


    public function getPremiumExpiration(): ?\DateTimeInterface
    {
        return $this->premiumExpiration;
    }

    /**
     * @param string|null $premiumExpiration
     */
    public function setPremiumExpiration(?\DateTimeInterface $premiumExpiration): void
    {
        $this->premiumExpiration = $premiumExpiration;
    }

    public function getPremium(): bool
    {
        return $this->isPremium();
    }

    public function isPremium(): bool
    {
        if(!$this->premiumExpiration) return false;
        $today = new \DateTime();
        $todayMorning = new \DateTime($today->format('Y-m-d').' 00:00:00');
        return  $this->premiumExpiration->getTimestamp() >= $todayMorning->getTimestamp();
    }

    public function extendPremium($days)
    {
        $today = new \DateTime();
        if($this->premiumExpiration && $this->premiumExpiration->getTimestamp() > $today->getTimestamp()) {
            $newDateToExpire = new \DateTime($this->premiumExpiration->format('Y-m-d') . ' 00:00:00');
        } else {
            $newDateToExpire = new \DateTime($today->format('Y-m-d').' 00:00:00');
        }
        $this->premiumExpiration =   $newDateToExpire->modify(sprintf('+%s day',$days));
    }

    /**
     * @return Order[]
     */
    public function getOrders(): array
    {
        return $this->orders;
    }

    /**
     * @param Order[] $orders
     */
    public function setOrders(array $orders): void
    {
        $this->orders = $orders;
    }

    /**
     * @return bool
     */
    public function isPrimaryDrawerOn(): bool
    {
        if($this->primaryDrawerOn === true) return true;
        return false;
    }

    /**
     * @param bool $primaryDrawerOn
     */
    public function setPrimaryDrawerOn(bool $primaryDrawerOn): void
    {
        $this->primaryDrawerOn = $primaryDrawerOn;
    }

    /**
     * @return bool
     */
    public function isLightMode(): bool
    {
        if(!$this->lightMode) return false;
        return $this->lightMode;
    }

    /**
     * @param bool $lightMode
     */
    public function setLightMode(bool $lightMode): void
    {
        $this->lightMode = $lightMode;
    }



    /**
     * @return bool
     */
    public function isWatchListByTabs(): bool
    {
        if(!$this->watchListByTabs) return  false;
        return $this->watchListByTabs;
    }

    /**
     * @param bool $watchListByTabs
     */
    public function setWatchListByTabs(bool $watchListByTabs): void
    {
        $this->watchListByTabs = $watchListByTabs;
    }

    /**
     * @return Collection|MainTickers[]
     */
    public function getHistoryQuotes(): array
    {
        if(!is_array($this->historyQuotes)) return [];
        return $this->historyQuotes;
    }

    /**
     * @param MainTickers[] $historyQuotes
     */
    public function setHistoryQuotes($historyQuotes): void
    {
        $this->historyQuotes = $historyQuotes;
    }



    /**
     * @return string|null
     */
    public function getNewTicker(): ?string
    {
        return $this->newTicker;
    }

    /**
     * @param string|null $newTicker
     */
    public function setNewTicker(?string $newTicker): void
    {
        $this->newTicker = $newTicker;
    }

    /**
     * @return array
     */
    public function getHistoryTickers(): array
    {
        if( !is_array($this->historyTickers)) return [];
        return $this->historyTickers;
    }

    /**
     * @param string $historyTicker
     */
    public function addHistoryTicker(string $historyTicker): void
    {
        if(!is_array($this->historyTickers) || empty($this->historyTickers)) {
            $this->historyTickers = [$historyTicker];
            return;
        }
        $newArray = array_diff($this->historyTickers,[$historyTicker]);
        array_unshift($newArray,$historyTicker);
        if(count($newArray)>= 10) {  array_pop($newArray); }
        $this->historyTickers =  $newArray;
    }

    /**
     * @return string|null
     */
    public function getActiveFilterPresetId(): ?string
    {
        return $this->activeFilterPresetId;
    }

    /**
     * @param string|null $activeFilterPresetId
     */
    public function setActiveFilterPresetId(?string $activeFilterPresetId): void
    {
        $this->activeFilterPresetId = $activeFilterPresetId;
    }

    /**
     * @return string|null
     */
    public function getActiveChartPresetId(): ?string
    {
        return $this->activeChartPresetId;
    }

    /**
     * @param string|null $activeChartPresetId
     */
    public function setActiveChartPresetId(?string $activeChartPresetId): void
    {
        $this->activeChartPresetId = $activeChartPresetId;
    }

    /**
     * @return string|null
     */
    public function getActiveTablePresetId(): ?string
    {
        return $this->activeTablePresetId;
    }

    /**
     * @param string|null $activeTablePresetId
     */
    public function setActiveTablePresetId(?string $activeTablePresetId): void
    {
        $this->activeTablePresetId = $activeTablePresetId;
    }

    /**
     * @return string|null
     */
    public function getWatchlistId(): ?string
    {
        return $this->watchlistId;
    }

    /**
     * @param string|null $watchlistId
     */
    public function setWatchlistId(?string $watchlistId): void
    {
        $this->watchlistId = $watchlistId;
    }

    /**
     * @return \DateTime|null
     */
    public function getEmailing(): ?\DateTime
    {
        return $this->emailing;
    }

    /**
     * @param \DateTime|null $emailing
     */
    public function setEmailing(?\DateTime $emailing): void
    {
        $this->emailing = $emailing;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime|null $lastLogin
     */
    public function setLastLogin(?\DateTime $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }



}
