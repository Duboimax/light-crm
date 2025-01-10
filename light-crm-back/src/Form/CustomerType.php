<?php

namespace App\Form;

use App\Entity\Customer;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'The name cannot be blank'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => false
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 10,
                        'maxMessage' => 'The phone number cannot exceed 10 characters.'
                    ]),
                    new Regex([
                        'pattern' => '/^\d{0,10}$/',
                        'message' => 'The phone ,umber must contain only digits'
                    ])
                ]
            ])
            ->add('address', TextType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
