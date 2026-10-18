<?php
/**
 * Created by PhpStorm.
 * User: den
 * Date: 24.12.2018
 * Time: 18:38
 */

namespace App\Entity\Feed;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Feed\Traits\MainFinvizDataTrait;
use App\Entity\Feed\Traits\MainEarningsTrait;
use App\Entity\Feed\Traits\MainLevel1Trait;
use App\Entity\Feed\Traits\MainMinutePrevTrait;
use App\Entity\Feed\Traits\MainPremarketTrait;
use App\Entity\Feed\Traits\MainTickerTrait;
use App\Entity\Feed\Traits\MainWeekTrait;
use App\Entity\Feed\Traits\TickerIdTrait;


/**
 * MainBasicData
 * @ORM\Entity
 * @ORM\Table(name="feed_main_grid_delay")
 *
 */
class MainGridDelay
{
    use TickerIdTrait;

    use MainTickerTrait;

    use MainFinvizDataTrait;

    use MainLevel1Trait;

    use MainEarningsTrait;

    use MainPremarketTrait;

    use MainMinutePrevTrait;

    use MainWeekTrait;

}
