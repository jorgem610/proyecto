<?php

namespace App\Controller;

use App\Form\SearchProductoType;
use App\Repository\ProductosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/productos", name="producto_")
* @package App\Controller
*/

class ProductoController extends AbstractController
{
    /**
     * @Route("/detalles/{id}", name="detalles")
     */
    public function index( Request $request, $id, ProductosRepository $productosRepository): Response
    {
        $producto = $productosRepository->findOneBy(['id' => $id]);

        //Creamos el formulario de buscar
        $formSearch = $this->createForm(SearchProductoType::class);
        //Cogemos el texto de la busquedad
        $search = $formSearch->handleRequest($request);

        if($formSearch->isSubmitted() && $formSearch->isValid()){
            //cogemos los productos de la busquedad
            $producto = $productosRepository->search($search->get('palabras')->getData(), $search->get('categoria')->getData());
            //Lo mandamos al inicio
            return $this->render('inicio/inicio.html.twig', [
                //Enviamos un array associativo a la vista inicio.html.twig con los productos que estan activo
                'productos' => $producto,
                'formSearch' => $formSearch->createView()
            ]);

        }
       
        if(!$producto){
            throw new NotFoundHttpException('No se ha encontrado este producto');
        }
        return $this->render('productos/detalles.html.twig',[
            'producto' => $producto,
            'formSearch' => $formSearch->createView()
    ]);
    }
}
