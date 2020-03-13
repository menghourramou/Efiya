<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VoyageController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('voyage/index.html.twig', [
            'controller_name' => 'VoyageController',
        ]);
    }
    /**
     * @Route("/egypte", name="egypte")
     */
    public function egypte()
    {
        return $this->render('voyage/egypte.html.twig');
    }
    /**
     * @Route("/ethiopie", name="ethiopie")
     */
    public function ethiopie()
    {
        return $this->render('voyage/ethiopie.html.twig');
    }
    /**
     * @Route("/jordanie", name="jordanie")
     */
    public function jordanie()
    {
        return $this->render('voyage/jordanie.html.twig');
    }
    /**
     * @Route("/nevada", name="nevada")
     */
    public function nevada()
    {
        return $this->render('voyage/nevada.html.twig');
    }
}
