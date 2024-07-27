<?php

namespace App\Entity\Presets;

use App\Entity\OwnerInterface;
use App\Entity\User;
use App\Entity\Chart\ChartLayout;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\OwnerTrait;
/**
 * @ORM\Entity(repositoryClass="App\Repository\Chart\PanelLayoutRepository")
 */
class PanelLayout implements OwnerInterface
{
    use OwnerTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Chart\ChartLayout", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $chartOne;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Chart\ChartLayout", cascade={"persist", "remove"})
     */
    private $chartTwo;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Chart\ChartLayout", cascade={"persist", "remove"})
     */
    private $chartThree;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $displayFeedFields = [];



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChartOne(): ?ChartLayout
    {
        return $this->chartOne;
    }

    public function setChartOne(ChartLayout $chartOne): self
    {
        $this->chartOne = $chartOne;

        return $this;
    }

    public function getChartTwo(): ?ChartLayout
    {
        return $this->chartTwo;
    }

    public function setChartTwo(?ChartLayout $chartTwo): self
    {
        $this->chartTwo = $chartTwo;

        return $this;
    }

    public function getChartThree(): ?ChartLayout
    {
        return $this->chartThree;
    }

    public function setChartThree(?ChartLayout $chartThree): self
    {
        $this->chartThree = $chartThree;

        return $this;
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

    public function getDisplayFeedFields(): ?array
    {
        return $this->displayFeedFields;
    }

    public function setDisplayFeedFields(?array $displayFeedFields): self
    {
        $this->displayFeedFields = $displayFeedFields;

        return $this;
    }


}
