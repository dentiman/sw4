<?php


namespace App\Form\Filter;


use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\FilterType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DailyHistoryFilterType extends FilterType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => [
                'Group' => '1',
                'This month' => 'this_month',
                // ...
            ],
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function filter(QueryBuilder $queryBuilder, FormInterface $form, array $metadata)
    {
        if ('1' === $form->getData()) {
            // use $metadata['property'] to make this query generic
            $queryBuilder
                ->groupBy('entity.ticker')

            ;
        }

        // ...
    }
}
