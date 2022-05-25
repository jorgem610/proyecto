<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'row_attr' => ['class' => 'form-group'],
                'type' => PasswordType::class,
                'first_options' => [
                    
                    'attr' => [
                        'class' => 'form-control',
                        'autocomplete' => 'new-password',
                        'placeholder' => 'Contraseña'
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Por favor, introduzca una contraseña',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Su contraseña debe tener al menos {{ limit }} caracteres',
                            'max' => 4096,
                        ]),
                    ],
                    'label' => false,
                ],
                'second_options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control',
                        'placeholder' => 'Repetir contraseña'
                        
                    ],
                    'label' => false,
                ],
                'invalid_message' => 'Las contraseñas deben coincidir.',
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
