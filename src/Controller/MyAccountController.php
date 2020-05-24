<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserEmailAddress;
use App\Form\UserEmailAddressType;
use App\Form\UserProfileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
            $em = $this->getDoctrine()->getManager();
            /* @var UserEmailAddress $userEmailAddress */
            $em->flush($userEmailAddress);
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
            return $this->redirectToRoute('myaccount_personal_data');
        }
        return $this->render('myaccount/personal-data.html.twig', [
            'form' => $form->createView()
        ]);
    }
}