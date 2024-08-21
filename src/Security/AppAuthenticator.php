<?php

namespace App\Security;

use App\Entity\Pemploye;
use App\Entity\LContract;
use App\Repository\UserRepository;
use App\Entity\UserOperationDossier;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $passwordHasher;
    private $urlGenerator;
    private $userRepository;
    private $doctrine;

    public function __construct(UrlGeneratorInterface $urlGenerator, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository, ManagerRegistry $doctrine)
    {
        $this->urlGenerator = $urlGenerator;
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
        $this->doctrine = $doctrine;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email, function ($userIdentifier) {
                $user = $this->userRepository->findOneBy(['email' => $userIdentifier]);
                if (!$user) {

                    throw new CustomUserMessageAuthenticationException("Email introuvable!");
                }
                return $user;
            }),
            new CustomCredentials(function ($credentials, $user) {
                if (!$this->passwordHasher->isPasswordValid($user, $credentials)) {
                    throw new CustomUserMessageAuthenticationException("Votre mot de passe est incorrect!");
                }

                if (!$user->isVerified()) {
                    throw new CustomUserMessageAuthenticationException("Please Confirm your Email");
                }


                return true;
            }, $password),
            [
                new RememberMeBadge(),
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Redirect to a default route after successful authentication
        return new RedirectResponse($this->urlGenerator->generate('default_route'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
