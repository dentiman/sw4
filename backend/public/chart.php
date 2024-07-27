<?php
//error_reporting(E_ALL);
//error_reporting(-1);
//ini_set('error_reporting', E_ALL);

use Symfony\Component\HttpFoundation\Request;
use App\DataFeedApp\ChartBuilder\ChartBuilder;
use App\Entity\Chart\ChartLayout;
use App\DataFeedApp\Bar\Read\BarReader;
use App\DataFeedApp\Bar\Read\Sources\FileBarSource;
use App\DataFeedApp\Bar\Read\Sources\IqFeedBarSource;
use App\DataFeedApp\Bar\Storage\DailyBarStorage;

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';


//parse_str($_SERVER['QUERY_STRING'],$settings);


$chartLayout = new ChartLayout();

$chartBuilder = new ChartBuilder($chartLayout);

$barReader = new BarReader(...[new FileBarSource()]);

$barData =  $barReader->getBarsForChart($chartLayout);

//print_r($r->lines);
$chartBuilder->output();
