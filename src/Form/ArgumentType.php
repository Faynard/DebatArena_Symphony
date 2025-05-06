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
            ->add('camp', EntityType::class, [
                'class' => Camp::class,
                'choice_label' => 'nameCamp',
                'choices' => $options['camps'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Argument::class,
            'camps' => ["Camp 1", "Camp 2"],
        ]);

        $resolver->setRequired(['camps']);
    }
}
