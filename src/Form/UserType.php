<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('pseudo')
            ->add('password')
            // ->add('createdDate', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('isBanned')
        ;
    }

    public function registerForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', null, [
                'label' => 'Pseudo *',
                'attr' => ['placeholder' => 'Pseudo'],
            ])
            ->add('email', null, [
                'label' => 'Adresse email *',
                'attr' => ['placeholder' => 'Adresse email'],
            ])
            ->add('password', null, [
                'label' => 'Mot de passe *',
                'attr' => ['placeholder' => 'Mot de passe'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
