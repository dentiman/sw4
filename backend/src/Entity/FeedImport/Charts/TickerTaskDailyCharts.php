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
 * @ORM\Table(name="feed_charts_daily_counter")
 * @ORM\Entity(repositoryClass="App\Repository\TickerTaskDailyChartRepository")
 * @ORM\HasLifecycleCallbacks
 */
class TickerTaskDailyCharts implements TickerTaskInterface
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FeedImport\Charts\DailyHistory", mappedBy="tickerTask")
     */
    private $dailyHistories;

    public function __construct()
    {
        $this->dailyHistories = new ArrayCollection();
    }

    use TickerIdTrait;
    use TickerTaskTrait;

    /**
     * @return Collection|DailyHistory[]
     */
    public function getDailyHistories(): Collection
    {
        return $this->dailyHistories;
    }

    public function addDailyHistory(DailyHistory $dailyHistory): self
    {
        if (!$this->dailyHistories->contains($dailyHistory)) {
            $this->dailyHistories[] = $dailyHistory;
            $dailyHistory->setTickerTask($this);
        }

        return $this;
    }

    public function removeDailyHistory(DailyHistory $dailyHistory): self
    {
        if ($this->dailyHistories->contains($dailyHistory)) {
            $this->dailyHistories->removeElement($dailyHistory);
            // set the owning side to null (unless already changed)
            if ($dailyHistory->getTickerTask() === $this) {
                $dailyHistory->setTickerTask(null);
            }
        }

        return $this;
    }

    public function barsCount()
    {
        return $this->dailyHistories->count();
    }
}

