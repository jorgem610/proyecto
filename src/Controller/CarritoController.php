<?php

namespace App\Controller;

use App\Entity\LineaTicket;
use App\Entity\Productos;
use App\Entity\Tickets;
use App\Repository\LineaTicketRepository;
use App\Repository\ProductosRepository;
use App\Repository\TicketsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CarritoController extends AbstractController
{
    /**
     * @Route("/index", name="carrito_index")
     */
    public function index(SessionInterface $session, ProductosRepository $productosRepository): Response
    {

        // Para que solo entre el usuario
        $this->denyAccessUnlessGranted('ROLE_USER');
        //Recupero los datos de la sessiones
        $cesta = $session->get('cesta', []);

        //Creo un array
        $cestaConDatos = [];
        //Creo el total
        $total = 0;

        //Recorro la cesta y guardo los valores
        foreach ($cesta as $id => $cantidad) {
            $producto = $productosRepository->find($id);
            $total += $producto->getPrecio() * $cantidad;
            $cestaConDatos[] = [
                'producto' => $producto,
                'cantidad' => $cantidad,
                'total' => $total
            ];
        }


        $session->set('cestaConDatos', $cestaConDatos);
        $session->set('total', $total);
        return $this->redirectToRoute("app_inicio");
    }

    /**
     * @Route("/cesta", name="carrito_pagar")
     */
    public function pago(SessionInterface $session, ProductosRepository $productosRepository): Response
    {

        // Para que solo entre el usuario
        $this->denyAccessUnlessGranted('ROLE_USER');
        //Recupero los datos de la sessiones
        $cesta = $session->get('cesta', []);

        //Creo un array
        $cestaConDatos = [];
        //Creo el total
        $total = 0;

        //Recorro la cesta y guardo los valores
        foreach ($cesta as $id => $cantidad) {
            $producto = $productosRepository->find($id);
            $total += $producto->getPrecio() * $cantidad;
            $cestaConDatos[] = [
                'producto' => $producto,
                'cantidad' => $cantidad,
                'total' => $total
            ];
        }

        $session->set('cestaConDatos', $cestaConDatos);
        $session->set('total', $total);
       
        return $this->render('carrito/index.html.twig');
    }

    /**
     * @Route("/cesta/agregar/{id}", name="carrito_add")
     */

    public function add(Productos $productos, SessionInterface $session, EntityManagerInterface $em, LineaTicketRepository $lineaTicketRepository)
    {
        $id = $productos->getId();

        $arrayStockProducto = $session->get('arrayStockProducto', []);
        if(!empty($arrayStockProducto[$id])){
            $arrayStockProducto[$id] = $productos->getStockMinimo();
        }else{
            $arrayStockProducto[$id] = $productos->getStockMinimo();
        }
       
        $session->set('arrayStockProducto', $arrayStockProducto);

        // $existe = $session->get('cesta');

        //Si no existe la session cesta me creas el ticket
        // if ($existe == null) {
        //     $ticket = new Tickets();
        //     $ticket->setUsuario($this->getUser());
        //     $ticket->setPagado(0);
        //     $session->set('ticket', $ticket);
        //     $em->persist($ticket);
        //     $em->flush();
        // }
        //Recuperamos la cesta actual
        $cesta = $session->get('cesta', []);

       


        if (!empty($cesta[$id])) {
            //Si un producto existe en la cesta te va sumando la cantidad del producto
            $cesta[$id]++;
            //Sacamos la linea de ticket existente y la sumamos la cantidad
            // $lineaTicketModificado = $lineaTicketRepository->sacarLineaTicket($id,  $session->get('ticket'));
            // $lineaTicketModificado->setCantidad($cesta[$id]);
            // $em->persist($lineaTicketModificado);
            // $em->flush();

            //Le quitamos del stockminimo un producto
            $productos->setStockMinimo($productos->getStockMinimo() - 1);
            $em->persist($productos);
            $em->flush();
        } else {
            //Si no existe el producto en la cesta te añade a la cesta el nuevo producto
            $cesta[$id] = 1;
            //Creo la linea de ticket
            // $lineaTicket = new LineaTicket;
            // $lineaTicket->setCantidad(1);
            // $lineaTicket->setPrecio($productos->getPrecio());
            // $ticket = $session->get('ticket');
            // $entityTicket = $em->merge($ticket);
            // $lineaTicket->setTicket($entityTicket);
            // $lineaTicket->setProductos($productos);
            // $session->set('lineaTicket', $lineaTicket);
            // $em->persist($lineaTicket);
            // $em->flush();
            //Le quitamos del stockminimo un producto
            $productos->setStockMinimo($productos->getStockMinimo() - 1);
            $em->persist($productos);
            $em->flush();
        }

        $session->set('cesta', $cesta);

        return $this->redirectToRoute("carrito_index");
    }

    /**
     * @Route("/cesta/agregar/prueba/{id}", name="carrito_cesta_add")
     */

    public function addCesta(Productos $productos, SessionInterface $session, EntityManagerInterface $em, LineaTicketRepository $lineaTicketRepository)
    {
        //Saco el stock minimo que hay en ese momento por si vacia la cesta para poder recuperar ese stock y lo //guardo en session 
    
        // $existe = $session->get('cesta');
        
        //Si no existe la session cesta me creas el ticket
        // if ($existe == null) {
        //     $ticket = new Tickets();
        //     $ticket->setUsuario($this->getUser());
        //     $ticket->setPagado(0);
        //     $session->set('ticket', $ticket);
        //     $em->persist($ticket);
        //     $em->flush();
        // }
        //Recuperamos la cesta actual
        $cesta = $session->get('cesta', []);

        $id = $productos->getId();


        if (!empty($cesta[$id])) {
            //Si un producto existe en la cesta te va sumando la cantidad del producto
            $cesta[$id]++;
            //Sacamos la linea de ticket existente y la sumamos la cantidad
            // $lineaTicketModificado = $lineaTicketRepository->sacarLineaTicket($id,  $session->get('ticket'));
            // $lineaTicketModificado->setCantidad($cesta[$id]);
            // $em->persist($lineaTicketModificado);
            // $em->flush();

            //Le quitamos del stockminimo un producto
            $productos->setStockMinimo($productos->getStockMinimo() - 1);
            $em->persist($productos);
            $em->flush();
        } else {
            //Si no existe el producto en la cesta te añade a la cesta el nuevo producto
            $cesta[$id] = 1;
            //Creo la linea de ticket
            // $lineaTicket = new LineaTicket;
            // $lineaTicket->setCantidad(1);
            // $lineaTicket->setPrecio($productos->getPrecio());
            // $ticket = $session->get('ticket');
            // $entityTicket = $em->merge($ticket);
            // $lineaTicket->setTicket($entityTicket);
            // $lineaTicket->setProductos($productos);
            // $session->set('lineaTicket', $lineaTicket);
            // $em->persist($lineaTicket);
            // $em->flush();

            //Le quitamos del stockminimo un producto
            $productos->setStockMinimo($productos->getStockMinimo() - 1);
            $em->persist($productos);
            $em->flush();
        }

        $session->set('cesta', $cesta);

        return $this->redirectToRoute("carrito_pagar");
    }

    /**
     * @Route("/cesta/eliminar/{id}", name="carrito_eliminar")
     */
    public function remove($id, SessionInterface $session, Productos $productos, EntityManagerInterface $em, LineaTicketRepository $lineaTicketRepository)
    {
        $cesta = $session->get('cesta', []);
        $id = $productos->getId();

        if (!empty($cesta[$id])) {
            if ($cesta[$id] > 1) {
                $cesta[$id]--;
                //Sacamos la linea de ticket existente y le vamos restando uno
                $lineaTicketModificado = $lineaTicketRepository->sacarLineaTicket($id,  $session->get('ticket'));
                $lineaTicketModificado->setCantidad($cesta[$id]);
                $em->persist($lineaTicketModificado);
                $em->flush();

                //Le quitamos del stockminimo un producto
                $productos->setStockMinimo($productos->getStockMinimo() + 1);
                $em->persist($productos);
                $em->flush();
            } else {
                unset($cesta[$id]);
                //Cuando quede cero borramos la linea de ticket
                $lineaTicketModificado = $lineaTicketRepository->sacarLineaTicket($id,  $session->get('ticket'));
                $em->remove($lineaTicketModificado);
                $em->flush();
                 //Le quitamos del stockminimo un producto
                 $productos->setStockMinimo($productos->getStockMinimo() + 1);
                 $em->persist($productos);
                 $em->flush();
            }
        }

        $session->set('cesta', $cesta);

        return $this->redirectToRoute("carrito_pagar");
    }

    /**
     * @Route("/eliminar", name="eliminar_todo")
     */
    public function eliminarTodo(SessionInterface $session, EntityManagerInterface $em, ProductosRepository $productosRepository)
    {
    
       

        //Eliminamos las sessiones del carrito
        $session->remove('cesta');
        $session->remove("cestaConDatos");
        $session->remove("total");
        $session->remove("ticket");
        $session->remove("lineaTicket");
        $session->remove("arrayStockProducto");




        return $this->redirectToRoute("app_inicio");
    }

    /**
     * @Route("/eliminar/{id}", name="eliminar")
     */
    // public function eliminar(SessionInterface $session, Productos $productos)
    // {
    //     $cesta = $session->get('cesta', []);
    //     $id = $productos->getId();

    //     if (!empty($cesta[$id])) {
    //         unset($cesta[$id]);
    //     }

    //     //guardamos en la sesión
    //     $session->set('cesta', $cesta);

    //     return $this->redirectToRoute("carrito_pagar");
    // }
}
