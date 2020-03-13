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

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('statut')
            ->add('date_depart', DateType::class, ["widget" => "single_text"])
            ->add('formule')
            ->add('prix')
            ->add('voyage', ChoiceType::class, [ 'choices' => ['Egypte', 'Nevada', 'Jordanie', 'Ethiopie']] )
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
