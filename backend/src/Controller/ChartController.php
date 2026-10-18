<?php

namespace App\Controller;

use App\DataFeedApp\Bar\Read\BarReader;
use App\DataFeedApp\Bar\Read\Exception\NoDataException;
use App\DataFeedApp\Bar\Read\Sources\CacheBarSource;
use App\DataFeedApp\Bar\Read\Sources\FileBarSource;
use App\DataFeedApp\Bar\Read\Sources\IqFeedBarSource;
use App\DataFeedApp\Bar\TimeFrame\MinuteBarTimeFrameInterface;
use App\DataFeedApp\Bar\TimeFrame\TimeFrameFactory;
use App\DataFeedApp\ChartBuilder\ChartBuilder;
use App\Entity\Chart\ChartLayout;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ChartController extends AbstractController
{
    const DARK_PRESETS = [
        'width' => 700,
        'height' => 400,
        'bgColor' => '#171616',
        'gridColor' => '#333232',
        'preMarketColor' => 'rgba(180,180,180,.1)',
        'colorUp' => '#249E92',
        'colorDown' => '#E34F4C',
        'outlineColorUp' => '#249E92',
        'outlineColorDown' => '#E34F4C',
        'volumeColorUp' => 'rgba(36, 158, 146,.5)',
        'volumeColorDown' => 'rgba(227, 79, 76, .5)',
    ];

    /**
     * @Route("/api/chart", name="chart")
     * @param Request $request
     * @param LoggerInterface $appLogger
     * @return Response
     */
    public function index(Request $request, LoggerInterface $appLogger): Response
    {
        $chartLayout = $this->getChartLayout($request);
        $chartBuilder = new ChartBuilder($chartLayout, $appLogger);
        try {
            $this->tryBuildChart($chartBuilder);
        } catch (NoDataException $exception) {
            $chartBuilder->setError($exception->getMessage());
        } catch (Exception $exception) {
            $chartBuilder->setServerError();
        }
        return $this->renderChart($chartBuilder);
    }

    /**
     * @Route("/api/chart/prev", name="chart_prev")
     * @param Request $request
     * @param LoggerInterface $appLogger
     * @return Response
     */
    public function deprecatedSystem(Request $request, LoggerInterface $appLogger): Response
    {
        $chartLayout = $this->getPrevSystemChartLayout($request);
        $chartBuilder = new ChartBuilder($chartLayout, $appLogger);
        try {
            $this->tryBuildChart($chartBuilder);
        } catch (NoDataException $exception) {
            $chartBuilder->setError($exception->getMessage());
        } catch (Exception $exception) {
            $chartBuilder->setServerError();
        }

        return $this->renderChart($chartBuilder);
    }


    /**
     * @Route("/api/chart/debug", name="chart_debug")
     * @param Request $request
     * @param LoggerInterface $appLogger
     * @return Response
     */
    public function debug(Request $request, LoggerInterface $appLogger)
    {
        $chartLayout = $this->getChartLayout($request);
        $chartBuilder = new ChartBuilder($chartLayout, $appLogger);
        $this->tryBuildChart($chartBuilder);
        return $this->render("chart/index.html.twig", [
            'chartBuilder' => $chartBuilder,
            'barData' => $chartBuilder->getBarData()
        ]);
    }


    /**
     * @Route("/api/chart/prev/debug", name="chart_prev_debug")
     * @param Request $request
     * @param LoggerInterface $appLogger
     * @return Response
     * @throws Exception
     */
    public function prevDebug(Request $request, LoggerInterface $appLogger)
    {
        $chartLayout = $this->getPrevSystemChartLayout($request);
        $chartBuilder = new ChartBuilder($chartLayout, $appLogger);
        $this->tryBuildChart($chartBuilder);
        return $this->render("chart/index.html.twig", [
            'chartBuilder' => $chartBuilder,
            'barData' => $chartBuilder->getBarData()
        ]);
    }

    /** For big chart
     * @Route("/api/chart/bars/{ticker}/{tf}", name="vue_chart_data")
     */
    public function barHistory($ticker, $tf)
    {
        $barReader = new BarReader(...[
//            new FileBarSource(),
//            new CacheBarSource(),
            new IqFeedBarSource(),
            new IqFeedBarSource()
        ]);

        $timeFrame = TimeFrameFactory::getTimeFrame(strtolower($tf));

        $bars = $barReader->calculateTimeAndGetBars($ticker,$timeFrame,true);

        $data = [];
        /** @var \DateTime $dateTime */
        foreach ($bars->time as $index => $dateTime) {
            $data[] = [
                $dateTime->getTimestamp()*1000,
                $bars->open[$index],
                $bars->high[$index],
                $bars->low[$index],
                $bars->close[$index],
                $bars->volume[$index],
            ];
        }
        return new JsonResponse(array_reverse($data));
    }

    protected function tryBuildChart(ChartBuilder $chartBuilder)
    {
        $barReader = new BarReader(...[
            new FileBarSource(),
            new CacheBarSource(),
            new IqFeedBarSource(),
            new IqFeedBarSource()
        ]);
        $barData = $barReader->getBarsForChart($chartBuilder->getChartLayout());
        $chartBuilder->applyData($barData);

        if($chartBuilder->getChartLayout()->getSpyOn() === true) {

            $barReader = new BarReader(...[
                new CacheBarSource(),
                new IqFeedBarSource(),
                new IqFeedBarSource()
            ]);

            $compareChartLayout = clone $chartBuilder->getChartLayout();
            $compareChartLayout->setupAsCompareLayout('SPY');
            $chartBuilder->drawCompareBars($compareChartLayout, $barReader->getBarsForChart($compareChartLayout));
        }

        $chartBuilder->addChartData($chartBuilder->getChartLayout());
    }


    protected function getChartLayout(Request $request): ChartLayout
    {
        $serializer = new Serializer([new ObjectNormalizer(), new ArrayDenormalizer()]);
        $data = $request->query->all();

//        try {
            /** @var ChartLayout $chartLayout */
            $chartLayout = $serializer->denormalize($data, ChartLayout::class);
            $chartLayout->setPreMarket($this->serializeBoolean($request->get('preMarket')));
            $chartLayout->setSpyOn($this->serializeBoolean($request->get('spyOn')));
            $chartLayout->setSma1($this->serializeInt($request->get('sma1')));
            $chartLayout->setSma2($this->serializeInt($request->get('sma2')));
            $chartLayout->setSma3($this->serializeInt($request->get('sma3')));
            $chartLayout->setEma1($this->serializeInt($request->get('ema1')));
            $chartLayout->setEma2($this->serializeInt($request->get('ema2')));
            $chartLayout->setEma3($this->serializeInt($request->get('ema3')));
            $chartLayout->setLinesOpen($this->serializeBoolean($request->get('linesOpen')));
            $chartLayout->setLinesClose($this->serializeBoolean($request->get('linesClose')));
            $chartLayout->setLinesHigh($this->serializeBoolean($request->get('linesHigh')));
            $chartLayout->setLinesLow($this->serializeBoolean($request->get('linesLow')));
            $chartLayout->setLineLastPrice($this->serializeBoolean($request->get('lineLastPrice')));
            $chartLayout->setSeparateVolumeArea($this->serializeBoolean($request->get('separateVolumeArea')));

            if ($request->get('lstbr')) {
                $chartLayout->setLastBarData($request->get('lstbr'));
            }

//        } catch (Exception $exception) {
//            $chartLayout = new ChartLayout();
//        }

        return $chartLayout;
    }

    protected function getPrevSystemChartLayout(Request $request): ChartLayout
    {
        $serializer = new Serializer([new ObjectNormalizer(), new ArrayDenormalizer()]);

        /** @var ChartLayout $chartLayout */
        $chartLayout = $serializer->denormalize($this::DARK_PRESETS, ChartLayout::class);
        $this->setupLayoutPrevFormat($request, $chartLayout);
        return $chartLayout;
    }


    protected function setupLayoutPrevFormat(Request $request, ChartLayout $chartLayout)
    {
        if ($request->get('t')) {
            $chartLayout->setTicker($request->get('t'));
        }

        if ($request->get('tf')) {
            $chartLayout->setTimeFrame($request->get('tf'));
        }

        if ($request->get('w')) {
            $chartLayout->setWidth($request->get('w'));
        }

        if ($request->get('h')) {
            $chartLayout->setHeight($request->get('h'));
        }

        if ($request->get('vol_wdt')) {
            $chartLayout->setVolumeAreaHeight($request->get('vol_wdt'));
        }

        if ($request->get('bgcolor')) {
            $chartLayout->setBgColor('rgb(' . $request->get('bgcolor') . ')');
        }

        if ($request->get('setka')) {
            $chartLayout->setGridColor('rgb(' . $request->get('setka') . ')');
        }

        $chartLayout->setSeparateVolumeArea($request->get('voll') == '1' ? true : false);

        if ($request->get('type')) {
            $chartLayout->setBarType($request->get('type'));
        }
        if ($request->get('barw')) {
            $chartLayout->setBarWidth($request->get('barw'));
        }
        if ($request->get('kontur')) {

            $kontur = $request->get('kontur');
            if ($kontur == '15') {
                $kontur = 2;
            }
            $chartLayout->setOutline($kontur);
        }
        if ($request->get('thick')) {
            $thick = $request->get('thick') * 1;
            if ($thick > 1) {
                $thick = $thick + 1;
            }
            $chartLayout->setBarThick($thick);
        }
        if ($request->get('colorup')) {
            $chartLayout->setColorUp('rgb(' . $request->get('colorup') . ')');
        }
        if ($request->get('colordown')) {
            $chartLayout->setColorDown('rgb(' . $request->get('colordown') . ')');
        }
        if ($request->get('fcolorup')) {
            $chartLayout->setOutlineColorUp('rgb(' . $request->get('fcolorup') . ')');
        }
        if ($request->get('fcolord')) {
            $chartLayout->setOutlineColorDown('rgb(' . $request->get('fcolord') . ')');
        }
        if ($request->get('vol_c_u')) {
            $chartLayout->setVolumeColorUp('rgb(' . $request->get('vol_c_u') . ')');
        }
        if ($request->get('vol_c_d')) {
            $chartLayout->setVolumeColorDown('rgb(' . $request->get('vol_c_d') . ')');
        }
        //lines_op=1&lines_hi=1&lines_lo=1&lines_cl=1
        if ($request->get('lines_d')) {
            $chartLayout->setLinesDays($request->get('lines_d') * 1);
        }
        if ($request->get('lines_op')) {
            $chartLayout->setLinesOpen(true);
        }
        if ($request->get('lines_hi')) {
            $chartLayout->setLinesHigh(true);
        }
        if ($request->get('lines_lo')) {
            $chartLayout->setLinesLow(true);
        }
        if ($request->get('lines_cl')) {
            $chartLayout->setLinesClose(true);
        }
        if ($request->get('lines_last')) {
            $chartLayout->setLineLastPrice(true);
        }
        if ($request->get('prem') && $request->get('prem') == '1') {
            $chartLayout->setPreMarket(true);
        }

        //sma2=10&sma2c=110,49,49
        if ($request->get('sma1')) {
            $chartLayout->setSma1($request->get('sma1') * 1);
        }
        if ($request->get('sma1c')) {
            $chartLayout->setSma1Color('rgb(' . $request->get('sma1c') . ')');
        }
        if ($request->get('sma2')) {
            $chartLayout->setSma2($request->get('sma2') * 1);
        }
        if ($request->get('sma2c')) {
            $chartLayout->setSma2Color('rgb(' . $request->get('sma2c') . ')');
        }
        if ($request->get('sma3')) {
            $chartLayout->setSma2($request->get('sma3') * 1);
        }
        if ($request->get('sma3c')) {
            $chartLayout->setSma2Color('rgb(' . $request->get('sma3c') . ')');
        }

        if ($request->get('ema1')) {
            $chartLayout->setSma2($request->get('ema2') * 1);
        }
        if ($request->get('ema1c')) {
            $chartLayout->setSma2Color('rgb(' . $request->get('ema1c') . ')');
        }
        if ($request->get('ema2')) {
            $chartLayout->setSma2($request->get('ema2') * 1);
        }
        if ($request->get('ema2c')) {
            $chartLayout->setSma2Color('rgb(' . $request->get('ema2c') . ')');
        }
        if ($request->get('ema3')) {
            $chartLayout->setSma2($request->get('ema3') * 1);
        }
        if ($request->get('ema3c')) {
            $chartLayout->setSma2Color('rgb(' . $request->get('ema3c') . ')');
        }

        if ($request->get('lstbr')) {
            $chartLayout->setLastBarData($request->get('lstbr'));
        }

        if ($request->get('spy_on')) {
            $chartLayout->setSpyOn(true);
        }
    }

    protected function renderChart(ChartBuilder $chartBuilder)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'image/png');
        $response->setContent($chartBuilder->render());
        return $response;
    }


    protected function serializeBoolean($value)
    {
        if($value === '1')  return  true;
        if($value === 'true')  return  true;
        return  false;
    }

    protected function serializeInt($value)
    {
        if($value === '0')  return  0;
        if($value === 'null')  return  0;
        return $value*1;
    }
}
