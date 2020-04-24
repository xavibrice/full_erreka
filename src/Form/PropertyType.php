<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\Owner;
use App\Entity\Property;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('agent', EntityType::class, [
                'label' => 'Agente',
                'class' => Agent::class,
                'placeholder' => 'Selecciona agente',
            ])
            ->add('owner', EntityType::class, [
                'label' => 'Propietario',
                'class' => Owner::class,
                'placeholder' => 'Selecciona propietario',
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Precio',
            ])
            ->add('portal', TextType::class, [
                'label' => 'Portal',
            ])
            ->add('floor', TextType::class, [
                'label' => 'Piso',
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Comentario',
            ])
            ->add('created', DateType::class, [
                'label' => 'Fecha creación'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
