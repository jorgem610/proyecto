<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Entity\Usuarios;
use App\Repository\ProductosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        
        // Si hay un error lo coge y lo guarda en esta variable
        $error = $authenticationUtils->getLastAuthenticationError();
        // ultimo usuario introducido por el usuario
        $lastUsername = $authenticationUtils->getLastUsername();

        //Lo mandamos a la vista del login
        return $this->render('security/login.html.twig', [
                'last_username' => $lastUsername, 
                'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout( ): void
    {
        //Cerrar session
        throw new \LogicException('Este método puede estar en blanco - será interceptado por la clave de cierre de sesión del cortafuegos.');
    }
}
