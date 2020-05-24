<?php

namespace App\Form;

use App\Entity\UserBirthdate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserBirthdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $yearNow = (int)(new \DateTime())->format('Y');
        $builder
            ->add('value', BirthdayType::class, [
                'format' => 'd/M/y',
                'required' => false,
                'label' => false
            ])
            ->add('visible')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserBirthdate::class,
        ]);
    }
}
