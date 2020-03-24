<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface as EMI;
use App\Security\RegistrationFormTypeAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(Request $request, EMI $em, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, RegistrationFormTypeAuthenticator $authenticator): Response
    {
        //création d'un objet user(création d'un nouvel utilisateur)
        $user = new User();

        //création d'un formulaire à partir de la classe UserType:remplace les setPropriete()
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);//faire les requètes en BDD

        //Si le formulaire est envoyé et complet (avec toutes les informations)
        if ($form->isSubmitted() && $form->isValid()) {
            // encode le mot de passe(sinon il est enrengistré non crypté)
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);//recupère toutes les informations et prepare la requète "sql"
            $entityManager->flush();//execute la requète ( envoie les informations en BDD)

            
             //retourne les informations vers la vue (toujours dans le 'if')
            return $guardHandler->authenticateUserAndHandleSuccess( 
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );

            
        }

        //retourne les informations vers la vue
        return $this->render('registration/formulaire-user.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
