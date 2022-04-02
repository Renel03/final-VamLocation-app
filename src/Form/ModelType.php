<?php

namespace App\Form;

use App\Entity\Model;
use App\Entity\Trademark;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('trademark',EntityType::class,[
                'class' => Trademark::class,
                'label' => 'Marque',
                'placeholder' => '',
                'choice_label' => 'name',
                'group_by' => function($trademark) {
                    return $trademark->getType() == 1 ? 'Voiture' : ($trademark->getType() == 2 ? 'Moto' : 'Utilitaire');
                },
            ])
            ->add('name', null, [
                'label' => 'Nom de la modèle'
            ])
            ->add('status')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Model::class,
        ]);
    }
}
