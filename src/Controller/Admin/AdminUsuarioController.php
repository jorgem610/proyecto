<?php

namespace App\Controller\Admin;

use App\Entity\Usuarios;
use App\Form\UsuarioType;
use App\Repository\UsuariosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/usuario", name="admin_usuario_")
 * @package App\Controller\Admin
 */
class AdminUsuarioController extends AbstractController
{
    /**
     * @Route("/", name="lista")
     */
    public function index(UsuariosRepository $usuariosRepository): Response
    {
        // Para que solo entre el admin
          $this->denyAccessUnlessGranted('ROLE_ADMIN');
        //Le redireccionamos a la vista correspondiente y le mandamos todos los usuarios para mostrarlo
        return $this->render('admin/usuario/index.html.twig', [
            'usuarios' => $usuariosRepository->findAll(),
        ]);
    }

      /**
     * @Route("/activo/{id}", name="activo")
     */
    public function activo(Usuarios $usuario, EntityManagerInterface $em): Response
    {
        // Para que solo entre el admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        //Dependiendo de lo que mande por get al objeto le pones true o false
        $usuario->setActivo(($usuario->isActivo())?false:true);
        //Creamos la consulta (symfony detecta si hay que hacer un update o insert)
        $em->persist($usuario);
        //La ejecutamos en la BD
        $em->flush();

        return new Response("true");
       
    }

    /**
    * @Route("/agregar", name="agregar")
     * @Route("/modificar/{id}", name="modificar")
     */
    public function modificarUsuario($id=null, Request $request, UsuariosRepository $usuariosRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        //Para que solo pueda entrar el rol de admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        //Instaciamos la clase usuario
        $usuario = new Usuarios;
        //Si el $id que pasa por get es que estoy intentando editar
        if($id!=null){
            //Buscamos el usuario con ese id y se lo añadimos al objeto usuario
            $usuario = $usuariosRepository->find($id);
            //Si es null quiere decir que no existe categoría con ese id
            if(empty($usuario->getDni())){
                $this->addFlash("danger", "No existe el usuario que se quiere editar");
                return $this->redirectToRoute("admin_usuario_lista");
            }
        }
      
        //Creamos el formulario de Usuario
        $form = $this->createForm(UsuarioType::class, $usuario);
        
        //Recogemos la respuesta
        $form->handleRequest($request);
        
        //Si la respuesta viene por el formulario y es valido
        if ($form->isSubmitted() && $form->isValid()) {
            //Comprobamos que la contraseña no este vacia ni sea nula
            if($usuario->getPlainPassword()!=null && $usuario->getPlainPassword()!="") {
                //Hasheamos la contraseña plana
                $passHashed = $userPasswordHasher->hashPassword($usuario, $usuario->getPlainPassword());
                //Se la añadimos al objeto usuario
                $usuario->setPassword($passHashed);
            }
            //Creamos la consulta y la ejecutamos en la base de datos
            $usuariosRepository->add($usuario, true);
            //Le retornamos un mensaje
            $this->addFlash("success", "Datos guardados con éxito");
           //Lo redireccionamos al inicio de usuario
            return $this->redirectToRoute('admin_usuario_lista');
        }
        //Mandamos el formulario a la vista
        return $this->render('admin/usuario/crear.html.twig', [
            'form' => $form->createView(),
            'id' => $id
        ]);
    }
}
