<?php

namespace App\Form;

use App\Entity\Code;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CodeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('discount', NumberType::class, [
                'label' => 'Réduction (%)',
                'scale' => 2,
            ])
            ->add('domainName', TextType::class, [
                'label' => 'Nom de domaine',
            ])
            ->add('validFrom', DateType::class, [
                'label' => 'Valide à partir de',
            ])
            ->add('validUntil', DateType::class, [
                'label' => 'Valide jusqu\'à',
            ])
        ;

        if ($options['is_vip_user']) {
            $builder->add('isVipOnly', CheckboxType::class, [
                'label' => 'Code VIP',
                'required' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Code::class,
            'is_vip_user' => false,
        ]);
    }
}
