<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\Owner;
use App\Entity\Property;
use App\Entity\Street;
use App\Form\EventListener\AddStreetFieldListener;
use App\Form\EventListener\AddZoneFieldListener;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('created', DateType::class, [
                'label' => 'Fecha creación',
                'widget' => 'single_text'
            ])
            ->add('reason', ChoiceType::class, [
                'label' => 'Motivo',
                'choices' => $this->getChoices(),
                'placeholder' => 'Selecciona motivo'
            ])
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
            ->addEventSubscriber(new AddZoneFieldListener())
            ->addEventSubscriber(new AddStreetFieldListener())
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
            ->add('photos', FileType::class, [
                'label' => 'Fotos',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'attr' => [
                    'accept' => 'image/*',
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }

    private function getChoices()
    {
        $choices = Property::SELL_OR_RENT;
        $output = [];
        foreach ($choices as $k => $v) {
            $output[$v] = $k;
        }

        return $output;
    }
}
