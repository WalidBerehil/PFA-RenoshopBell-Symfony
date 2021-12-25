<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Categorie;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SecurityController extends AbstractController
{  

    /**
     * @Route("/inscription", name="security_inscription")
     */
     public function registration(Request $request, UserPasswordEncoderInterface $encoder,SessionInterface $session)
    {
        $user = new User();
        $form = $this -> createForm(RegistrationType::class, $user);

        $form->add('email',TextType::class, array(
            'attr' => array('class' => 'form-control form-group') 
          ))
        ->add('password',PasswordType::class, array(
            'attr' => array('class' => 'form-control form-group') 
          ))
          
          ->add('inscription', SubmitType::class, [
            'attr' => ['class' => 'btn btn-success form-group'],
        ])
        ;



        $form->handleRequest($request);
        if ($form->isSubmitted()){
        $em = $this->getDoctrine()->getManager(); // On récupère l'entity manager
        $var = $encoder ->encodePassword($user, $user->getPassword());
        $user->setroles(['ROLE_USER']);
        $user->setPassword($var);
        $em->persist($user); // On confie notre entité à l'entity manager (on persist l'entité)
        $em->flush(); // On execute la requete
       
        return $this->redirectToRoute('security_connexion');
    }


        $repos1 = $this->getDoctrine()->getRepository(Categorie::class);
        $categories = $repos1 ->findAll();

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories,
            'nbCmds'    => count($session->get('panier', []))
            ]);
    }

     /**
     * @Route("/connexion", name="security_connexion")
     */
    public function login(Request $request, AuthenticationUtils $authUtils,SessionInterface $session)
 
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();


        $repos1 = $this->getDoctrine()->getRepository(Categorie::class);
        $categories = $repos1 ->findAll();


        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'categories'    => $categories,
            'nbCmds'    => count($session->get('panier', []))
        ));
    }
       /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout(){

        
    }
}
