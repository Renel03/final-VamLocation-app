<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Trademark;
use App\Entity\Model;
use App\Entity\Version;
use App\Form\CarPhotoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('trademark',EntityType::class,[
                'class' => Trademark::class,
                'label' => 'Marque',
                'placeholder' => '',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er  ->createQueryBuilder('t')
                                ->where('t.type = 1')
                                ->orderBy('t.name', 'ASC');
                },
                // 'group_by' => function($trademark) {
                //     return $trademark->getType() == 1 ? 'Voiture' : ($trademark->getType() == 2 ? 'Moto' : 'Utilitaire');
                // },
            ])
            ->add('model',EntityType::class,[
                'class' => Model::class,
                'label' => 'Modèle',
                'placeholder' => '',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er  ->createQueryBuilder('m')
                                ->join('m.trademark', 't')
                                ->where('t.type = 1')
                                ->orderBy('m.name', 'ASC');
                },
                'group_by' => function($model) {
                    return $model->getTrademark()->getName();
                },
                'required' => false,
                ])
            ->add('version',EntityType::class,[
                'class' => Version::class,
                'label' => 'Version',
                'placeholder' => '',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er  ->createQueryBuilder('v')
                                ->join('v.model', 'm')
                                ->join('m.trademark', 't')
                                ->where('t.type = 1')
                                ->orderBy('v.name', 'ASC');
                },
                'group_by' => function($version) {
                    return $version->getModel()->getTrademark()->getName() . ' > ' . $version->getModel()->getName();
                }, 
                'required' => false,
            ])
            ->add('num', null, [
                'label' => 'N° matricule'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description en quelques mots',
                'attr' => [
                    'maxlength' => 255
                ],
                'required' => false
            ])
            ->add('file', null, [
                'label' => 'Pièce jointe'
            ])
            ->add('nbPlace', null, [
                'label' => 'Nombre de places'
            ])
            ->add('isVerified')
            ->add('photos',CollectionType::class,[
                'entry_type' => CarPhotoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
                'required' => true,
                'empty_data' => null,
                'label' => false,
            ])
            // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
