<?php

// src/Security/ApiKeyAuthenticator.php
namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\PasswordUpgradeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Contracts\Translation\TranslatorInterface;

class UsernameAuthenticator extends AbstractAuthenticator
{

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RouterInterface
     */
    private $router;

    private $csrfTokenManager;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(RouterInterface $router,TranslatorInterface $translator,CsrfTokenManagerInterface $csrfTokenManager,UrlGeneratorInterface $urlGenerator)
    {
        $this->router = $router;
        $this->translator = $translator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->urlGenerator = $urlGenerator;
    }


        const LOGIN_ROUTE = "user.login";

        /**
        * Called on every request to decide if this authenticator should be
        * used for the request. Returning `false` will cause this authenticator
        * to be skipped.
        */
        public function supports(Request $request): ?bool
        {
            return self::LOGIN_ROUTE === $request->attributes->get('_route')
                && $request->isMethod('POST');
        }

    public function authenticate(Request $request): PassportInterface
    {

        $credentials = [
            'email' => $request->request->get('_username'),
            'password' => $request->request->get('_password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];


        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException("Token CSRF Erroné");
        }


        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        if (empty($credentials)) {
            throw new CustomUserMessageAuthenticationException($this->translator->trans("Aucune Donnée n'a été transferé"));
        }

        return new Passport(new UserBadge($credentials['email']),
            new PasswordCredentials($credentials['password']),
            [
                    new CsrfTokenBadge('authenticate', $credentials['csrf_token']),
                    new PasswordUpgradeBadge($credentials['password']),
                    new RememberMeBadge()
            ]);
    }

        public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
        {
        // on success, let the request continue
            return new RedirectResponse($this->router->generate('home'));
        }

        public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
        {
                $data = [
                'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
                ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }
}
?>