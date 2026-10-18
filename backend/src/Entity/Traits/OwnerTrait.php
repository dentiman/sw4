<?php


namespace App\Entity\Traits;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\User;

trait OwnerTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="panelLayouts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): void
    {
        $this->owner = $owner;

    }

}
