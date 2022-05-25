<?php

namespace App\Controller\Admin;



use App\Repository\TicketsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;


/**
 * @Route("/admin/tickets", name="admin_ticket_")
 * @package App\Controller\Admin
 */
class AdminTicketsController extends AbstractController
{
    /**
     * @Route("/", name="lista")
     */
    public function index(TicketsRepository $ticketsRepository): Response
    {
        //Le retornamos cuando venga por el path lista le mostramos la vista
        return $this->render('Admin/tickets/index.html.twig', [
            //Sacamos todos los ticket que esten pagado a true
            'tickets' => $ticketsRepository->findby(['pagado' => true]),


        ]);
    }




    /**
     * @Route("/factura/{idTicket}", name="factura")
     *
     */
    public function factura($idTicket = null, TicketsRepository $ticketsRepository)
    {
        //Iniciamos la variable total
        $total = 0;
       //Si el $id que pasa por get es que estoy intentando editar
        if ($idTicket != null) {
            //cogemos el id del ticket y recogemos los productos
            $resultado = $ticketsRepository->ventas($idTicket);
           
            if(empty($resultado)){
                $this->addFlash("red", "No existe el usuario que se quiere editar");
                return $this->redirectToRoute("admin_ticket_lista");
            }

            //Recorremos el array y le sumamos el total
            foreach ($resultado as $producto) {
                $total += $producto['total'];
            }
        }

        //DOMPDF es una libreria que he instalado en mi proyecto
        //Instaciamos el objeto pdfOptions
        $pdfOptions = new Options();
        //Le introducimos al objeto el tipo de fuente
        $pdfOptions->set('defaultFont', 'Arial');

        $pdfOptions->setIsRemoteEnabled(true);

        //Instaciamos el objeto dompdf y le pasamos el objeto pdfoptions
        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);
        //Cogemos el html de la vista y le pasamos los productos y el total para pintarlo en el html
        $html = $this->renderView('admin/tickets/pdf.html.twig', ['productos' => $resultado, 'total' => $total]);
        //cargamos el contenido HTML
        $dompdf->loadHtml($html);
        //Definimos el tamaño y la orientacion que queremos
        $dompdf->setPaper('A4', 'portrait');
        //Renderizamos el documento
        $dompdf->render();
        //El nombre del fichero
        $fichero = 'Ticket-' . $idTicket . '.pdf';
        //Enviamos el fichero pdf al navegador y lo mostramos
        $dompdf->stream($fichero, [
            'Attachment' => false
        ]);

        return new Response();
    }
}
