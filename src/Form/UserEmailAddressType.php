<?php

namespace App\Form;

use App\Entity\UserEmailAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEmailAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => false
            ])
        ;

        $displayTogglers = $options['display_togglers'];
        if ($displayTogglers) {
            $builder
                ->add('newsLetterAccepted', CheckboxType::class, [
                    'required' => false
                ])
            ;
        }

        $displayHistoryToggler = $options['display_history_toggler'];
        if ($displayHistoryToggler) {
            $builder
                ->add('historyOfVerifiedEmailAddressesKept', CheckboxType::class, [
                    'required' => false
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserEmailAddress::class,
            'display_togglers' => false,
            'display_history_toggler' => false,
        ]);
    }
}
