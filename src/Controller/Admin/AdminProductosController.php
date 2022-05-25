<?php

namespace App\Controller\Admin;

use App\Entity\Imagenes;
use App\Entity\Productos;
use App\Form\ProductoType;
use App\Repository\ProductosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/producto", name="admin_producto_")
 * @package App\Controller\Admin
 */
class AdminProductosController extends AbstractController
{
    /**
     * @Route("/inicio", name="inicio")
     * 
     */
    public function index(ProductosRepository $productoRepository, Request $request): Response
    {
        // Para que solo entre el admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        //Definimos el número de elementos por página
        $pag = $request->query->get("pag", 1);
        $results = $productoRepository->findAllPaginated($pag);
        
        if($pag <1 || $pag > $results["maxPages"]) {
            $this->addFlash("red", "No existe la página a la que intenta acceder");
            return $this->redirectToRoute("admin_producto_inicio");
        }
        
        $producto = new Productos;
        $form = $this->createForm(ProductoType::class, $producto);
       
        //Le mandamos a la ruta del inicio del producto y le pasamos todos los productos
        return $this->render('admin/productos/index.html.twig', [
            'form' => $form->createView(),
            'maxPages' => $results["maxPages"],
            'productos' => $results["paginator"],
            'pag' => $pag
        ]);
    }

    /**
     * @Route("/activo/{id}", name="activo")
     */
    public function activo(Productos $producto, EntityManagerInterface $em): Response
    {
        
        // Para que solo entre el admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
      
        //Si activo esta en 1 o en 0 al objeto del producto paso por id le ponemos true o false
        $producto->setActivo(($producto->getActivo())?false:true);
        //creamos el update pasando el objeto producto (symfony detecta si tiene que hacer un insert o update)
        $em->persist($producto);
        //La ejecutamos en la base de datos
        $em->flush();
        //Le retornamos un true
        return new Response("true");
       
    }

  
    /**
     * @Route("/agregar", name="agregar")
     * @Route("/modificar/{id}", name="modificar")
     */
    public function agregarProducto(Request $request, EntityManagerInterface $em, $id=null, ProductosRepository $productoRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        //Instaciamos el objeto Producto
       
        $producto = new Productos;
        $images = $producto->getImagenes();
        //Si el $id que pasa por get es que estoy intentando editar
        if($id!=null){
            //Buscamos el producto por su id en la base de datos y se la añadimos al objeto
            $producto = $productoRepository->find($id);
            
           
           
            //Si el objeto producto es null
            if(empty($producto->getNombre())){
                //Le mostramos el mensaje 
                $this->addFlash("red", "No existe el producto que se quiere editar");
                //Lo retornamos al inicio de producto 
                return $this->redirectToRoute("admin_producto_inicio");
            }
        }
        //Creamos el formularios de Producto
        $form = $this->createForm(ProductoType::class, $producto);
        //Recogemos la respuesta
        $form->handleRequest($request);
        //Comprobamos que la respuesta viene por el boton del formulario y es valido
        if ($form->isSubmitted() && $form->isValid()) {
            //Activamos el producto
            $producto->setActivo(true);
            $images = $producto->getImagenes();
            $imagenes = $form->get('imagenes')->getData();
            
            if($imagenes){
                foreach($images as $image){
                    $em->remove($image);
                    $em->flush();
                }
            }
           
           
             //Recorremos las imagenes
             foreach($imagenes as $imagen){
                 
                // Generamos un nombre unico para la imagen
                $nombreFichero = md5(uniqid()).'.'.$imagen->guessExtension();
                //Del producto cogemos su categoria
                $categoria =$producto->getCategoria();
                //Cogemos el nombre de la categoria
                $categoriaNombre = $categoria->getNombre();
              
                //Como tengo las carpetas separadas por sus categoria necesito la categoria para poder guardarlo en su sitio y le pasamos tambien el nombre del fichero
                $imagen->move("uploads/productos/$categoriaNombre/", $nombreFichero
                );
                
                // Instaciamos el objeto imagenes
                $img = new Imagenes();
                //le cambiamos el nombre por el nombre unico
                $img->setNombre($nombreFichero);
                
                
                //añadimos la imagen al objeto producto
                $producto->addImagen($img);
              
            }

            //Cogemos el repository de productos y lo guardamos en la base de datos symfony detecta si tiene que hacer un update o insert
            $productoRepository->add($producto, true);
            //Le mandamos un mensaje que se ha guardado correctamente
            $this->addFlash("green", "Datos guardados con éxito");
          
            //Le retornamos al inicio de producto
            return $this->redirectToRoute('admin_producto_inicio');
        }
        //Le redirecciono a la ruta para crear el nuevo producto
        return $this->render('admin/productos/crear.html.twig', [
            'form' => $form->createView(),
            'id' => $id
        ]);
    }

}
