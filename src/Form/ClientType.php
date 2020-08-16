<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\Client;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => 'Nombre completo',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo',
            ])
            ->add('mobile', TelType::class, [
                'label' => 'Móvil',
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Teléfono',
                'required' => false,
            ])
            ->add('propertyType', EntityType::class, [
                'label' => 'Tipo Propiedad',
                'class' => \App\Entity\PropertyType::class,
                'placeholder' => 'Seleciona tipo propiedad',
            ])
        ;

        $builder
            ->get('propertyType')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    $form = $event->getForm();
                    $form->getParent()->add('nationality');
                    dd('ok');
                }
            );

        $builder
            ->addEventListener(
                FormEvents::POST_SET_DATA,
                function (FormEvent $event)
                {
                    $data = $event->getData();
                    $form = $event->getForm();

                    $client = $data->getPropertyType();

                    if ($client || null != $client) {
                        if ($client->getName() === 'Vivienda') {
                            $form->add('nationality');
                            dd('na');
                        }
                    }
                }
            );



        /*
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            $client = $data->getPropertyType();

            if ($client || null != $client) {
                if ($client->getName() === 'Vivienda') {
                    $form->add('nationality');
                }
            }
        });

        $builder->get('propertyType')->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();
                if ($data === 1 || $data === 'Vivienda') {
                    $form->getParent()->add('nationality');
                }
            }
        );
        */
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
