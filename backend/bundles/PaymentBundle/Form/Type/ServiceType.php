<?php

namespace Dentiman\PaymentBundle\Form\Type;

use Dentiman\PaymentBundle\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class,['required' => false,'label' =>'app.entity.service.name'])
        ->add('isEnabled',null,[ 'required' => false, 'label' => 'app.entity.service.isEnabled'])
        ->add('code', TextType::class,['required' => false,'label' =>'app.entity.service.code'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
    * {@inheritdoc}
    */
    public function getBlockPrefix()
    {
        return 'dentiman_paymentbundle_service';
    }
}
