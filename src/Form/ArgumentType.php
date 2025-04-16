<?php

namespace App\Form;

use App\Entity\Argument;
use App\Entity\Camp;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArgumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text')
            ->add('validationDate', null, [
                'widget' => 'single_text',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('userValidate', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('camp', EntityType::class, [
                'class' => Camp::class,
                'choice_label' => 'id',
            ])
            ->add('mainArgument', EntityType::class, [
                'class' => Argument::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Argument::class,
        ]);
    }
}
