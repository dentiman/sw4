<?php

namespace App\Controller\Admin;

use Dentiman\PaymentBundle\Entity\PaymentToken;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PaymentTokenCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PaymentToken::class;
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
