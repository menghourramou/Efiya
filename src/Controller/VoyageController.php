<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VoyageRepository;
use App\Repository\FormulesRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface as EMI;
use App\Entity\Formules;
use App\Entity\Voyage;

class VoyageController extends AbstractController
{
    //fonction pour route vers la vue de l'accueil
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('voyage/index.html.twig');
    }

    //fonction pour gerer la route pour toutes les vues destination(sahara, egypte, ethiopie, jordanie)
   /**
     * @Route("/{pays}", name="destination")
     */
    public function destination($pays)
    {
        return $this->render('voyage/'. $pays .'.html.twig');
    }

    //fonction pour afficher l'ensemble des formules et voyages (uniquement pour l'admnin)
    /**
     * @Route("/admin/formulelist", name="formulelist")
     */
    public function list(FormulesRepository $fr, VoyageRepository $vr){

        $formules = $fr->findAll();
        $voyages = $vr->findAll(); 
        return $this->render('voyage/liste.html.twig', [
            "formules" => $formules, "voyages" => $voyages, 
        ]);

    }
    //fonction pour créer une nouvelle formule
    /**
     * @Route("/admin/ajouter-formule", name="formule_add")
     */
    public function add(EMI $em, Request $request){
        // L'objet $request de la classe Request contient, entre autres, 2 propriétés :
        //  ->query      : permet de récupérer ce qui se trouve dans $_GET
        //  ->request    : permet de récupérer ce qui se trouve dans $_POST

     

        if($request->isMethod("POST") ){
            $formule = $request->request->get("formule");           // $formule= $_POST["formule"]
            $prix = $request->request->get("prix");   // $prix = $_POST["prix"]
            $formules = new Formules;
            $formules->setFormule($formule);
            $formules->setPrix($prix);
            $em->persist($formules);  // équivalent à la création d'une requête INSERT INTO
            $em->flush();            // exécute la (ou les) requête(s) en attente
            return $this->redirectToRoute("formulelist");  // redirection vers la route
        }

       
       
        return $this->render("voyage/formulaire-formule.html.twig", ["mode" => "ajouter" ]);
    }

    //fonction pour modifier une formule(uniquement pour l'admin)
     /**
     * @Route("/admin/formuleupdate/{id}", name="formule_update")
     */
    public function update(FormulesRepository $fr, Request $rq, EMI $em, int $id)
    {
    
        $formuleAmodifier = $fr->find($id);
        if($rq->isMethod("POST")){
            $formuleAmodifier->setFormule($rq->request->get("formule"));
            $formuleAmodifier->setPrix($rq->request->get("prix"));
            $em->persist($formuleAmodifier);  // équivalent à la création d'une requête UPDATE
            $em->flush();            // exécute la (ou les) requête(s) en attente
            return $this->redirectToRoute("formulelist");  // redirection vers la route
        }
        return $this->render("voyage/formulaire-formule.html.twig", [ "formule" => $formuleAmodifier, "mode" => "modifier" ]);

    }
     //fonction pour supprimer une formule(uniquement pour l'admin)
    /**
     * @Route("/admin/formuledelete/{id}", name="formule_delete")
     */
    public function delete(FormulesRepository $fr, Request $rq, EMI $em, int $id)
    {
        $formuleAsupprimer = $fr->find($id);
        if($rq->isMethod("POST")){
            $em->remove($formuleAsupprimer);    // équivalent à la création d'une requête DELETE
            $em->flush();                       // exécute la (ou les) requête(s) en attente
            return $this->redirectToRoute("formulelist");  // redirection vers la route
        }
        return $this->render("voyage/formulaire-formule.html.twig", [ "formule" => $formuleAsupprimer, "mode" => "confirmer" ]);
    }

    //fonction pour modifier un voyage(uniquement pour l'admin)
    /**
     * @Route("/admin/voyageupdate/{id}", name="voyage_update")
     */
    public function updateVoyage(VoyageRepository $vr, Request $rq, EMI $em, int $id)
    {
    
        $voyageAmodifier = $vr->find($id);
        if($rq->isMethod("POST")){
            $voyageAmodifier->setDestination($rq->request->get("destination"));
            $em->persist($voyageAmodifier);  // équivalent à la création d'une requête UPDATE
            $em->flush();            // exécute la (ou les) requête(s) en attente
            return $this->redirectToRoute("formulelist");  // redirection vers la route
        }
        return $this->render("voyage/formulaire-voyage.html.twig", [ "voyage" => $voyageAmodifier, "mode" => "modifier" ]);

    }
//fonction pour supprimer un voyage(uniquement pour l'admin)
    /**
     * @Route("/admin/voyagedelete/{id}", name="voyage_delete")
     */
    public function deleteVoyage(VoyageRepository $vr, Request $rq, EMI $em, int $id)
    {
        $voyageAsupprimer = $vr->find($id);
        if($rq->isMethod("POST")){
            $em->remove($voyageAsupprimer);    // équivalent à la création d'une requête DELETE
            $em->flush();                       // exécute la (ou les) requête(s) en attente
            return $this->redirectToRoute("formulelist");  // redirection vers la route
        }
        return $this->render("voyage/formulaire-voyage.html.twig", [ "voyage" => $voyageAsupprimer, "mode" => "confirmer" ]);
    }
    //fonction pour ajouter un voyage(uniquement pour l'admin)
    /**
     * @Route("/admin/ajouter-voyage", name="voyage_add")
     */
    public function addVoyage(EMI $em, Request $request){
        // L'objet $request de la classe Request contient, entre autres, 2 propriétés :
        //  ->query      : permet de récupérer ce qui se trouve dans $_GET
        //  ->request    : permet de récupérer ce qui se trouve dans $_POST

     

        if($request->isMethod("POST") ){
            $voyage = $request->request->get("destination");           // $voyage= $_POST["destination"]
            $voyages = new Voyage;
            $voyages->setDestination($voyage);
            $em->persist($voyages);  // équivalent à la création d'une requête INSERT INTO
            $em->flush();            // exécute la (ou les) requête(s) en attente
            return $this->redirectToRoute("formulelist");  // redirection vers la route
        }

       
       
        return $this->render("voyage/formulaire-voyage.html.twig", ["mode" => "ajouter" ]);
    }
}
