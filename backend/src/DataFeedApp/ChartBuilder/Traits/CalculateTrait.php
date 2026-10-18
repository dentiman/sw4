<?php


namespace App\DataFeedApp\ChartBuilder\Traits;


use App\DataFeedApp\Bar\Model\BarData;
use App\Entity\Chart\ChartLayout;

trait CalculateTrait
{
    /**
     * @var \Intervention\Image\Image
     */
    protected $img;

    /**
     * @var BarData
     */
    protected $feed;
    /**
     * @var ChartLayout
     */
    protected $chartLayout;




}
