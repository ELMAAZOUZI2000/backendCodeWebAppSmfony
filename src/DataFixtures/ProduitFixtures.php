<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Produit;

class ProduitType extends Fixture
{
    public function load(ObjectManager $manager)
    { 
        for($i=1 ; $i < 100; $i++){
            $produit = new Produit();
            $produit->setTitle("Title de produit $i")
                    ->setDescription("Description de produit $i")
                    ->setReference("Reference de produit $i")
                    ->setCategory(3)
                    ->setPrix(7542742);

            $manager->persist($produit);
        }


        $manager->flush();
    }
}
