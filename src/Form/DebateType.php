<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Debate;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DebateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nameDebate')
            ->add('descriptionDebate')
            ->add('isValid')
            ->add('creationDate', null, [
                'widget' => 'single_text',
            ])
            ->add('userCreated', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Debate::class,
        ]);
    }
}
