<?php

namespace App\Entity\Presets;

use App\Entity\OwnerInterface;
use App\Entity\User;
use App\Entity\Chart\ChartLayout;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class TableLayout implements OwnerInterface
{
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
     * @ORM\Column(type="array", nullable=true)
     */
    private $fields = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="panelLayouts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

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

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     */
    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }



    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): void
    {
        $this->owner = $owner;

    }
}
