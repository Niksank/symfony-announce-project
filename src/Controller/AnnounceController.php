<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Form\ArticleType;

class AnnounceController extends AbstractController
{
    /**
     * @Route("/annonce", name="announce")
     */
    public function index(ArticleRepository $repo)
    {
  
        $articles = $repo-> findAll(); 
        return $this->render('announce/index.html.twig', [
            'controller_name' => 'AnnounceController',
            'articles' => $articles
        ]);
    }
    /**
     * @Route("/", name="home")
     */
    public function home() {
        return $this->render('announce/home.html.twig', [
            'Title'=> "Bienvenue"
        ]);
    }

    /**
     * @Route("/annonce/new", name="create_announce")
     * @Route("/annonce/edit/{id}", name="edit_announce")
     */
    public function form(Article $article = null,  Request $request, ObjectManager $manager) {
        if (!$article) {
            $article = new Article();
        }
        

        /* $form = $this->createFormBuilder($article)
                     ->add('title')
                     ->add('description')
                     ->add('image')
                     ->add('price')
                     ->getForm(); */

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            if(!$article->getId()){
                $article->setCreatedAt(new \DateTime());
            }
            
            $file = $form['attachment']->getData();
            $manager->persist($article);
            $manager->flush();
            
            //return $this->redirectToRoute('show_announce',['id'=> $article->getId()]);
        }
        
        return $this->render('announce/create.html.twig',[
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }

    /**
     * @Route("/annonce/{id}", name="show_announce")
     */
    public function show($id, ArticleRepository $repo) {
        $article = $repo->find($id);
        return $this->render('announce/show.html.twig', [
            'article' => $article
        ]);
    }

    
}
