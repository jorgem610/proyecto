<?php

namespace App\Controller;

use App\Entity\Usuarios;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

/**
 * @Route("/reset-password")
 */
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    private $resetPasswordHelper;
    private $entityManager;

    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper, EntityManagerInterface $entityManager)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->entityManager = $entityManager;
    }

    /**
     * 
     *
     * @Route("", name="app_forgot_password_request")
     */
    public function request(Request $request, MailerInterface $mailer, TranslatorInterface $translator): Response
    {
        //Creamos el formulario
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        //La respuesta del formulario
        $form->handleRequest($request);
        //Si viene del submit y el form es valido 
        if ($form->isSubmitted() && $form->isValid()) {
            //Hacemos el envio del formulario 
            return $this->processSendingPasswordResetEmail(
                $form->get('email')->getData(),
                $mailer,
                $translator
            );
        }
        //Si no viene del form pintamos el formulario en la vista
        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    /**
     * P??gina de confirmaci??n despu??s de que un usuario haya solicitado el restablecimiento de su contrase??a.
     *
     * @Route("/check-email", name="app_check_email")
     */
    public function checkEmail(): Response
    {
      
        
        //Le envio el mensaje
        $this->addFlash("success", "Si existe una cuenta que coincide con su correo electr??nico, entonces se acaba de enviar un correo electr??nico que contiene un enlace que puede utilizar para restablecer su contrase??a.");

        //Lo envio a la vista por si quiere volver a escribir el correo
        return $this->redirectToRoute("app_forgot_password_request");

       
    }

    /**
     * Valida y procesa la URL de restablecimiento que el usuario ha pulsado en su correo electr??nico..
     *
     * @Route("/reset/{token}", name="app_reset_password")
     */
    public function reset(Request $request, UserPasswordHasherInterface $userPasswordHasher, TranslatorInterface $translator, string $token = null): Response
    {
        //Si existe el token
        if ($token) {
            //Almacenamos el token en la sesi??n y lo eliminamos de la URL, para evitar que la URL sea
            //cargada en un navegador y que potencialmente se filtre el token a un JavaScript de terceros.
            $this->storeTokenInSession($token);
            //Le retornamos la vista
            return $this->redirectToRoute('app_reset_password');
        }
        //Guardamos un token del formulario en session
        $token = $this->getTokenFromSession();
        //Si el token es erroneo te salta una excepcion
        if (null === $token) {
            throw $this->createNotFoundException('No se ha encontrado un token de restablecimiento de contrase??a en la URL o en la sesi??n.');
        }

        try {

            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            //La exepcecion con el mensaje flash
            $this->addFlash('reset_password_error', sprintf(
                '%s - %s',
                $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_VALIDATE, [], 'ResetPasswordBundle'),
                $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
            ));

            return $this->redirectToRoute('app_forgot_password_request');
        }

        // El token es v??lido; permite al usuario cambiar su contrase??a.
        $form = $this->createForm(ChangePasswordFormType::class);
        //Cogemos la respuesta del formulario
        $form->handleRequest($request);
        //Si viene del boton y es valido
        if ($form->isSubmitted() && $form->isValid()) {
            // Un token de restablecimiento de contrase??a debe usarse s??lo una vez, borramos el token
            $this->resetPasswordHelper->removeResetRequest($token);

            // Hasheamos la pass
            $encodedPassword = $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );
            //Le ponemos la contrase??a hasheada
            $user->setPassword($encodedPassword);
            //la ejecutamos en la base de datos
            $this->entityManager->flush();

            // La sesi??n se limpia despu??s de cambiar la contrase??a.
            $this->cleanSessionAfterReset();
            //Lo mandamos al login
            return $this->redirectToRoute('app_login');
        }
        //Si no viene por el boton pintamos el formulario en su vista
        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

    //Proceso del reseteo de la imagen
    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer, TranslatorInterface $translator): RedirectResponse
    {
        //En el objeto usuario le metemose el usuario con ese email
        $user = $this->entityManager->getRepository(Usuarios::class)->findOneBy([
            'email' => $emailFormData,
        ]);

        //Si no es un usuario
        if (!$user) {
            return $this->redirectToRoute('app_check_email');
        }

        try {
            //Generamos un token
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            //Si hay una excepcion lo mandamos a la ruta
            return $this->redirectToRoute('app_check_email');
        }

        //Si no creamos un email
        $email = (new TemplatedEmail())
            ->from(new Address('jorgemv610@gmail.com', 'PcInform??tica'))
            ->to($user->getEmail())
            ->subject('Restablecer la contrase??a')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ])
        ;
        
        //Se lo enviamos
        $mailer->send($email);

        // Almacenamos el objeto token en la sesi??n para recuperarlo en la ruta de comprobaci??n del correo electr??nico.
        $this->setTokenObjectInSession($resetToken);
            
        //Lo redirecionamos a la ruta app_check_mail
        return $this->redirectToRoute('app_check_email');
    }
}
