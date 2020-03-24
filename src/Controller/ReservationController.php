<?php
//le dossier dans lequel est situé le fichier
namespace App\Controller;
//pour faire appel à un fichier (controller,entity,..) situé dans un autre namespace
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use App\Repository\FormulesRepository;
use App\Repository\VoyageRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface as EMI;
use App\Form\ReservationType;
use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Voyage;

class ReservationController extends AbstractController
{
    //sert à créer une nouvelle reservation par l'utilisateur (gestion requete BDD)
    /**
     * @Route("/reservation/form", name="reservation")
     */
    public function add(EMI $em, Request $request, UserRepository $ur, VoyageRepository $vr){
        
        
        $userAmodifier = $this->getUser();
        $form = $this->createForm(ReservationType::class);
        $form->handleRequest($request);
        //Si la requète est une methode post
        if($request->isMethod("POST")){
            // Récupération des données envoyées par le formulaire
            $reservation = $form->getData();
            $prix = $reservation->getFormule()->getPrix();
            $num_passeport = $form["num_passeport"]->getData();
            $date_expiration_passeport = $form['date_expiration_passeport']->getData();
            // Création d'un objet Reservation avec les données récupérées qui ne sont pas gérées automatiquement par le $form
            $reservation->setPrix($prix);
            $reservation->setUser($userAmodifier);
            $userAmodifier->setNumPasseport($num_passeport);
            $userAmodifier->setDateExpirationPasseport($date_expiration_passeport);
            $reservation->setStatut("en attente");
            // Enregistrement en BDD
            $em->persist($userAmodifier);
            $em->persist($reservation);
            $em->flush();
            return $this->redirectToRoute("home");//redirection vers l'accueil
        }
    
        return $this->render('reservation/index.html.twig', [
                    'registrationForm' => $form->createView()
                ]);
    }

    //fonction pour l'affichage de toutes les réservations (disponible uniquement pour l'admin)
    /**
     * @Route("/admin/reservationlist", name="reservationlist")
     */
    public function list(ReservationRepository $rr){

        $reservations = $rr->findAll();//récupere toute les reservations dans la BDD
        $taille = count($reservations); //comptabilisé le nombre total de reservations
        return $this->render('reservation/liste.html.twig', [ //envoi $reservations et $taille à la vue
            "reservations" => $reservations, "taille" => $taille
        ]);

    }

//fonction pour modifier les réservations existantes
    /**
     * @Route("/admin/reservationupdate/{id}", name="reservation_update")
     */
    public function update(ReservationRepository $rr, Request $rq, EMI $em, int $id)
    {
        
        //recupérer toutes les informations de la reservation selectionnée par son id
        $reservationAmodifier = $rr->find($id);

        if($rq->isMethod("POST")){
            //$rq->request->get(): récupère l'info du formulaire/setPropriete():envoi l'info à la BDD
            $reservationAmodifier->setVoyage($rq->request->get("destination"));
            $reservationAmodifier->setStatut($rq->request->get("statut"));
            $reservationAmodifier->setDateDepart($rq->request->get("date_depart"));
            $reservationAmodifier->setFormule($rq->request->get("formule"));
            $reservationAmodifier->setPrix($rq->request->get("prix"));
            $em->persist($reservationAmodifier);  // équivalent à la création d'une requête UPDATE
            $em->flush();            // exécute la (ou les) requête(s) en attente
            return $this->redirectToRoute("reservationlist");  // redirection vers la route
        }
        return $this->render("reservation/formulaire-admin.html.twig", [ "reservation" => $reservationAmodifier, "mode" => "modifier" ]);

    }

    //fonction pour supprimer une reservation
     /**
     * @Route("/admin/reservationdelete/{id}", name="reservation_delete")
     */
    public function delete(ReservationRepository $rr, Request $rq, EMI $em, int $id)
    {
        $reservationAsupprimer = $rr->find($id);
        if($rq->isMethod("POST")){
            $em->remove($reservationAsupprimer);    // équivalent à la création d'une requête DELETE
            $em->flush();                       // exécute la (ou les) requête(s) en attente
            return $this->redirectToRoute("reservationlist");  // redirection vers la route
        }
        return $this->render("reservation/formulaire-admin.html.twig", [ "reservation" => $reservationAsupprimer, "mode" => "confirmer" ]);
    }
    
    //fonction pour afficher la liste des réservations de l'utilisateur connecté
    /**
     * @Route("/reservationlist", name="reservationlistuser")
     */
    public function listUser(ReservationRepository $rr){

        $user = $this->getUser();//pour recupérer les infos de l'utilisateur connecté
        $reservationUser = $user->getReservations();
        $taille = count($reservationUser); 
        return $this->render('reservation/liste-user.html.twig', [
            "reservations" => $reservationUser, "taille" => $taille
        ]);

    }

    //fonction pour afficher le prix en fonction de la formule sélectionnée(requète ajax)
    /**
     * @Route("/prix-reservation/{id}", name="prix_reservation")
     */
    public function prixReservation(FormulesRepository $fr, $id)
    {
        $formule = $fr->find($id);//recupère les infos de la formule selectionée
        $prix = $formule->getPrix();//recupère le prix de la formule selectionée
        return $this->json([ "prix" => $prix ]);//envoi le prix en json
    }

}