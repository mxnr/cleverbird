<?php

namespace CleverBirdBundle\Controller;

use CleverBirdBundle\Entity\User;
use CleverBirdBundle\Form\Type\UserType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $this->authenticateUser($user);

            $this->save($user);

            return $this->redirectToRoute('clever_bird_homepage');
        }

        return $this->render(
            '@CleverBird/Security/register.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            '@CleverBird/Security/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
            ]
        );
    }

    /**
     * @param User $user
     */
    private function authenticateUser(User $user)
    {
        $providerKey = 'default';
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());

        $this->container->get('security.token_storage')->setToken($token);
    }

    /**
     * This controller will not be executed, as the route is handled by the Security system.
     */
    public function loginCheckAction()
    {
    }
}
