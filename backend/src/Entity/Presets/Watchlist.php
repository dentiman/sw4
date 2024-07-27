<?php

namespace App\Entity\Presets;

use App\Entity\OwnerInterface;
use App\Entity\Traits\OwnerTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WatchlistRepository")
 */
class Watchlist implements OwnerInterface
{
    use OwnerTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="date")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $continueAt;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $green = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $red = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $blue = [];

    public function __construct()
    {
        $this->createdAt = new \DateTime('now', new \DateTimeZone('America/New_York'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getGreen(): ?array
    {
        return $this->green;
    }

    public function setGreen(array $green): self
    {
        $this->green = $green;

        return $this;
    }

    public function getRed(): ?array
    {
        return $this->red;
    }

    public function setRed(?array $red): self
    {
        $this->red = $red;

        return $this;
    }

    public function getBlue(): ?array
    {
        return $this->blue;
    }

    public function setBlue(?array $blue): self
    {
        $this->blue = $blue;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContinueAt(): ?\DateTimeInterface
    {
        return $this->continueAt;
    }

    /**
     * @param mixed $continueAt
     */
    public function setContinueAt( ?\DateTimeInterface $continueAt): void
    {
        $this->continueAt = $continueAt;
    }

}
