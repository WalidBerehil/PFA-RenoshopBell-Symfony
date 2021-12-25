<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Categorie;


class PanierController extends AbstractController
{
        /**
     * @Route("/panier", name="panier_index")
     */
 public function index( SessionInterface $session, ArticleRepository $productRepository)
    {
        
        $panier = $session->get('panier', []);
        $panierArticles = [];

        foreach($panier as $id => $quantity){
            $panierArticles[] = [
                'article' => $productRepository->find($id),
                'quantity' => $quantity
            ];
        }
      
        $total = 0;
        foreach($panierArticles as $item){

            $totalitem = $item['article']->getPrice() * $item['quantity'];
            $total += $totalitem;
        }

        $repos1 = $this->getDoctrine()->getRepository(Categorie::class);
        $categories = $repos1 ->findAll();


        return $this->render('panier/panier.html.twig', [
            'items' => $panierArticles,
            'total' => $total,
            'categories' => $categories,
            'nbCmds'    => count($session->get('panier', []))
            ]);
    }
      /**
     * @Route("/panier/add/{id}", name="panier_add")
     */
 public function add($id, SessionInterface $session){
       
        $panier = $session->get('panier', []);

        if( !empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
       }
      
       $session->set('panier', $panier);

       return $this->redirectToRoute('panier_index');
    //    dd($session->get('panier'));

       


   }


/**
     * @Route("/panier/remove/{id}", name="panier_remove")
     */
 public function remove($id, SessionInterface $session){
       
        $panier = $session->get('panier', []);

        if( !empty($panier[$id])){
            unset($panier[$id]);
        }
      
       $session->set('panier', $panier);

       return $this->redirectToRoute('panier_index');
    //    dd($session->get('panier'));

       


   }






}