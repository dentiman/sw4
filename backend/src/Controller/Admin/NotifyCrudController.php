<?php

namespace App\Controller\Admin;

use Dentiman\PaymentBundle\Entity\Notify;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class NotifyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Notify::class;
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
