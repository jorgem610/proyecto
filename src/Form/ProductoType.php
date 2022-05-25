<?php

namespace App\Form;

use App\Entity\Categoria;
use App\Entity\Categoria as EntityCategoria;
use App\Entity\Productos;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
               
                'attr' => array(
                    'class' => 'form-control rounded-1 mb-1',
                    'placeholder' => 'Introduce el nombre del producto',
                ),
               
                'row_attr' => [
                    'class' => 'form-field'
                ],
            
                
            ])
            ->add('contenido', TextareaType::class, [
                'attr' => array(
                    'class' => 'form-control rounded-1 mb-1',
                    'placeholder' => 'Introduce la descripción del producto',
                    'cols' => 40,
                    'rows' => 20,
                ),
                
                'row_attr' => [
                    'class' => 'form-field'
                ],
                
            ]
            
            )
            ->add('imagenes', FileType::class,[
                'attr' => array(
                    'class' => 'form-control rounded-1 mb-1'
                ),
                'row_attr' => [
                    'class' => 'form-field'
                ],
               
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('precio', NumberType::class, [
                'attr' => array(
                    'class' => 'form-control rounded-1 mb-1',
                    'placeholder' => 'Introduce el precio del producto',
                ),
                'row_attr' => [
                    'class' => 'form-field'
                ],
              
            ])
            ->add('stockMinimo', NumberType::class, [
                'attr' => array(
                    'class' => 'form-control rounded-1 mb-1',
                    'placeholder' => 'Introduce el stock mínimo del producto',
                    
                ),
                'row_attr' => [
                    'class' => 'form-field'
                ],
               
            ])
            
            ->add('categoria', EntityType::class, [
                'class' => Categoria::class,
                'label_attr' => array('class' => 'form-check'),
                'attr' => array(
                    'class' => 'form-control rounded-1 mb-1'
                ),
                'row_attr' => [
                    'class' => 'form-field'
                ],
                
                
            ])
            ->add('activo', CheckboxType::class, ['required' => false])
            ->add('Agregar', SubmitType::class, [
                'attr' => array(
                    'class' => 'btn shadow-1 rounded-1 blue mb-1'
                ),
                'row_attr' => array(
                    'class' => 'form-grupo text-center m-1'
                )
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Productos::class,
        ]);
    }
}
