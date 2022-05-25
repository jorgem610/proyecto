<?php

namespace App\Controller;

use App\Entity\LineaTicket;
use App\Entity\Tickets;
use App\Repository\TicketsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PasarelaController extends AbstractController
{
    /**
     * @Route("/pasarela", name="app_pasarela")
     */
    public function index(): Response
    {
        return $this->render('pasarela/index.html.twig', [
            'controller_name' => 'PasarelaController',
        ]);
    }

     /**
     * @Route("/pasarela/completado", name="app_pasarela_completado")
     */
    public function completado(SessionInterface $session, EntityManagerInterface $em, TicketsRepository $ticketsRepository): Response
    {
        //ME HE QUEDADO AQUI
        // dd($request);
        // $json = file_get_contents('php://input');
        // $datos = json_decode($json, true);

        // $id = $datos['detalles']['id'];
        // dd($id);

        //Instaciamos el objeto ticket
        $ticket = new Tickets;
        //Le introducimos el usuario que ha hecho la compra
        $ticket->setUsuario($this->getUser());
        //Le ponemos como true si ha pagado
        $ticket->setPagado(true);
        
        //Creamos la consulta
        $em->persist($ticket);
        //Ejecutamos la consulta en base de datos
        $em->flush();

        $id = $ticketsRepository->maxTicket();
        
        
        $ticket->setId($id);

        $lineaTicket = new LineaTicket;
        $lineaTicket->setTicket($ticket);

        
        $cestaConDatos = $session->get('cestaConDatos');
        
        foreach($cestaConDatos as $datos){
            $lineaTicket->setCantidad($datos['cantidad']);
            $lineaTicket->setProductos($datos['producto']);
            $lineaTicket->setPrecio($datos['producto']->getPrecio());
            $entityLineaTicket = $em->merge($lineaTicket);
            $em->persist($entityLineaTicket);
            $em->flush();
           
        }

        $this->addFlash("success", "Pago confimado");
        
       
        //Ejecutamos la consulta en base de datos
     
        
        //Recorremos la linea de ticket
        
        //Cuando el pago esta completado elimino la session de la cestas y del total
        $session->remove('cesta');
        $session->remove("cestaConDatos");
        $session->remove("total");
        $session->remove("ticket");
        $session->remove("lineaTicket");
        return $this->redirectToRoute("app_inicio");
       
    }


}
