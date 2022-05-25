<?php

namespace App\Security;

use App\Entity\Tickets;
use App\Repository\ProductosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function authenticate(Request $request): Passport
    {
        dd($request);
        //Cojo el email por post
        $email = $request->request->get('email', '');

        //lo meto en session
        $request->getSession()->set(Security::LAST_USERNAME, $email);

        //Este passport requiere un usuario (email) y una pass
        return new Passport(
            //Nos permite adjuntar el email
            new UserBadge($email),
            //Cogemos la claave
            new PasswordCredentials($request->request->get('password', '')),
            [
                //Valida automaticamnete los tokens CSRF
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    //Cuando la auntenticacion es correcta

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        
        
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        //Si el usuario es rol admin lo mandamos al inicio de admin
        if($token->getUser()->isAdmin()){
            return new RedirectResponse($this->urlGenerator->generate('admin_producto_inicio'));
        }

      
        //Si el usuario es rol user lo mandamos al inicio de admin
        return new RedirectResponse($this->urlGenerator->generate('app_inicio'));
      
    }

    
    protected function getLoginUrl(Request $request): string
    {
        
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
