<?php

namespace App\Form;

use App\Entity\Categoria as EntityCategoria;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchProductoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('palabras', SearchType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-control-dark',
                    'placeholder' => 'Búsqueda..'
                ],
                'row_attr' => [
                    'class' => 'col-8'
                ],
                
                ])
                ->add('Buscar', SubmitType::class,[
                    'label' => false,
                   
                    'attr' => [
                        'class' => 'btn btn-primary bi bi-search'
    
                    ],
                    'row_attr' => [
                        'class' => 'col-1'
                    ]
                    
                ]
    
            );
            // ->add('categoria', EntityType::class, [
            //     'class' => EntityCategoria::class,
            //     'label' => false,
            //     'attr' => [
            //         'class' => 'form-control',
            //     ],
            //     'required' => false
            // ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
