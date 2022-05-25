<?php

namespace App\Controller\Admin;

use App\Entity\Categoria;
use App\Entity\Productos;
use App\Form\CategoriasType;
use App\Form\ProductoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin", name="admin_")
 * @package App\Controller\Admin
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="inicio")
     */
    public function index(): Response
    {
        // Para que solo entre el admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        //La parte del inicio 
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    
}
