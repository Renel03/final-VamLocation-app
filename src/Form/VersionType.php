<?php

namespace App\Form;

use App\Entity\Version;
use App\Entity\Model;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class VersionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model',EntityType::class,[
                'class' => Model::class,
                'label' => 'Marque',
                'placeholder' => '',
                'choice_label' => 'name',
                'group_by' => function($model) {
                    return ($model->getTrademark()->getType() == 1 ? 'Voiture' : ($model->getTrademark()->getType() == 2 ? 'Moto' : 'Utilitaire')) . ' > ' . $model->getTrademark()->getName();
                }, 
            ])
            ->add('name', null, [
                'label' => 'Nom de la version'
            ])
            ->add('status')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Version::class,
        ]);
    }
}
