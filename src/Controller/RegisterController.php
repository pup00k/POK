<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use App\service\JWTService;
use App\service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    private $EntityManager; // Initaliser une V doctrine Manager de cotrine pour nos ma,ipulation

    public function __construct(EntityManagerInterface $EntityManager){ // fonction construct dedans ->  inject entity manager interface
        $this->EntityManager = $EntityManager;}

    /**
     * @Route("/inscription", name="inscription")
     */
    public function index(Request $request, UserPasswordHasherInterface $encoder, SendMailService $mail, JWTService $jwt): Response
    {

            $user =  new User();
            $form = $this->createForm(RegisterType::class, $user);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                            //On récupère les img transmises 
            $img = $form->get('photo')->getData();
            //On boucle sur les images 
            //foreach($img as $imgs){
            // On généère new nom fichier
               $fichier =md5(uniqid()).'.'.$img->guessExtension();
                
            // On va copier le fichier dans le dossier img, par intermdiaire de la methode moove
                $img->move(
                $this->getParameter('images_directory'),
                $fichier
                );

               if($form->get('roleArtiste')->getData()) {
                    $user->addRoles("ROLE_ARTISTE");
                }

                if($form->get('roleUser')->getData()) {
                    $user->addRoles("ROLE_USER");
                }
            // On créer on stock dans la bdd son nom et l'img stocké dans le disque 

                $user->setPhoto($fichier);

                $user = $form->getData();
                $user->setRegisterDate(new \DateTime());
                $user -> setIsVerified(false);

                $password = $encoder->hashPassword($user,$user->getPassword()); // Le user va prendre le password, pour Hashed appel la methode encodepassword, avec 1er user et deuxièeme mdp
                // dd($password);

                $user->setPassword($password);
                // $doctrine = $this->getDoctrine()->getManager(); // On apelle doctrine avec le get et le manager
                $this->EntityManager->persist($user); // use la premier methode persist prend en para, persist notre enité user, fige la data parce que besoin de l'enregistrer
                $this->EntityManager->flush(); // flush est comme un execute, tu execute le persiste de la data  qui est figé.
            
                // On génère le JWT de l'user 
                
                // On crée le Header
                $header = [
                    'typ' => 'JWT',
                    'alg'=> 'HS256'
                ];

                // ON Crée le Payload 

                $payload = [
                    'user_id'=>$user->getId()
                ];

                // On génère le token 
                $token = $jwt->generate($header, $payload,$this->getParameter('app.jwtsecret'));
              
            

                // On envoie le mail 
                $mail->send(
                    'no-reply@monsite.net',
                    $user->getEmail(),
                    'Activation de Votre compte sur le site événementiel POK',
                    'register', 
                    compact('user', 'token') 
                    
                );

                return $this->redirectToRoute('home');
            }
            
        return $this->render('register/index.html.twig', [
            'form' => $form ->createView()
        ]);
        
    }


    /**
     * @Route("/verif/{token}", name="verify_user")
     */
    public function verifyUser($token, JWTService $jwt, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
            // On vérifie sir le token est valide, n'a pas expiré et n'a pas été modifier 

            if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))){ 
                    // On récupère le payload 
                    $payload = $jwt->getPayload($token);

                    // On récupère le user du token 
                    $user = $userRepository->find($payload['user_id']);

                    // On vérifie que l'utilisateur existe et n'a pas encore activé son compte
                    if($user && !$user->getIsVerified()){
                        $user->setIsVerified(true);
                        $em->flush($user);
                        $this->addFlash('success', 'Utilisateur activé');
                        return $this->redirectToRoute('account');
                    }

            }
            // Ici un problème se pose dans le token 
            $this->addFlash('danger', 'Le token est invalide ou a expiré');
            return $this->redirectToRoute('app_login');
    }
    
}
