<?php

namespace App\Controller\Admin;

use App\Entity\FeedImport\Premarket\FeedPremarketIqfeed;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class FeedPremarketIqfeedCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FeedPremarketIqfeed::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            'pvol',
            'pchp',
            'ptcount',
            DateTimeField::new('pttime'),
        ];
    }
}
