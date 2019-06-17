<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i = 1; $i <= 3; $i++) { 
            $category = new Category();
            $category->setTitle($faker->sentence());

            $manager-> persist($category);

        

            for ($j = 1; $j <= 10; $j++) { 
                $article = new Article();
                $content= '<p>' . join($faker->paragraphs(3), '<p></p>') . '</p>';

                $article->setTitle($faker->sentence())
                        ->setPrice('98')
                        ->setDescription($content)
                        ->setImage($faker->image())
                        ->setCreatedAt(new \DateTime()) 
                        ->setCategory($category);

                $manager->persist($article);
                
            }
        }
        
        $manager->flush();
        
    }
}
