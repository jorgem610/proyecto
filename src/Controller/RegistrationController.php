<?php

namespace App\Controller;

use App\Entity\Usuarios;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/registro", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        //Instaciamos el objeto usuario
        $user = new Usuarios();
        //Guardamos el formulario creado para después mostrarlo en la vista
        $form = $this->createForm(RegistrationFormType::class, $user);
        //Recogemos la respuesta
        $form->handleRequest($request);
        //Si viene del boton submit y es valido 
        if ($form->isSubmitted() && $form->isValid()) {
            // le añadimos al objeto usuario el password y hasheamos la clave
            $user->setPassword(+
            //Hasheamos la pass (basada a la configuración que tenemos el security.yaml)
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            //Creamos la consulta para insertar el usuario
            $entityManager->persist($user);
            //Ejecutamos la consulta en la base de datos
            $entityManager->flush();

            // Generamos el correo para enviarle la verificacion y cogemos su correo para enviarselo
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    //quien lo envia
                    ->from(new Address('jorgemv610@gmail.com', 'PcInformática'))
                    //A quien va dirigido
                    ->to($user->getEmail())
                    //Titulo
                    ->subject('Por favor confirma tu Email')
                    //Body
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            //Retornamos el usuario autenticado
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        //Solo puede entrar quien esta verificado
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Cuando valida el enlace de confirmación por correo electrónico actualizamos que esta verificado el usuario
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));
            
            return $this->redirectToRoute('app_register');
        }

        // Mensaje de que se ha verificado
        $this->addFlash('success', 'Tu email ha sido verificado.');
        //Lo mandamos al app_inicio
        return $this->redirectToRoute('app_inicio');
    }
}
