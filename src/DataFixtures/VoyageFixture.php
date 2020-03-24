<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Voyage;

class VoyageFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $pays = ["Egypte", "Jordanie", "Ethiopie", "Sahara"];
        $this->createMany(4, "destination", function($num) use ($pays){
             $voyage = (new Voyage) -> setDestination($pays[$num]);

             return $voyage;
         });

       

        $manager->flush();
    }
}
