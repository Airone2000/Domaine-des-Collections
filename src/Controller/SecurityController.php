<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserEmailAddress;
use App\Form\RegistrationFormType;
use App\Mailer\Email;
use App\Mailer\TemplatedEmail;
use App\Security\UsernamePasswordAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
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
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // If requesting with ajax, if no longer connected, redirect to login
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'loginURL' => $this->generateUrl('security_login', [], UrlGenerator::ABSOLUTE_URL),
            ], Response::HTTP_UNAUTHORIZED);
        }

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
    public function register(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             GuardAuthenticatorHandler $guardHandler,
                             UsernamePasswordAuthenticator $authenticator,
                             MailerInterface $mailer): Response
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

            if (null !== $user->getEmailAddress()->getEmail()) {
                $email = new TemplatedEmail();
                $email
                    ->to(new Address( $user->getEmailAddress()->getEmail(), $user->getUsername() ))
                    ->subject('Domaine des Collections : Bienvenue '.$user->getUsername().' !')
                    ->htmlTemplate('emails/welcome.html.twig')
                    ->context(['user' => $user])
                ;
                $mailer->send($email);
            }

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
            # Keep history of verified email addresses based on user preferences
            if ($userEmailAddress->isHistoryOfVerifiedEmailAddressesKept()) {
                $historyOfVerifiedEmailAddresses = $userEmailAddress->getHistoryOfVerifiedEmailAddresses();
                $historyOfVerifiedEmailAddresses[$userEmailAddress->getEmail()] = $userEmailAddress->getVerifiedAt();
                $userEmailAddress->setHistoryOfVerifiedEmailAddresses($historyOfVerifiedEmailAddresses);
            }
            $this->getDoctrine()->getManager()->flush($userEmailAddress);
            $justVerified = true;
        }

        return $this->render('security/activate-email.html.twig', [
            'justVerified' => $justVerified,
            'email' => $userEmailAddress
        ]);
    }
}
