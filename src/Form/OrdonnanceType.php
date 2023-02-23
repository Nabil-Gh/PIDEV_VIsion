<?php

namespace App\Form;

use App\Entity\Consultation;
use App\Entity\Ordonnance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class OrdonnanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nompatient')
            ->add('nommedecin')
            ->add('description')
            ->add('medicament')
            ->add('dateo',DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable'
            ])
            ->add('consultation',EntityType::class,[
                     'class'=>Consultation::class,
                     'choice_label'=>'id', 
                     
                 ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ordonnance::class,
        ]);
    }
}
