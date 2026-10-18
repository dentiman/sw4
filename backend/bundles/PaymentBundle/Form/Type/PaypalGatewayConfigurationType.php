<?php


namespace Dentiman\PaymentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

final class PaypalGatewayConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'sylius.form.gateway_configuration.paypal.username',
                'constraints' => [
                    new NotBlank([
                        'message' => 'sylius.gateway_config.paypal.username.not_blank',
                    ]),
                ],
            ])
            ->add('password', TextType::class, [
                'label' => 'sylius.form.gateway_configuration.paypal.password',
                'constraints' => [
                    new NotBlank([
                        'message' => 'sylius.gateway_config.paypal.password.not_blank',
                    ]),
                ],
            ])
            ->add('signature', TextType::class, [
                'label' => 'sylius.form.gateway_configuration.paypal.signature',
                'constraints' => [
                    new NotBlank([
                        'message' => 'sylius.gateway_config.paypal.signature.not_blank',
                    ]),
                ],
            ])
            ->add('sandbox', CheckboxType::class, [
                'label' => 'sylius.form.gateway_configuration.paypal.sandbox',
            ])
        ;
    }
}
