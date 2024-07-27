<?php

namespace App\Controller\Admin;

use App\Entity\Feed\MainEarnings;
use App\Entity\Feed\MainGrid;
use App\Entity\Feed\MainLevel1;
use App\Entity\Feed\MainMinutePrev;
use App\Entity\Feed\MainPremarket;
use App\Entity\Feed\MainTech;
use App\Entity\Feed\MainTickers;
use App\Entity\Feed\MainTmp;
use App\Entity\Feed\MainWeek;
use App\Entity\FeedImport\Basic\FeedBasicEarnings;
use App\Entity\FeedImport\Basic\FeedBasicTickers;
use App\Entity\FeedImport\Basic\FeedYahooQuote;
use App\Entity\FeedImport\Charts\DailyHistory;
use App\Entity\FeedImport\Charts\IntradayCounter;
use App\Entity\FeedImport\Charts\TickerTaskDailyCharts;
use App\Entity\FeedImport\Level1\FeedLevel1Sources;
use App\Entity\FeedImport\Level1\FeedLevel1TV;
use App\Entity\FeedImport\Premarket\FeedPremarketIqfeed;
use App\Entity\User;
use Dentiman\PaymentBundle\Entity\GatewayConfig;
use Dentiman\PaymentBundle\Entity\Notify;
use Dentiman\PaymentBundle\Entity\Order;
use Dentiman\PaymentBundle\Entity\Payment;
use Dentiman\PaymentBundle\Entity\Service;
use Dentiman\PaymentBundle\Entity\ServiceVariant;
use Dentiman\ScheduleBundle\Entity\Schedule;
use Dentiman\ScheduleBundle\Entity\ScheduleLog;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("api/admin", name="admin")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Backend')
            ->disableUrlSignatures()
            ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linkToCrud('Schedule', null, Schedule::class);
        yield MenuItem::linkToCrud('ScheduleLog', null, ScheduleLog::class);


        yield MenuItem::subMenu('Billing', null)->setSubItems([
            MenuItem::linkToCrud('User', null, User::class),
            MenuItem::linkToCrud('Order', null, Order::class),
            MenuItem::linkToCrud('Payment', null, Payment::class),
            MenuItem::linkToCrud('Notify', null, Notify::class),
            MenuItem::linkToCrud('ServiceVariant', null, ServiceVariant::class),
            MenuItem::linkToCrud('Service', null, Service::class),
            MenuItem::linkToCrud('GatewayConfig', null, GatewayConfig::class),
        ]);

        yield MenuItem::subMenu('Charts', null)->setSubItems([
            MenuItem::linkToCrud('DailyHistory', null, DailyHistory::class),
            MenuItem::linkToCrud('IntradayCounter', null, IntradayCounter::class),
            MenuItem::linkToCrud('TickerTaskDailyCharts', null, TickerTaskDailyCharts::class),
        ]);


        yield MenuItem::subMenu('Grids', null)->setSubItems([

            MenuItem::linkToCrud('MainGrid', null, MainGrid::class),
            MenuItem::section('Tikers'),
            MenuItem::linkToCrud('MainTickers', null, MainTickers::class),
            MenuItem::linkToCrud('FeedBasicTickers', null, FeedBasicTickers::class),
            MenuItem::section('MainLevel1'),
            MenuItem::linkToCrud('MainLevel1', null, MainLevel1::class),
            MenuItem::linkToCrud('FeedLevel1Sources', null, FeedLevel1Sources::class),
            MenuItem::linkToCrud('FeedLevel1TV', null, FeedLevel1TV::class),
            MenuItem::section('MainEarnings'),
            MenuItem::linkToCrud('MainEarnings', null, MainEarnings::class),
            MenuItem::linkToCrud('FeedBasicEarnings', null, FeedBasicEarnings::class),
            MenuItem::section('MainPremarket'),
            MenuItem::linkToCrud('MainPremarket', null, MainPremarket::class),
            MenuItem::linkToCrud('FeedPremarketIqfeed', null, FeedPremarketIqfeed::class),
            MenuItem::section('Other Feed'),
            MenuItem::linkToCrud('MainTech', null, MainTech::class),
            MenuItem::linkToCrud('MainTmp', null, MainTmp::class),
            MenuItem::linkToCrud('MainWeek', null, MainWeek::class),
            MenuItem::linkToCrud('MainMinutePrev', null, MainMinutePrev::class),
            MenuItem::linkToCrud('FeedYahooQuote', null, FeedYahooQuote::class),


        ]);


    }
}
