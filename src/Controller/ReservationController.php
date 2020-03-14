<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReservationRepository;


class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation/{id}", name="reservation")
     */
    public function reservation(ReservationRepository $rr)
    {

        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }
}
