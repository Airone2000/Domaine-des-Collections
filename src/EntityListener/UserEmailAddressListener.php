<?php

namespace App\EntityListener;

use App\Entity\UserEmailAddress;
use App\Mailer\TemplatedEmail;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class UserEmailAddressListener
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function preUpdate(UserEmailAddress $userEmailAddress, PreUpdateEventArgs $preUpdateEventArgs): void
    {
        $changeSet = $preUpdateEventArgs->getEntityChangeSet();

        if (array_key_exists('email', $changeSet)) {
            $userEmailAddress
                ->setVerifiedAt(null)
                ->setVerified(false)
                ->renewVerificationToken()
            ;

            // Question ? Is this feature a good feature ?
            // Am I right to store an history of email ?
            // Maybe ... or not. Let the user has total control over this
            // by giving him the ability to clear history of verified email
            // See this logic in action further in this class

            // the email has been changed for another one which is not null
            if (null !== $userEmailAddress->getEmail()) {
                // If this email has already been linked to this account once and verified
                // we mark it as verified right now so that the user is not required to go to
                // his mailbox ... etc.
                $verifiedEmailsHistory = $userEmailAddress->getHistoryOfVerifiedEmailAddresses();
                if (isset($verifiedEmailsHistory[$userEmailAddress->getEmail()])) {
                    $userEmailAddress
                        ->setVerifiedAt($verifiedEmailsHistory[$userEmailAddress->getEmail()])
                        ->setVerified(true)
                    ;
                } else {
                    // Cannot verify the email automatically based on history
                    // so we ask the user for going to his mailbox and click the button in the mail.
                    $user = $userEmailAddress->getUser();
                    $email = new TemplatedEmail();
                    $email
                        ->to(new Address($user->getEmailAddress()->getEmail(), $user->getUsername()))
                        ->subject('Domaine des Collections : Nouvelle adresse à activer')
                        ->htmlTemplate('emails/activate-email.html.twig')
                        ->context(['user' => $user]);
                    $this->mailer->send($email);
                }
            }
        }

        // As seen previously, let the user has total control over his history of verified email addresses
        if (array_key_exists('historyOfVerifiedEmailAddressesKept', $changeSet)) {
            if (false === $userEmailAddress->isHistoryOfVerifiedEmailAddressesKept()) {
                $userEmailAddress->setHistoryOfVerifiedEmailAddresses([]);
            } else {
                if ($userEmailAddress->getEmail() && $userEmailAddress->isVerified()) {
                    $historyOfVerifiedEmailAddresses = [];
                    $historyOfVerifiedEmailAddresses[$userEmailAddress->getEmail()] = $userEmailAddress->getVerifiedAt();
                    $userEmailAddress->setHistoryOfVerifiedEmailAddresses($historyOfVerifiedEmailAddresses);
                }
            }
        }
    }
}