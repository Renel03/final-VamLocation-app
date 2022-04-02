<?php

namespace App\Form;

use App\Entity\Demand;
use App\Entity\City;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DemandTestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tripType')
            ->add('checkInAt')
            ->add('checkOutAt')
            ->add('nbPers')
            ->add('description')
            ->add('isWithDriver')
            ->add('status')
            ->add('createdAt')
            ->add('luggage')
            ->add('reason')
            ->add('fromCity', EntityType::class,[
                'class' => City::class,
                'choice_label' => 'name'
            ])
            ->add('toCity', EntityType::class,[
                'class' => City::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => '',
            ])
            ->add('user', EntityType::class,[
                'class' => User::class,
                'choice_label' => 'email'
            ])
            ->add('tripType3Cities', EntityType::class,[
                'class' => City::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Demand::class,
        ]);
    }
}
