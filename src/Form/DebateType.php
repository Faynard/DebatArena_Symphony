<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Debate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DebateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nameDebate', TextType::class, [
                'label' => 'Nom du débat',
            ])
            ->add('descriptionDebate', TextareaType::class, [
                'label' => 'Description du débat',
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'nameCategory',
                'multiple' => true,
                'expanded' => false,
                'placeholder' => 'Sélectionnez des catégories',
                'attr' => ['class' => 'tom-select'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Debate::class,
        ]);
    }
}
