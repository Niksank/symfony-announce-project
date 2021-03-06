<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use App\Entity\Article;
use App\Entity\Category;
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
            
            $file = $article->getImage();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            //dd($fileName);
            // Move the file to the directory where brochures are stored
            try {
                $file->move(
                    $this->getParameter('img_upload'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $article->setImage($fileName);

            $manager->persist($article);
            $manager->flush();
            
            return $this->redirectToRoute('show_announce',['id'=> $article->getId()]);
        }
        
        return $this->render('announce/create.html.twig',[
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }


    /**
     * @Route("/annonce/delete/{id}", name="delete_announce")
     */
    public function remove($id, ObjectManager $manager, ArticleRepository $repo){
        $article = $repo-> find($id); 
        if (!$article) {
            throw $this->createNotFoundException(
                'Impossible de trouver une annonce avec l\'id: '.$id
            );

        }

        $manager->remove($article);
        $manager->flush();

        return $this->render('announce/remove.html.twig');

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
    
    /**
     * @Route("/search", name="search_announce")
     */
    public function search(Request $request, ArticleRepository $repo) {

        //dump($request->request->get('query')); 
        //die;

        $q = $request->request->get('query');
        $posts = $repo->findPostsByName($q);

        if(!$posts){
            dd("Pas de query avec cette valeur");
            die;
        }

        return $this->render('search/result.html.twig', [
            'posts' => $posts
        ]);
        //dump($post);
        //die;
        
    }

    
    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
    
}
