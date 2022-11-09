<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Images;
use App\Form\RegisterType;
use App\Form\ModifyAccountType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{


     /**
     * @Route("/suppressionProfil/{id}", name="profilSuppression", methods={"DELETE","GET"})
     */
    public function  deleteAccount(Request $request, User $user, ManagerRegistry $doctrine): Response{

        // $anonymous = $this->$user->getId(3);
        // $anonymousPseudo = $anonymous->getPseudo(); 
        
        if ($this->getUser() != $user) {
            return $this->render('account/error.html.twig');
        } else { 
        $entityManager = $doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        }

        $this->addFlash('success', 'Votre compte a bien été supprimé');

        return $this->redirectToRoute('home');   
    
        

    }
    /**
     * @Route("/compte", name="account") 
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $user = $doctrine -> getRepository(User::class)->findBy([],["name" => "ASC"]);
        $events = $doctrine -> getRepository(Event::class)->findBy([],["name" => "ASC"]);
        
        return $this->render('account/index.html.twig', [
            'user' => $user,
            'events'=> $events
        
        ]);
    }

        /**
     * @Route("/compte/{id}", name="account_search") 
     */
    public function profil(ManagerRegistry $doctrine): Response
    {
        $user = $doctrine -> getRepository(User::class)->findBy([],["name" => "ASC"]);
        $events = $doctrine -> getRepository(Event::class)->findBy([],["name" => "ASC"]);
        
        return $this->render('account/index.html.twig', [
            'user' => $user,
            'events'=> $events
        
        ]);
    }

    /**
     * @Route("/compte/data", name="user_data") 
     */
    public function UserData(ManagerRegistry $doctrine): Response{

        return $this->render('account/data.html.twig');

    }

    /**
     * @Route("{id}/edit_account", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, ManagerRegistry $doctrine): Response
    {

        $entityManager = $doctrine->getManager();
        $form = $this->createForm(ModifyAccountType::class,$user);
        $form->handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()){
            //On récupère les img transmises 
            $img = $form->get('photo')->getData();
            $imgBack = $form->get('photo_background')->getData();

            if($img) {
                //On boucle sur les images SI on veut ajouter Plusieurs images 
                //foreach($img as $imgs){
                // On généère new nom fichier
                $fichier = md5(uniqid()).'.'.$img->guessExtension();

                // On va copier le fichier dans le dossier img, par intermdiaire de la methode moove
                    $img->move(
                    $this->getParameter('images_directory'),
                    $fichier);
                    

                    $nom = $user->getPhoto();
                    // On supprime le fichier
                    
                    unlink($this->getParameter('images_directory').'/'.$nom);
                    
                    // On créer on stock dans la bdd son nom et l'img stocké dans le disque 
                    $user->setPhoto($fichier);
                    //}
                    
                    // $user = $form -> getData();
                    $entityManager -> persist($user);
                    $entityManager -> flush();
                
                    
                } else {
                    $user = $form -> getData();
                    $entityManager -> persist($user);
                    $entityManager -> flush();

            }

            if($imgBack) {

                $fichier = md5(uniqid()).'.'.$imgBack->guessExtension();

                // On va copier le fichier dans le dossier img, par intermdiaire de la methode moove
                    $imgBack->move(
                    $this->getParameter('images_directory'),
                    $fichier);

                    // On créer on stock dans la bdd son nom et l'img stocké dans le disque 
                    $user->setPhotoBackground($fichier);
                    //}
                    
                    // $user = $form -> getData();
                    $entityManager -> persist($user);
                    $entityManager -> flush();
            } else {
                $user = $form -> getData();
                $entityManager -> persist($user);
                $entityManager -> flush();
            }

            return $this->redirectToRoute('account');
        }

        return $this->render('account/edit.html.twig',[
            'user'=> $user,
            'form'=> $form->createView(),
       
        ]);

    }
   
    
}

