<?php

namespace App\Form;

use App\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de la ville'
            ])
            ->add('cp', null, [
                'label' => 'Code postal'
            ])
            ->add('longitude', null, [
                'label' => 'Longitude'
            ])
            ->add('latitude', null, [
                'label' => 'Latitude'
            ])
            ->add('description', null, [
                'label' => 'Parlez de cette ville en quelques mots'
            ])
            ->add('file', null, [
                'label' => 'Photo'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => City::class,
            'edit' => false
        ]);
    }
}
