<?php

namespace App\Form;

use App\Entity\RendezVous;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints as Assert;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class UpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date_rv',DateType::class, [
            'widget' => 'single_text',
            'input' => 'datetime_immutable',
            'constraints' => [
                new Assert\GreaterThan('today',message:"choissisez une date valide")]
        ])
          
        ->add('heure_rv', TimeType::class, [
            'input'  => 'string',
            'widget' => 'single_text',
        ])
            ->add('type_rv', ChoiceType::class, [
                'choices'  => [
                   'En ligne' => 'En Ligne',
                   'Au cabinet' => 'Au cabinet ',
            ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
        ]);
    }
}
