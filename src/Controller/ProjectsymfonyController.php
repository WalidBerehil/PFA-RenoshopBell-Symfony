<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class ProjectsymfonyController extends AbstractController
{
    /**
     * @Route("/projectsymfony", name="projectsymfony")
     */
    public function index(SessionInterface $session)
    {
      $repos = $this->getDoctrine()->getRepository(Article::class);
      $articles = $repos ->findAll();
      
      $repos1 = $this->getDoctrine()->getRepository(Categorie::class);
      $categories = $repos1 ->findAll();
      
      
      $nb = count($session->get('panier', []));

      return $this->render('Projectsymfony/home.html.twig', [ 'articles' => $articles,'categories'=>$categories,'nbCmds'=>$nb]);
    }
    
    /**
     * @Route("/categorie/{id}", name="cat")
     */
    public function cat($id,SessionInterface $session)
    {
      $repos = $this->getDoctrine()->getRepository(Article::class);
      $articles = $repos ->findAll();
      
      $repos1 = $this->getDoctrine()->getRepository(Categorie::class);
      $categories = $repos1 ->findAll();
      
      $arts =[];
      if($id==-1){
        return $this->redirect('http://127.0.0.1:8000/admin');
      }

      if($id==0){
        $arts=$articles;  
      }else{
        for ($i=0; $i <count($articles) ; $i++) { 
          if($articles[$i]->getCategorie()->getId()==$id) array_push($arts,$articles[$i]);
        }
      }

      $nb = count($session->get('panier', []));
      
      return $this->render('Projectsymfony/cats.html.twig', [ 'articles' => $arts,'categories'=>$categories,'nbCmds'=>$nb]);
    }
    
    

    /**
     * @Route("/chercher", name="chercher")
     */

    public function chercher(SessionInterface $session,Request $request)
    {

      
      $mot=$request->get('mot');
      $id=$request->get('cats');

      if(!empty($id)){
        $repos = $this->getDoctrine()->getRepository(Article::class)->createQueryBuilder('p')
          ->where('p.titre LIKE :word AND p.categorie = :id')
          ->setParameter('word', "%".$mot."%")
          ->setParameter('id', $id)
          ->getQuery();
        $articles = $repos ->getResult();
      }else{
        $repos = $this->getDoctrine()->getRepository(Article::class)->createQueryBuilder('p')
          ->where('p.titre LIKE :word')
          ->setParameter('word', "%".$mot."%")
          ->getQuery();
        $articles = $repos ->getResult();
      }
      


      $repos1 = $this->getDoctrine()->getRepository(Categorie::class);
      $categories = $repos1 ->findAll();
      

      
      $nb = count($session->get('panier', []));

      return $this->render('Projectsymfony/search.html.twig', [ 'articles' => $articles,'categories'=>$categories,'nbCmds'=>$nb]);
    }




    





    public function add(Request $request) 
    {

            $Article = new Article();
              $form = $this->createFormBuilder($Article)
                          ->add('titre', TextType::class)
                          ->add('image', TextType::class)
                          ->add('price', TextType::class)
                          ->add('Ajouter', SubmitType::class , [
                            'attr' => [
                              'class' => 'btn btn-primary'
                            ]
                          ])
                          ->getForm();
                          $form->handleRequest($request);
                          if ($form->isSubmitted()){
                          $em = $this->getDoctrine()->getManager(); // On récupère l'entity manager
                          $em->persist($Article); // On confie notre entité à l'entity manager (on persist l'entité)
                          $em->flush(); // On execute la requete
              
                          return new Response('L\'article a bien été enregistrer.');}
        
        
                  return $this->render('Projectsymfony/add.html.twig', ['formArticle'=>$form->createView()]);
        
                  //return new Response('<h1>hello</h1>');
    }


    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function detail($id,SessionInterface $session){
       
      $repos= $this->getDoctrine()->getRepository(Article::class);
      $article=$repos->find($id);

      $repos2= $this->getDoctrine()->getRepository(Article::class);
      $articles=$repos2->findAll();


      $repos1 = $this->getDoctrine()->getRepository(Categorie::class);
      $categories = $repos1 ->findAll();

      $nb = count($session->get('panier', []));

      return $this->render('Projectsymfony/detail.html.twig',['article'=>$article,'articles'=>$articles,'categories'=>$categories,'nbCmds'=>$nb]);
  }


  /**
     * @Route("/home", name="home")
     */
    public function home(SessionInterface $session){
       
      $repos = $this->getDoctrine()->getRepository(Article::class);
      $articles = $repos ->findAll();
      
      $repos1 = $this->getDoctrine()->getRepository(Categorie::class);
      $categories = $repos1 ->findAll();
      
      
      $nb = count($session->get('panier', []));

      return $this->render('Projectsymfony/home.html.twig', [ 'articles' => $articles,'categories'=>$categories,'nbCmds'=>$nb]);
    }











    public function show($url)
    {
        return new Response('<h1>Lire l\'article ' .$url.'<h1>');
    }

    public function edit($id)
    {
        return new Response('<h1>Modifier l\'article' .$id.'<h1>');
    }

    public function remove($id)
    {
        return new Response('<h1>Supprime l\'article' .$id.'<h1>');
    }
}
