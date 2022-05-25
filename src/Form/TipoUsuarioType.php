<?php 

namespace App\Form;

use App\Repository\NoticiaRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TipoUsuarioType extends AbstractType {


    //Pongo en esta funcion los diferentes roles que admite mi pagina
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(["choices" => [
            "Usuario"=> "ROLE_USER",
            "Admin"=> "ROLE_ADMIN"
        ]]);
    }

    //Y le digo que proviene de la clase choice que es para elegir varias opciones
    public function getParent() {
        return ChoiceType::class;
    }

}