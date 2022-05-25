<?php

namespace App\Form;

use App\Entity\Usuarios;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditarPerfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre: ',
                'label_attr' => array(
                    'class' => 'col-2'),
                'attr' => array(
                    'class' => 'form-control'
                ),
                

            ])
            ->add('apellidos', TextType::class,[
                'label' => 'Apellidos: ',
                'label_attr' => array(
                    'class' => 'col-2'),
                    'attr' => array(
                        'class' => 'form-control'
                    ),
            ])
            ->add('direccion', TextType::class, [
                'label' => 'Direccion: ',
                'label_attr' => array(
                    'class' => 'col-2'),
                    'attr' => array(
                        'class' => 'form-control'
                    ),
            ])
            ->add('dni', TextType::class,[
                'label' => 'DNI: ',
                'label_attr' => array(
                    'class' => 'col-2'),
                    'attr' => array(
                        'class' => 'form-control'
                    ),
            ])
            ->add('Cambiar', SubmitType::class, [
                'attr' => array(
                    'class' => 'btn btn-primary'
                ),
                'row_attr' => array(
                    'class' => ''
                )
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
