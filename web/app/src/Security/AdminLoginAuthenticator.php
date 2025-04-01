<?php

declare(strict_types=1);

namespace App\Security;

use App\Form\AdminLoginForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\AuthenticatorInterface;

final class AdminLoginAuthenticator extends AbstractFormLoginAuthenticator implements AuthenticatorInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * AdminLoginAuthenticator constructor.
     *
     * @param FormFactoryInterface         $formFactory
     * @param RouterInterface              $router
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request): bool
    {
        return 'admin_login' === $request->attributes->get('_route') && $request->isMethod('POST');
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request): array
    {
        $form = $this->formFactory->create(AdminLoginForm::class);
        $form->handleRequest($request);

        $data = $form->getData();
        $request->getSession()->set(Security::LAST_USERNAME, $data['email']);

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        return $userProvider->loadUserByUsername($credentials['email']);
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): RedirectResponse
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        return new RedirectResponse($this->router->generate('admin_login'));
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): RedirectResponse
    {
        return new RedirectResponse($this->router->generate('sonata_admin_dashboard'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getLoginUrl(): string
    {
        return $this->router->generate('admin_login');
    }
}
