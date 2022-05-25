<?php

namespace App\Form;

use App\Entity\Usuarios;
use App\Form\TipoUsuarioType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //Creo el formulario para poder modificar el usuario
        $builder
            ->add('email', TextType::class, [
                "required"=>false,
                'attr' => array(
                    'class' => 'form-control rounded-1 mb-1',
                    'placeholder' => 'Introduce el email',
                ),
               
                'row_attr' => [
                    'class' => 'form-field'
                ],
            ])
            ->add('roles', TipoUsuarioType::class, ["required"=>false, 
            "multiple" => true, 
            "expanded" => true,
            ])
            ->add('plainPassword', RepeatedType::class, [ 
                "type" => PasswordType::class, 
                "invalid_message" => "Las contraseñas deben coincidir",
                "first_options" => ["label"=> "Clave"],
                "second_options" => ["label"=> "Repite clave"],
                "required"=>false
            ])
            ->add('nombre', TextType::class,[
                'attr' => array(
                    'class' => 'form-control rounded-1 mb-1',
                    'placeholder' => 'Introduce el nombre del usuario',
                ),
               
                'row_attr' => [
                    'class' => 'form-field'
                ],
            ])
            ->add('apellidos', TextType::class, [
                'attr' => array(
                    'class' => 'form-control rounded-1 mb-1',
                    'placeholder' => 'Introduce el apellido del usuario',
                ),
               
                'row_attr' => [
                    'class' => 'form-field'
                ],
            ])
            ->add('direccion', TextType::class, [
                'attr' => array(
                    'class' => 'form-control rounded-1 mb-1',
                    'placeholder' => 'Introduce la dirección del usuario',
                ),
               
                'row_attr' => [
                    'class' => 'form-field'
                ],
            ])
            ->add('dni', TextType::class, [
                'attr' => array(
                    'class' => 'form-control rounded-1 mb-1',
                    'placeholder' => 'Introduce la dni del usuario',
                ),
               
                'row_attr' => [
                    'class' => 'form-field'
                ],
            ])
            ->add('btnRegistro', SubmitType::class, ["label"=> 'Modificar Usuario'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuarios::class,
        ]);
    }
}
