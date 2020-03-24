<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Formules;

class FormulesFixtures extends BaseFixture
{
    // fonction qui permet de creer des données enrengistrée en BDD pour la table Formules
    public function loadData(ObjectManager $manager)
    {
        $formule = ["Basic 7 jours", "Gold 15 jours"];
        $prix = ["3500 €", "4000 €"];
        $this->createMany(2, "formules", function($num) use ($formule, $prix){
             $formules = (new Formules) -> setFormule($formule[$num])
                                        -> setPrix($prix[$num]);

             return $formules;
         });
        

        $manager->flush();
    }
}
