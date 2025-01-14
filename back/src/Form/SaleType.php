<?php

namespace App\Form;

use App\Entity\Sale;
use App\Entity\Customer;
use App\Entity\Service;
use App\Enum\SaleStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'choice_label' => fn(Customer $customer) => $customer->getFirstname() . ' ' . $customer->getLastname(),
                'required' => true,
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'name',
                'required' => true,
            ])
            ->add('saleDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'empty_data' => (new \DateTime())->format('Y-m-d'),
            ])
            ->add('total', MoneyType::class, [
                'currency' => 'EUR',
                'required' => true,
            ])
            ->add('discount', MoneyType::class, [
                'currency' => 'EUR',
                'required' => false,
            ])
            ->add('comment', TextareaType::class, [
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'choices' => array_combine(SaleStatus::getValues(), SaleStatus::getValues()),
                'required' => false,
                'empty_data' => SaleStatus::PENDING->value,
            ])
            ->add('paymentMethod', TextType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sale::class,
            'csrf_protection' => false, // Désactivé pour les API REST
        ]);
    }
}
