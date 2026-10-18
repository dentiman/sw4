<?php

namespace App\Entity\FeedImport\Charts;

use App\Entity\Feed\Traits\ChartBarTrait;
use App\Entity\Feed\Traits\TickerIdTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * DailyHistory
 *
 * @ORM\Table(name="feed_charts_daily_history")
 * @ORM\Entity()
 */
class DailyHistory
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FeedImport\Charts\TickerTaskDailyCharts", inversedBy="dailyHistories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tickerTask;

    use ChartBarTrait;

    public function getTickerTask(): ?TickerTaskDailyCharts
    {
        return $this->tickerTask;
    }

    public function setTickerTask(?TickerTaskDailyCharts $tickerTask): self
    {
        $this->tickerTask = $tickerTask;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

}

