<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Voyage;
use App\Entity\Formules;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ReservationType extends AbstractType
{
    // Fonction pour construction formulaire reservation
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('statut')
            ->add('date_depart', DateType::class, ["widget" => "single_text"])
            ->add('formule', EntityType::class, ["class" => Formules::class, "choice_label" => function(Formules $formules){return $formules->getFormule();}] )//EntityType::class sert à faire appel à une entity differente de celle de base du formulaire( ici l'entity de base c'est réservation et on appel entity Formules)
            ->add('prix', TextType::class, [ "mapped" => false, "required" => false, "attr" =>["readonly" => true]])// "mapped"=>false indique que la donnée 'prix' ne doit pas être récupérée car elle est déjà générée automatiquement par la fonction au-dessus // "required" => false indique que la donnée n'est pas indispensable // "attr"=>["readonly"=>true] empêche l'utilisateur de modifier la donnée sur le formulaire (il peut juste la lire)
            ->add('voyage', EntityType::class, ["class" => Voyage::class, "choice_label" => function(Voyage $voyage){return $voyage->getDestination();}] )
            ->add('num_passeport', TextType::class, [ "mapped" => false ]) // "mapped" => false ici indique toujours que la donnée ne doit pas être récupérée, mais cette fois parce qu'elle est gérer par le ReservationController
            ->add('date_expiration_passeport', DateType::class, ["widget" => "single_text",  "mapped" => false])// "mapped" => false ici indique toujours que la donnée ne doit pas être récupérée, mais cette fois parce qu'elle est gérer par le ReservationController
        ;
    }
    // "attr" => ["readonly" => true]
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
