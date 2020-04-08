<?php

namespace App\Form;

use App\Entity\Agent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Vich\UploaderBundle\Form\Type\VichFileType;

class AgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $agent = $options['data'] ?? null;
        $isEdit = $agent && $agent->getId();

        $passwordContrains = [
            new Length([
                'min' => 6,
                'minMessage' => 'Su contraseña debe tener al menos {{ limit }} caracteres.',
                'max' => 4096,
            ])
        ];

        if (!$isEdit || !$agent->getPassword()) {
            $passwordContrains[] = new NotNull([
                'message' => 'Por favor poner una contraseña'
            ]);
        }

        $builder
            ->add('fullName', TextType::class, [
                'label' => 'Nombre completo',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo',
            ])
            ->add('username', TextType::class, [
                'label' => 'Nombre usuario',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Los campos de contraseña deben coincidir.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => false,
                'constraints' => $passwordContrains,
                'first_options'  => ['label' => 'Contraseña'],
                'second_options' => ['label' => 'Repetir contraseña'],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Permiso',
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Agente' => 'ROLE_USER',
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('thumbnailFile', VichFileType::class, [
                'label' => 'Foto',
                'mapped' => true,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Selecciona una imagen'
                ],
            ])
            ->add('color', ColorType::class, [
                'label' => 'Color',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Agent::class,
        ]);
    }
}
