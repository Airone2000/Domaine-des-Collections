<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserEmailAddress;
use App\Form\ChangePasswordType;
use App\Form\UserEmailAddressType;
use App\Form\UserProfileType;
use App\Mailer\TemplatedEmail;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route(path="/my-account", name="myaccount_")
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class MyAccountController extends AbstractController
{
    /**
     * @Route(path="/", name="home")
     */
    public function home(): Response
    {
        return $this->render('myaccount/home.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route(path="/notification-preferences", name="notification_preferences")
     */
    public function notificationPreferences(Request $request): Response
    {
        /* @var User $user */
        $user = $this->getUser();
        $userEmailAddress = $user->getEmailAddress();

        $form = $this->createForm(UserEmailAddressType::class, $userEmailAddress, [
            'display_togglers' => null !== $userEmailAddress->getEmail(),
            'display_history_toggler' => true,
            'validation_groups' => 'UserEmailAddress:Update',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notificationPreferencesSuccess', 'Vos préférences de notification ont été modifiées.');
            return $this->redirectToRoute('myaccount_notification_preferences');
        }

        return $this->render('myaccount/notification-preferences.html.twig', [
            'email' =>  $userEmailAddress,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/personal-data", name="personal_data")
     */
    public function personalData(Request $request): Response
    {
        /* @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('personalDataSuccess', 'Vos données personnelles ont été mises à jour.');
            return $this->redirectToRoute('myaccount_personal_data');
        }
        return $this->render('myaccount/personal-data.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/change-password", name="change_password")
     */
    public function changePassword(Request $request,
                                   UserPasswordEncoderInterface $passwordEncoder,
                                   MailerInterface $mailer): Response
    {
        $form = $this->createForm(ChangePasswordType::class, null, [
            'proposeToReceiveNewPasswordByEmail' => true
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /* @var User $user */
            $user = $this->getUser();
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('changePasswordSuccess', 'Votre mot de passe a été mis à jour.');

            if ($user->getEmailAddress()->isVerified() && $form->get('newPasswordByEmail')->getData()) {
                $email = new TemplatedEmail();
                $email
                    ->to(new Address( $user->getEmailAddress()->getEmail(), $user->getUsername() ))
                    ->subject('Domaine des Collections : Votre nouveau mot de passe')
                    ->htmlTemplate('emails/new-password.html.twig')
                    ->context(['newPassword' => $form->get('plainPassword')->getData()])
                ;
                $mailer->send($email);
                $this->addFlash('changePasswordSuccess', 'Nous vous avons envoyé un e-mail contenant votre nouveau mot de passe.');
            }

            return $this->redirectToRoute('myaccount_change_password');
        }
        return $this->render('myaccount/change-password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}