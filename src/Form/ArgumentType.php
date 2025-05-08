<?php

namespace App\Form;

use App\Entity\Argument;
use App\Entity\Camp;
use App\Entity\User;
use App\Repository\CampRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArgumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextareaType::class)
            ->add('camp', EntityType::class, [
                'class' => Camp::class,
                'choice_label' => 'nameCamp',
                'query_builder' => function (CampRepository $repo) use ($options) {
                    return $repo->createQueryBuilder('c')
                        ->where('c.debate = :debate')
                        ->setParameter('debate', $options['debate']);
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Argument::class,
            'debate' => null,
        ]);
    }
}