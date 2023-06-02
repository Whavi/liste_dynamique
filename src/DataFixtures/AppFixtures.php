<?php

namespace App\DataFixtures;

use App\Entity\Pays;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         // France
         $france = new Pays;
         $france->setName('France');
 
         $paris = new Ville;
         $paris->setName('Paris');
         $paris->setPays($france);
 
         $marseille = new Ville;
         $marseille->setName('Marseille');
         $marseille->setPays($france);
 
         $lyon = new Ville;
         $lyon->setName('Lyon');
         $lyon->setPays($france);
 
         $manager->persist($france);
         $manager->persist($paris);
         $manager->persist($marseille);
         $manager->persist($lyon);
 
         // Canada
         $canada = new Pays;
         $canada->setName('Canada');
 
         $quebec = new Ville;
         $quebec->setName('Québec');
         $quebec->setPays($canada);
 
         $montreal = new Ville;
         $montreal->setName('Montréal');
         $montreal->setPays($canada);
 
         $troisRivieres = new Ville;
         $troisRivieres->setName('Trois-Rivières');
         $troisRivieres->setPays($canada);
 
         $manager->persist($canada);
         $manager->persist($quebec);
         $manager->persist($montreal);
         $manager->persist($troisRivieres);
 
         // Côte d'ivoire
         $coteDivoire = new Pays;
         $coteDivoire->setName("Côte d'ivoire");
 
         $abidjan = new Ville;
         $abidjan->setName('Abidjan');
         $abidjan->setPays($coteDivoire);
 
         $yamoussoukro = new Ville;
         $yamoussoukro->setName('Yamoussoukro');
         $yamoussoukro->setPays($coteDivoire);
 
         $bouake = new Ville;
         $bouake->setName('Bouaké');
         $bouake->setPays($coteDivoire);
 
         $manager->persist($coteDivoire);
         $manager->persist($abidjan);
         $manager->persist($yamoussoukro);
         $manager->persist($bouake);
 
         $manager->flush();
    }
}
