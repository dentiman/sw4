<?php

namespace App\Entity\FeedImport\Charts;

use App\Entity\Feed\Traits\TickerIdTrait;
use App\Entity\Feed\Traits\TickerTaskTrait;
use App\Model\Feed\TickerTaskInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * DailyCounter
 *
 * @ORM\Table(name="feed_charts_minute_counter")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class TickerTaskMinuteCharts implements TickerTaskInterface
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FeedImport\Charts\MinuteHistory", mappedBy="tickerTask")
     */
    private $minuteHistories;

    public function __construct()
    {
        $this->minuteHistories = new ArrayCollection();
    }

    use TickerIdTrait;
    use TickerTaskTrait;

    /**
     * @return Collection|MinuteHistory[]
     */
    public function getMinuteHistories(): Collection
    {
        return $this->minuteHistories;
    }

    public function addMinuteHistory(MinuteHistory $minuteHistory): self
    {
        if (!$this->minuteHistories->contains($minuteHistory)) {
            $this->minuteHistories[] = $minuteHistory;
            $minuteHistory->setTickerTask($this);
        }

        return $this;
    }

    public function removeMinuteHistory(MinuteHistory $minuteHistory): self
    {
        if ($this->minuteHistories->contains($minuteHistory)) {
            $this->minuteHistories->removeElement($minuteHistory);
            // set the owning side to null (unless already changed)
            if ($minuteHistory->getTickerTask() === $this) {
                $minuteHistory->setTickerTask(null);
            }
        }

        return $this;
    }

    public function barsCount()
    {
        return $this->minuteHistories->count();
    }
}

