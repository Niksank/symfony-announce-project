<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) { 
            $article = new Article();
            $article->setTitle("titre de l'article numero $i")
                    ->setPrice('98')
                    ->setDescription("Voici ma description")
                    ->setImage("http://placehold.it/350x150")
                    ->setCreatedAt(new \DateTime());

            $manager->persist($article);
            
        }

        $manager->flush();
    }
}
