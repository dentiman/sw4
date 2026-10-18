<?php

namespace App\Controller\Admin;

use App\Entity\FeedImport\Charts\DailyHistory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class DailyHistoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DailyHistory::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            'ticker',
            DateTimeField::new('time'),
            AssociationField::new('tickerTask')
        ];
    }
}
