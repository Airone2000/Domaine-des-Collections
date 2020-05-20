<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserEmailAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('enableEmailNotifications', CheckboxType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('emailAddress', UserEmailAddressType::class, [
                'label' => false
            ])
        ;

        // UserEmailAddressType returns an instance of UserEmailAddress due to configureOption > resolve > setDefault > data_class
        // If UserEmailAddress::email is NULL, then do not save the object
        $builder
            ->get('emailAddress')->addModelTransformer(new CallbackTransformer(
                fn(?UserEmailAddress $emailAddress) => $emailAddress,
                fn(UserEmailAddress $emailAddress) => is_null($emailAddress->getEmail()) ? null : $emailAddress
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
