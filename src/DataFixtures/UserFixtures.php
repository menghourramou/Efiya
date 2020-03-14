<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        
        $this->createMany(10, "admin", function($num){ 

            $email = "admin" . $num . "@efiya.com";
            $mdp = password_hash("admin" . $num, PASSWORD_DEFAULT);
            $nom = $this->faker->lastName;
            $prenom = $this->faker->firstName;
            $DateNaissance = $this->faker->dateTime("now");
            

            $user = (new User)->setEmail($email)
                                  ->setPassword($mdp)
                                  ->setRoles(["ROLE_ADMIN"])
                                  ->setNom($nom)
                                  ->setPrenom($prenom)
                                  ->setDateNaissance($DateNaissance)
                                  ->setNationalite("Algerienne");
        
            return $user;

        });

        $manager->flush();

    }

}
