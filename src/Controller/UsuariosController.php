<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\EditarPerfilType;
use App\Form\PassType;
use App\Form\SearchProductoType;
use App\Repository\ProductosRepository;
use App\Repository\TicketsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class UsuariosController extends AbstractController
{
   
    /**
     * @Route("/miperfil/mis-datos", name="app_usuario_mis-datos")
     */
    public function datos(Request $request, ProductosRepository $productosRepository, UserPasswordHasherInterface $passwordHash, EntityManagerInterface $em, TicketsRepository $ticketsRepository, SessionInterface $session): Response
    {
        // Para que solo entre los usuarios
        $this->denyAccessUnlessGranted('ROLE_USER');

        $usuario = $this->getUser();
        

        //Creamos el formulario de buscar
        $form = $this->createForm(SearchProductoType::class);
        
        //Pintamos la factura
        $ticket = $ticketsRepository->findby(['usuario'=> $usuario->getId() ,
        'pagado' => true]);
        
        //Recibimos la busquedad
        $search = $form->handleRequest($request);

        //Si viene por el boton y el formulario es valido de la busquedad
         if($form->isSubmitted() && $form->isValid()){
            
            // buscamos los productos que venga por search (por contenido y nombre)
           $producto = $productosRepository->search($search->get('palabras')->getData(), $search->get('categoria')->getData());

           return $this->render('inicio/inicio.html.twig', [
               //Enviamos un array associativo a la vista inicio.html.twig con los productos que estan activo
               'productos' => $producto,
               'formSearch' => $form->createView()
           ]);
           
          
       }

       //Instaciamos el objeto usuario
        $usuario = $this->getUser();
       //Creamos el formulario editar perfil para enviarlo a la vista y despues pintarlo
        $formDatos = $this->createForm(EditarPerfilType::class, $usuario);
        //Guardamos la respuesta
        $formDatosUser = $formDatos->handleRequest($request);
        //Si la respuesta viene por el boton y es valido
        if($formDatosUser->isSubmitted() && $formDatosUser->isValid()){
            $em->persist($usuario);
            $em->flush();
            $this->addFlash('success', 'Perfil actualizado');
            return $this->redirectToRoute('app_usuario_mis-datos');
        }


       
        return $this->render('usuarios/miperfil/miperfil.html.twig', [
            'formSearch' => $form->createView(),
            'formDatos' => $formDatos->createView(),
            'tickets' => $ticket
            
        ]);
    }


    /**
     * @Route("/miperfil/facturas/{idTicket}", name="app_usuario_mis-facturas")
     */
    public function facturas($idTicket = null, TicketsRepository $ticketsRepository): Response
    {
          //Iniciamos la variable total
        $total = 0;
        $dni = "";
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
                 $dni = $producto['dni'];
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
         $html = $this->renderView('admin/tickets/pdf.html.twig', ['productos' => $resultado, 'total' => $total, 'dni' => $dni]);
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

  
    /**
     * @Route("/miperfil/modificar", name="app_pass_modificar")
     */

    public function editarPass(Request $request, UserPasswordHasherInterface $passwordHash, EntityManagerInterface $em): Response
    {
        // Para que solo entre los usuarios
        $this->denyAccessUnlessGranted('ROLE_USER');
    
        if ($request->isMethod('POST')) {
            // dd($request->getPathInfo());
            $usuario = $this->getUser();

            //Verificamos si las dos contraseñas son las mismas
            if ($request->request->get('pass') == $request->request->get('pass2')) {
                
                $usuario->setPassword($passwordHash->hashPassword($usuario, $request->request->get('pass')));
                $em->flush();
                $this->addFlash('success', 'Has cambiado la contraseña correctamente');
                return $this->redirectToRoute('app_usuario_mis-datos');
            } else {
                $this->addFlash('danger', 'Las contraseñas tienen que ser la misma');
            }
        }
        return $this->render('usuarios/miperfil/editarPass.html.twig');
    }

    
}
