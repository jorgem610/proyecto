<?php

namespace App\Form;

use App\Entity\Usuarios;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nombre'
                ],
                'row_attr' => ['class' => 'form-group'],
                
            ])
            ->add('apellidos', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Apellidos'
                ],
                'row_attr' => ['class' => 'form-group'],
               
                
            ])
            ->add('direccion', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Dirección'
                ],
                'row_attr' => ['class' => 'form-group'],
                'label' => false
                
            ])
            ->add('dni', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'DNI'
                ],
                'row_attr' => ['class' => 'form-group'],
                'label' => false
                
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Email'
                ],
                'row_attr' => ['class' => 'form-group'],
                'label' => false
            ])
            ->add('plainPassword', RepeatedType::class,[
                    'row_attr' => ['class' => 'form-group'],
                    'type' => PasswordType::class,
                    'invalid_message' => 'Las contraseñas deben coincidir',
                    'first_options' => [
                            'label' => false, 
                            'attr' => [
                                    'class' => 'form-control', 
                                    'autocomplete' => 'new-password', 
                                    'placeholder' => 'Contraseña']],
                    'second_options' => ['label' => false, 'attr' => ['class' => 'form-control', 'autocomplete' => 'new-password', 'placeholder' => 'Repetir contraseña']],
                  
                    'mapped' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Por favor introduce la contraseña',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Tu contraseña debe tener al menos {{ limit }} caracteres',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
            
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuarios::class,
        ]);
    }
}
