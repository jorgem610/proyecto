<?php

namespace App\Controller\Admin;

use App\Entity\Categoria;
use App\Form\CategoriasType;
use App\Repository\CategoriaRepository;
use App\Repository\ProductosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin/categoria", name="admin_categoria_")
 * @package App\Controller\Admin
 */
class AdminCategoriasController extends AbstractController
{
    /**
     * @Route("/inicio", name="inicio")
     */
    public function index(CategoriaRepository $categoriaRepository): Response
    {
        // Para que solo entre el rol admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        //Lo mandamos a la vista correspondiente con todas sus categorias
        return $this->render('admin/categorias/index.html.twig', [
            'categorias' => $categoriaRepository->findAll(),
        ]);
    }

     /**
     * @Route("/agregar", name="agregar")
     * @Route("/modificar/{id}", name="modificar")
     */
    public function agregarCategoria(Request $request, $id=null, CategoriaRepository $categoriaRepository): Response
    {
        //Para que solo pueda entrar el rol de admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        //Instaciamos el objeto categoria
        $categoria = new Categoria;
       //Si el $id que pasa por get es que estoy intentando editar
        if($id!=null){
            //Buscamos en la base de datos la id y le añadimos los datos al objeto categoria
            $categoria = $categoriaRepository->find($id);
        
            //Si la categoria no existe
            if(empty($categoria->getNombre())){
                $this->addFlash("red", "No existe la categoria que se quiere editar");
                return $this->redirectToRoute("admin_categoria_inicio");
            }
        }
        //Creamos el formularios de categoria
        $form = $this->createForm(CategoriasType::class, $categoria);
        //Recogemos la respuesta
        $form->handleRequest($request);
        //Si la respuesta viene por submit y es valido
        if($form->isSubmitted() && $form->isValid()){
            //Le pasamos el objeto categoria y symfony te detecta si tiene que actualizar o añadir una nueva categoria
            $categoriaRepository->add($categoria, true);
            //Cuando haya hecho los cambios en la base de datos te muestra el mensaje
            $this->addFlash("green", "Datos guardados con éxito");
            //y te redirecciona al inicio de categoria
            return $this->redirectToRoute('admin_categoria_inicio');
        }

        //Si no viene del formulario te redirecciona a la vista y le pasa el formulario
        return $this->render('admin/categorias/crear.html.twig', [
            'form' => $form->createView(),
            'id' => $id
        ]);
    }


    /**
     * @Route("/eliminar/{id}", name="eliminar")
     */
    public function eliminar($id=null, ProductosRepository $productosRepository, CategoriaRepository  $categoriaRepository): Response
    {
        //Para que solo pueda entrar el rol admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        //Instaciamos un objeto categoria
        $categoria = new Categoria();
        //Si el id no es nulo
        if($id!=null){
            //Buscamos la categoria
            $categoria = $categoriaRepository->find($id);
            //Si la categoria es nula
            if($categoria==null){
                //Lanzamos un mensaje y su contenido
                $this->addFlash("red", "No existe la categoria que se quiere borrar");
                //Lo retornamos a la vista de categoria inicio
                return $this->redirectToRoute("app_categoria_inicio");
            }
        }
        //Instaciamos el objeto categoria
        $noticiasAll = $productosRepository->findBy(['categoria' => $categoria]);
        //Si esa categoria no tiene noticias la eliminamos
        if (empty($noticiasAll)) {
            //Creamos la sql de eliminar y la ejecutamos
            $categoriaRepository->remove($categoria, true);
            
           //Le enviamos un mensaje flash diciendole que se ha eliminado correctamente
            $this->addFlash('green', 'Se ha eliminado correctamente la categoria');
        }else{
            //Mensaje flash
            $this->addFlash('red', 'No se puede eliminar esta categoria porque contiene productos');
        }

        //Lo redireccionamos a donde queremos que vaya tras ser eliminado
        return $this->redirectToRoute('admin_categoria_inicio');
    }

    
}
