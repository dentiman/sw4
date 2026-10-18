<?php

namespace App\Controller\Admin;

use App\Entity\FeedImport\Charts\TickerTaskMinuteCharts;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TickerTaskMinuteChartsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TickerTaskMinuteCharts::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
