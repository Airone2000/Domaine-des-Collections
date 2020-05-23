<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserEmailAddress;
use App\Form\RegistrationFormType;
use App\Security\UsernamePasswordAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


/**
 * @Route(name="security_")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
             return $this->redirectToRoute('app_homepage');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UsernamePasswordAuthenticator $authenticator): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_homepage');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user, [
            'validation_groups' => 'User:Register'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main'
            );
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route(path="/activate-email/{id}/{verificationToken}", methods={"GET"}, name="activate_email")
     * @ParamConverter(name="userEmailAddress", options={"mapping": {"id": "id", "verificationToken": "verificationToken"}})
     */
    public function activateEmail(UserEmailAddress $userEmailAddress): Response
    {
        $justVerified = false;
        if (false === $userEmailAddress->isVerified()) {
            $userEmailAddress
                ->setVerified(true)
                ->setVerifiedAt(new \DateTime())
            ;
            $this->getDoctrine()->getManager()->flush($userEmailAddress);
            $justVerified = true;
        }

        return $this->render('security/activate-email.html.twig', [
            'justVerified' => $justVerified,
            'email' => $userEmailAddress
        ]);
    }
}
