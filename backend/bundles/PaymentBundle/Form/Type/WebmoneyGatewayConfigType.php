<?php

namespace Dentiman\PaymentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class WebmoneyGatewayConfigType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('LMI_PAYEE_PURSE', TextType::class, [
                'label' => 'Z Wallet',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Z wallet',
                    ]),
                ],
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'webmonay_gateway_config_type';
    }
}
