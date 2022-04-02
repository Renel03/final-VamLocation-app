<?php

namespace App\Form;

use App\Entity\Demand;
use App\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['step'] == 1){
            $builder
                ->add('tripType', ChoiceType::class,[
                    'choices' => [
                        'Aller simple' => 1,
                        'Aller-retour' => 2,
                        'Circuit' => 3
                    ]
                ])
                ->add('checkInAt', DateType::class, [
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'html5' => false,
                ])
                ->add('checkOutAt', DateType::class, [
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'html5' => false,
                ])
                ->add('nbPers', null, [])
                ->add('fromCity', EntityType::class, [
                    'class' => City::class,
                    'choice_label' => 'name',
                ])
                ->add('toCity', EntityType::class, [
                    'class' => City::class,
                    'choice_label' => 'name',
                    'required' => false,
                ])
            ;
        }elseif($options['step'] == 2){
            $builder
                ->add('tripType3Cities', EntityType::class, [
                    'class' => City::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'required' => false
                ])
                ->add('lastname', null , [
                    'mapped' => false,
                    'constraints' => [
                        new Length([
                            'min' => 2,
                        ]),
                    ],
                ])
                ->add('firstname', null , [
                    'mapped' => false,
                    'constraints' => [
                        new Length([
                            'min' => 2,
                        ]),
                    ],
                ])
                ->add('phone', null , [
                    'mapped' => false,
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^(\+)?[0-9]{7,}$/',
                            'message' => 'Numéro de téléphone invalide'
                        ]),
                    ],
                ])
                ->add('isWithDriver', null , [])
                ->add('luggage', null, [])
                ->add('reason', null , [])
                ->add('description', null , [])

                ->add('isNewUser', null , ['mapped' => false,])
                ->add('isAuth', null , ['mapped' => false,])
                
                ->add('email', null , ['mapped' => false,])
                ->add('plainPassword', null , [
                    'mapped' => false,
                    'constraints' => [
                        // new NotBlank([
                        //     'message' => 'Veuillez saisir votre mot de passe s\'il vous plaît',
                        // ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Un mot de passe doit contenir au moins {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Demand::class,
            'step' => null
        ]);
    }
}
