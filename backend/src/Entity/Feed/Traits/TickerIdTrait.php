<?php

namespace App\Entity\Feed\Traits;

use Doctrine\ORM\Mapping as ORM;




trait TickerIdTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=10, nullable=true)
     * @ORM\Id
     *
     */
    private $id;


    /**
     * Get id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param string $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }



}

