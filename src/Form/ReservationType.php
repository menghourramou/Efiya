<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Voyage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ReservationType extends AbstractType
{
    // Fonction pour construction formulaire reservation
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('statut')
            ->add('date_depart', DateType::class, ["widget" => "single_text"])
            ->add('formule', ChoiceType::class, ["choices" => ["Basic 7 jours" => true, "Gold 15 jours" => true]])
            ->add('prix')
            ->add('voyage', EntityType::class, ["class" => Voyage::class, "choice_label" => function(Voyage $voyage){return $voyage->getDestination();}] )
            // ->add('num_passeport')
            // ->add('date_expiration_passeport', DateType::class, ["widget" => "single_text"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
