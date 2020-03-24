<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface as EMI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{

    //fonction pour afficher la liste des utilisateurs(disponible pour l'admin)
     /**
     * @Route("/admin/userlist", name="userlist")
     */
    public function userList(UserRepository $ur)
    {
        $users = $ur->findAll();
        $taille = count($users); 
        return $this->render('user/liste.html.twig', [
            "users" => $users, "taille" => $taille
        ]);
    }
    //fonction pour que l'admin puisse modifier un utilisateur(sans le mdp car confidentiel)
     /**
     * @Route("/admin/userupdate/{id}", name="user_update")
     */
    public function update(UserRepository $ur, Request $rq, EMI $em, int $id)
    {
    
        $userAmodifier = $ur->find($id);
        if($rq->isMethod("POST")){
            $userAmodifier->setNom($rq->request->get("nom"));
            $userAmodifier->setPrenom($rq->request->get("prenom"));
            $dateNaissance = new \DateTime($rq->request->get("date_naissance"|date("Y/m/d")));
            $userAmodifier->setDateNaissance($dateNaissance);
            $userAmodifier->setEmail($rq->request->get("email"));
            $userAmodifier->setNationalite($rq->request->get("nationalite"));
            $em->persist($userAmodifier);  // équivalent à la création d'une requête UPDATE
            $em->flush();            // exécute la (ou les) requête(s) en attente
            return $this->redirectToRoute("userlist");  // redirection vers la route
        }
        return $this->render("user/formulaire.html.twig", [ "user" => $userAmodifier, "mode" => "modifier" ]);

    }
    //fonction pour supprimer un utilisateur(disponible uniquement pour l'admin)
    /**
     * @Route("/admin/userdelete/{id}", name="user_delete")
     */
    public function delete(UserRepository $ur, Request $rq, EMI $em, int $id)
    {
        $userAsupprimer = $ur->find($id);
        if($rq->isMethod("POST")){
            $em->remove($userAsupprimer);    // équivalent à la création d'une requête DELETE
            $em->flush();                       // exécute la (ou les) requête(s) en attente
            return $this->redirectToRoute("userlist");  // redirection vers la route
        }
        return $this->render("user/formulaire.html.twig", [ "user" => $userAsupprimer, "mode" => "confirmer" ]);
    }


    /**
     * @Route("/registration", name="user_registration")
     */
    // public function add(EMI $em, Request $request)
    // {
    //     $bouton = "add";

    //     if($request->isMethod("POST")){ 
    //         $email = $request->request->get('email');
    //         $mdp = $request->request->get('password');
    //         $mdp = password_hash($mdp, PASSWORD_DEFAULT);
    //         $user = new User; 
    //         $user->setNom($nom);
    //         $user->setPrenom($prenom);
    //         $user->setDateNaissance($dateNaissance);
    //         $user->setNationalite($nationalite);
    //         $user->setEmail($mail);
    //         $user->setPassword($mdp);
    
    //         $em->persist($user);
    //         $em->flush();
    
    //         return $this->redirectToRoute("home");
    
    //     }
    //     else{

    //         return $this->render('user/formulaire.html.twig', ["bouton" => $bouton]); 

    //     }
    // }
}