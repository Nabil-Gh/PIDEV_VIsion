<?php

namespace App\Form;

use App\Entity\Consultation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adresse',TextType::class)
            ->add('typec',TextType::class)
            ->add('datec',DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable'
            ])
            ->add('timec', TimeType::class, [
                'input'  => 'string',
                'widget' => 'single_text',
                
            ])
            ->add('email',EmailType::class)
            ->add('fname',TextType::class)
            ->add('lname',TextType::class)
            ->add('phone',NumberType::class)
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
