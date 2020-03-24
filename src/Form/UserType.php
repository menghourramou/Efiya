<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email') // (clé sql : UNIQ_8D93D649E7927C74)
            // ->add('roles')
            ->add('nom')
            ->add('prenom')
            // utilisation de Datetype pour le format affichage de la date
            ->add('date_naissance', DateType::class, ["widget" => "single_text"])
            ->add('nationalite')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [ // 'constraints' sert a appliquer des restrictions
                    new IsTrue([ // Si la case des CGV n'est pas cochée on renvoie le msg :
                        'message' => 'Vous devez accepter nos conditions générales.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([ // Si le mdp est vide on renvoie le msg : 
                        'message' => 'Veuillez saisir un mot de passe',
                    ]),
                    new Length([
                        'min' => 6, // Si le mdp fait moins de 6 caractères on renvoie le msg :
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            // ->add('num_passeport')
            // ->add('date_expiration_passeport')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
