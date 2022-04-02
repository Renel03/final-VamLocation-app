<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, [
                'label' => 'Adresse e-mail'
            ])
            ->add('phone', null, [
                'label' => 'Numéro de téléphone'
            ])
            ->add('lastname', null, [
                'label' => 'Nom'
            ])
            ->add('firstname', null, [
                'label' => 'Prénom'
            ])
            ->add('companyName', null, [
                'label' => 'Entreprise'
            ])
            ->add('address', null, [
                'label' => 'Adresse'
            ])
            ->add('nif', null, [
                'label' => 'NIF'
            ])
            ->add('stat', null, [
                'label' => 'STAT'
            ])
            ->add('rcs', null, [
                'label' => 'RCS'
            ])
            ->add('isActive')
            ->add('isIdentityVerified')
            ->add('isNifVerified')
            ->add('isStatVerified')
            ->add('isVerified')
            ->add('businessType', ChoiceType::class, [
                "label" => "Type d'activités",
                "choices" => [
                    "Location de voiture" => 1,
                    "Vente d'auto" => 2,
                    "Vente et Location d'auto" => 3
                ]
            ])
            ->add('about', null, [
                'label' => 'À propos'
            ])
            ->add('city', EntityType::class,[
                'class' => City::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => '-selectionner-',
            ])
            // ->add('subscription')
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
