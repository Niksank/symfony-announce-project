<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function searchBar(){
        $form = $this->createFormBuilder(null)
                ->add('query', TextType::class)
                ->add('search', SubmitType::class, [ 
                    'attr' => [
                        'class' => 'btn btn-primary-success'
                    ]
                ]) 
                ->getForm();

        return $this->render('search/searchBar.html.twig', [
            'form' => $form -> createView()
        ]);
    }
}
