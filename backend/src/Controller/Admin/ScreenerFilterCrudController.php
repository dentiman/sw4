<?php

namespace App\Controller\Admin;

use App\Entity\Presets\ScreenerFilter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ScreenerFilterCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ScreenerFilter::class;
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
