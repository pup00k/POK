<?php

namespace App\Controller;

use App\Form\ChangePasswordType;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ChangePasswordController extends AbstractController
{

    // private $EntityManager;

    public function __construt( EntityManagerInterface $EntityManager){
        $this->EntityManager = $EntityManager;
    }

    /**
     * @Route("/compte/modifier-mot-de-passe", name="change_password")
     */
    public function index(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $encoder): Response // request besoin de lui passer la request en injection de dépendance
    {
        $notification = null;

        $entityManager = $doctrine->getManager();
        $user = $this->getUser(); // besoin d'avoir l'objet user et q'il soit connecter on l'appel pour l'injecter dans l'objet user
        $form = $this->createForm( ChangePasswordType::class, $user); //  On appel le form, creeateform en appellant le formulaire change password type, on appel $user l'objet de la class por le relier 
        
        $form->handleRequest($request); // Pour traiter le form methode handlerequest pres é eocuter la methode entrante
        
        // ********************PERMET DE SUBMIT LE FORMULAIRE ET LE RELIER A LANCIEN MOT DE PASSE ET LE NOUVEAU***********
        
        if($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData(); // RECUPERAGE DU OLD PWD, on rappel form et on get OLD password et on lui dit de prendre la data avec getdata.
            
            // **********************PERMET D'ENCODER LE PSSWORD ET DE LEXECUTER DANS LA BDD EN LE METTANT A JOUR**********************************************  
            if($encoder->isPasswordValid($user, $old_pwd)){ // SUPER methode de l'encodeur isPasswordValid, 2 para l'user et l'ancien password (saisie par l'user), et si true -> pwd = identiquer
                $new_pwd = $form->get('new_password')->getData(); // on récupère les données dans la formulaire que l'user a mis avec le getdata
                $password = $encoder->hashPassword($user, $new_pwd); // On encode le password grace a la methode encoder->encodePassword et on le stock dans la variable password
                
                $user->setPassword($password);
                                                // $doctrine = $this->getDoctrine()->getManager(); // On apelle doctrine avec le get et le manager
                $entityManager->persist($user); // BIEN DEFINIR LE ENTITY MANAGER LIGNE 30 en METTANT MANAGE REGISTRY DAS LES PARA DE LINDEX 
                $entityManager->flush(); // flush est comme un execute, tu execute le persiste de la data  qui est figé.
                    $notification = " Votre mot de passe a bien été mis à jour.";      
                } else {
                    $notification = " Votre mot de passe actuel n'est pas le bon";
                }
        }
        
        
        return $this->render('account/password.html.twig', [
            'form' => $form -> createView(),
            'notification' => $notification
        ]);
    }
}
