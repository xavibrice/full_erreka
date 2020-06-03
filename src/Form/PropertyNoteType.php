<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\PropertyNote;
use App\Form\Type\DatePickerType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyNoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $propertyNote = $options['data'] ?? null;
        $isEdit = $propertyNote && $propertyNote->getId();

        if ($isEdit) {
            $date = 'js-datepicker-empty';
        } else {
            $date = 'js-datepicker';
        }

        $builder
            ->add('created', DatePickerType::class, [
                'required' => true,
                'label' => 'Fecha nota',
                'data' => new \DateTime(),
                'attr' => [
                    'class' => $date,
                ],
            ])
            ->add('client', EntityType::class, [
                'label' => 'Clientes',
                'class' => Client::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.full_name', 'ASC');
                },
                'placeholder' => 'Selecciona cliente'
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Comentario',
            ])
            ->add('nextCall', DatePickerType::class, [
                'required' => true,
                'label' => 'Próxima llamada',
                'attr' => [
                    'class' => 'js-datepicker-empty',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PropertyNote::class,
        ]);
    }
}
