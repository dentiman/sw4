<?php

namespace App\Entity\FeedImport\Charts;

use App\Entity\Feed\Traits\ChartBarTrait;
use App\Entity\Feed\Traits\TickerIdTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * DailyHistory
 *
 * @ORM\Table(name="feed_charts_minute_history")
 * @ORM\Entity()
 */
class MinuteHistory
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FeedImport\Charts\TickerTaskMinuteCharts", inversedBy="minuteHistories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tickerTask;

    use ChartBarTrait;

    public function getTickerTask(): ?TickerTaskMinuteCharts
    {
        return $this->tickerTask;
    }

    public function setTickerTask(?TickerTaskMinuteCharts $tickerTask): self
    {
        $this->tickerTask = $tickerTask;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

}

