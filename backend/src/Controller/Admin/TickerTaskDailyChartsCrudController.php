<?php

namespace App\Controller\Admin;

use App\Entity\FeedImport\Charts\TickerTaskDailyCharts;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class TickerTaskDailyChartsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TickerTaskDailyCharts::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            'done',
            'success',
            'message',
            DateTimeField::new('updatedAt'),
        ];
    }
}
