<?php

namespace App\Form;

use App\Entity\Trademark;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TrademarkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Voiture' => 1,
                    'Moto' => 2,
                    'Utilitaire' => 3,
                ],
                'placeholder' => ''
            ])
            ->add('name', null, [
                'label' => 'Nom de la marque'
            ])
            ->add('file', null, [
                'label' => 'Logo'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description en quelques mots',
                'attr' => [
                    'maxlength' => 255
                ],
                'required' => false
            ])
            ->add('status')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trademark::class,
        ]);
    }
}
