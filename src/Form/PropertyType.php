<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\Owner;
use App\Entity\Property;
use App\Form\EventListener\AddStreetFieldListener;
use App\Form\EventListener\AddZoneFieldListener;
use App\Form\Type\DatePickerType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $property = $options['data'] ?? null;
        $isEdit = $property && $property->getId();

        if ($isEdit) {
            $date = 'js-datepicker-empty';
        } else {
            $date = 'js-datepicker';
        }

        $builder
            ->add('created', DatePickerType::class, [
                'required' => true,
                'label' => 'Fecha creación',
                'data' => new \DateTime(),
                'attr' => [
                    'class' => $date,
                ],
            ])
            ->add('reason', ChoiceType::class, [
                'label' => 'Motivo',
                'choices' => $this->getChoices(),
                'placeholder' => 'Selecciona motivo'
            ])
            ->addEventSubscriber(new AddZoneFieldListener())
            ->addEventSubscriber(new AddStreetFieldListener())
            ->add('propertyType', EntityType::class, [
                'label' => 'Tipo propiedad',
                'class' => \App\Entity\PropertyType::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('pt')
                        ->orderBy('pt.name', 'ASC');
                },
                'placeholder' => 'Selecciona propiedad'
            ])
            ->add('agent', EntityType::class, [
                'label' => 'Agente',
                'class' => Agent::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.full_name', 'ASC');
                },
                'placeholder' => 'Selecciona agente',
            ])
            ->add('owner', EntityType::class, [
                'label' => 'Propietario',
                'class' => Owner::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.full_name', 'ASC');
                },
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
