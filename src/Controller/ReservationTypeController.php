<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\VoyageRepository;
use App\Entity\Reservation;
use App\Form\UserType;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface as EMI;
use App\Security\RegistrationFormTypeAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class ReservationTypeController extends AbstractController
{
    /**
     * @Route("/reservation", name="reservation")
     */
    public function reservation(Request $request, EMI $em,VoyageRepository $voyageRepo, UserRepository $ur, GuardAuthenticatorHandler $guardHandler, RegistrationFormTypeAuthenticator $authenticator): Response
    {
        $reservation = new Reservation();
        // $userAmodifier = $ur->find($id);
        $userAmodifier = $this->getUser();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        $voyages = $voyageRepo->findAll();
        
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // $nouveauLivre = $formLivre->getData();
                $em->persist($userAmodifier);
                $em->flush();
                $this->addFlash("success", "Votre reservation est enregistrée");
                return $this->redirectToRoute("home");
            }
            else {
                $this->addFlash("danger", "Le formulaire n'est pas valide");
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $reservation,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );

            
        }

        return $this->render('reservation_type/formulaire.html.twig', [
            compact("voyages"), 'registrationForm' => $form->createView()
        ]);
    }
}
