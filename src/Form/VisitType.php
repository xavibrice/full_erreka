<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Visit;
use App\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $visit = $options['data'] ?? null;
        $isEdit = $visit && $visit->getId();

        if ($isEdit) {
            $date = 'js-datepicker-empty';
        } else {
            $date = 'js-datepicker';
        }

        $builder
            ->add('created', DatePickerType::class, [
                'required' => true,
                'label' => 'Fecha visita',
                'data' => new \DateTime(),
                'attr' => [
                    'class' => $date,
                ],
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Comentario',
            ])
            ->add('client', EntityType::class, [
                'label' => 'Cliente',
                'class' => Client::class,
                'placeholder' => 'Selecciona cliente'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Visit::class,
        ]);
    }
}
