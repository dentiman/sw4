<?php

namespace App\Controller\Admin;

use App\Entity\FeedImport\Basic\FeedYahooQuote;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class FeedYahooQuoteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FeedYahooQuote::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('symbol'),
            'marketCap',
            'regularMarketChange',
            'regularMarketChangePercent',
            'regularMarketVolume',
        ];
    }
}
