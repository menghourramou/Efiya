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
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
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