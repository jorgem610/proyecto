<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Entity\Usuarios;
use App\Form\SearchProductoType;
use App\Repository\CategoriaRepository;
use App\Repository\ProductosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


class InicioController extends AbstractController
{
   
    /**
     * @Route("/", name="app_inicio")
     * @Route("/categoria/{id}", name="app_componentes")
     * 
     */
    public function index(Request $request, ProductosRepository $productosRepository, CategoriaRepository $categoriaRepository, $id = null): Response
    {
        
        //Si el $id que pasa por get es que estoy intentando r entrar a las categoria
        if ($id != null) {
            $producto = $productosRepository->productosConCatStock($id, null);

            //Si el objeto producto es null
            if ($producto == null) {
                //Le mostramos el mensaje de error
                $this->addFlash("danger", "No existe ningún producto en la categoria que quieres buscar");
                //Lo retornamos al inicio 
                return $this->redirectToRoute("app_inicio");
            }
           
        }else{
            //Si no vengo de categoria quiere decir que tengo que mostrar todos los productos
            $producto = $productosRepository->productosConStock();
        }

        //Saco las categorias para mostrar nuestros productos
        $categoria = $categoriaRepository->findAll();


        //Creamos el formulario de buscar
        $form = $this->createForm(SearchProductoType::class);
        //Recibimos la busquedad
        $search = $form->handleRequest($request);

        //Si viene por el boton y el formulario es valido
        if ($form->isSubmitted() && $form->isValid()) {
            // buscamos los productos que venga por search (por contenido y nombre)
            $producto = $productosRepository->search($search->get('palabras')->getData(), $id);
            
        }

        return $this->render('inicio/inicio.html.twig', [
            //Enviamos un array associativo a la vista inicio.html.twig con los productos, las categorias y el formulario buscar
            'productos' => $producto,
            'formSearch' => $form->createView(),
            'categorias' => $categoria,
            'id'  => $id
        ]);
    }


    /**
     * @Route("/ofertas/{especial}", name="app_ofertas")
     */
    public function ofertas(ProductosRepository $productosRepository, CategoriaRepository $categoriaRepository, $especial=null): Response
    {

        //Si vengo por especial quiere decir que vengo de Ofertas, outlet o gaming
        if ($especial != null) {
            //Busco esos productos con ese especial que me pasa por get su codgio
            $producto = $productosRepository->productosConCatStock(null, $especial);

            //Si el objeto producto es null
            if ($producto == null) {
                //Le mostramos el mensaje de error
                $this->addFlash("danger", "No existe ningún producto en esa categoria que quieres buscar");
                //Lo retornamos al inicio 
                return $this->redirectToRoute("app_inicio");
            }
           
        }

        //Creamos el formulario de buscar
        $form = $this->createForm(SearchProductoType::class);
        //Creamos las categorias para nuestro productos
        $categoria = $categoriaRepository->findAll();
        //Le mandamos a la vista correspondientes todos las variables para pintarla
        return $this->render('inicio/ofertas/index.html.twig', [
            'ofertas' => $producto,
            'categorias' => $categoria,
            'formSearch' => $form->createView()
        ]);
    }
}
