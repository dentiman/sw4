<?php

namespace App\Entity\FeedImport\Basic;

use App\Entity\Feed\Traits\MainCalculatedDataTrait;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Feed\Traits\MainTechTrait;
use App\Entity\Feed\Traits\MainTmpTrait;
use App\Entity\Feed\Traits\MainEarningsTrait;
use App\Entity\Feed\Traits\MainLevel1Trait;
use App\Entity\Feed\Traits\MainMinutePrevTrait;
use App\Entity\Feed\Traits\MainPremarketTrait;
use App\Entity\Feed\Traits\MainTickerTrait;
use App\Entity\Feed\Traits\MainWeekTrait;
use App\Entity\Feed\Traits\TickerIdTrait;
use phpDocumentor\Reflection\Types\This;


/**
 * @ORM\Table(name="feed_basic_calulated_grid")
 * @ORM\Entity()
 */
class FeedBasicCalculatedGrid {

    use TickerIdTrait;
    use MainTickerTrait;
    use MainTechTrait;
    use MainTmpTrait;
    use MainLevel1Trait;
    use MainEarningsTrait;
    use MainPremarketTrait;
    use MainCalculatedDataTrait;

    public function calculate(): void
    {
        $this->calculateRange();
        $this->calculateRelativeVolume();
        $this->calculateGep();
        $this->calculateChpo();
        $this->calculateCho();
        $this->calculateAtrp();
        $this->calculateNewHigh();
        $this->calculateNewLow();
        $this->calculateLevel();
        $this->calculateSpread();
    }

    protected function calculateSpread(): void
    {
        if($this->bid > 0 && $this->ask > 0) {
            $this->spread = $this->ask - $this->bid;
        }
    }


    protected function calculateRange(): void
    {
        if($this->hi > 0 && $this->lo > 0) {
            $this->priceRange = $this->hi - $this->lo;
        }
    }

    protected function calculateRelativeVolume(): void
    {
        if ($this->averageDailyVolume3Month > 0 && $this->vol > 0) {
            $this->relativeVolume = ceil($this->vol/$this->averageDailyVolume3Month);
        }
    }

    protected function calculateGep()
    {

        if($this->op > 0 && $this->ch && $this->price - $this->ch != 0 ) {
            $this->gep = round($this->op / ($this->price - $this->ch)*100-100,2);
        }
    }


    protected function calculateChpo()
    {
        if($this->op > 0 && $this->price > 0) {
            $this->chpo = round( $this->price / $this->op * 100-100,2);
        }
    }

    protected function calculateCho()
    {
        if($this->op > 0 && $this->price > 0) {
            $this->cho =  $this->price - $this->op ;
        }
    }

    protected function calculateAtrp()
    {
        if($this->atr > 0 && $this->hi > 0 && $this->lo > 0) {
            $this->atrp =  round(($this->hi - $this->lo)/$this->atr,2) ;
        }
    }

    protected function calculateNewHigh()
    {
        if($this->price > 0 && $this->hi > 0) {
            $this->newHigh =  $this->hi - $this->price ;
        }
    }

    protected function calculateNewLow()
    {
        if($this->price > 0 && $this->lo > 0) {
            $this->newLow =  $this->price - $this->lo ;
        }
    }


    protected function calculateLevel()
    {
        if($this->price > 0 ) {
           $this->level = fmod( round($this->price,2) ,1)*100;
         //  $this->level =   floor(fmod( $this->price ,1)*100) ;
        }
    }
}
