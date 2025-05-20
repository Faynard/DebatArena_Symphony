<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', null, [
                'label' => "{{ 'user.pseudo'|trans }} *",
                'attr' => ['placeholder' => "{{ 'user.pseudo'|trans }}"],
            ])
            ->add('email', EmailType::class, [
                'label' => "{ 'user.email'|trans }} *",
                'attr' => ['placeholder' => "{{ 'user.email'|trans }}"],
            ])
            ->add('password', PasswordType::class, [
                'label' => "{{ 'user.password'|trans }} *",
                'attr' => ['placeholder' => "{{ 'user.password'|trans }}"],
            ]);
            // ->add('createdDate', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('isBanned')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
