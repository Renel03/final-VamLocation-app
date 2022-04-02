<?php

namespace App\Form;

use App\Entity\Demand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandType extends AbstractType
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
            ->add('fromCity')
            ->add('toCity')
            ->add('user')
            ->add('tripType3Cities')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Demand::class,
        ]);
    }
}
