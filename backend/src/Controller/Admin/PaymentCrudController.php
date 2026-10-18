<?php

namespace App\Controller\Admin;

use Dentiman\PaymentBundle\Entity\Payment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class PaymentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Payment::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            'status',
            AssociationField::new('gateway'),
            DateTimeField::new('createdAt'),
            AssociationField::new('relatedOrder'),
        ];
    }
}
