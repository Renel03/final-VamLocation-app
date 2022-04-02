<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, [
                'label' => 'Adresse e-mail'
            ])
            //->add('roles')
            ->add('phone', null, [
                'label' => 'Numéro de téléphone'
            ])
            ->add('lastname', null, [
                'label' => 'Nom'
            ])
            ->add('firstname', null, [
                'label' => 'Prenom'
            ])
            ->add('address', null, [
                'label' => 'Adresse'
            ])
            ->add('isActive')
            ->add('city', EntityType::class,[
                'class' => City::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => '-Selectionner-',
            ])
        ;
        if($options['edit'] === true){
            $builder
                ->add('plainPassword', PasswordType::class, [
                    'label' => 'Nouveau mot de passe (Laisser vide pour garder l\'actuel)',
                    'mapped' => false,
                    'required' => false,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'edit' => false
        ]);
    }
}
