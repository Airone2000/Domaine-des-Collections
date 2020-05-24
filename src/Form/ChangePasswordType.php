<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'constraints' => [
                    new NotBlank(),
                    new UserPassword([
                        'message' => 'Please enter your current password'
                    ]),
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096
                    ])
                ]
            ])
        ;

        $proposeToReceiveNewPasswordByEmail = $options['proposeToReceiveNewPasswordByEmail'];
        if ($proposeToReceiveNewPasswordByEmail) {
            $builder
                ->add('newPasswordByEmail', CheckboxType::class, [
                    'required' => false
                ])
            ;
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'proposeToReceiveNewPasswordByEmail' => false,
        ]);
    }
}
