<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Event;
use App\Entity\Comment;
use App\Data\SearchData;
use App\Form\SearchType;
use App\Form\AddCartType;
use App\Form\CommentType;
use App\Form\AddEventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class EventController extends AbstractController
{

    // private $entitymanager;

     public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
     }
    /**
     * @Route("/evenements", name="event")
     */
    public function index(ManagerRegistry $doctrine, Request $request, EventRepository $eventRepo): Response
    {
        //// $date = $doctrine ->getRepository(Event::class)->findBy([]["date_start" => "Y:m:d"]);
        // Initialisation des données
        $dataSearch = new SearchData();

        // Incorporation de la fonctionnalité de pagination
        $dataSearch ->page =$request->get('page',1);

        // Instanciation du Form dans le controller
        $form = $this->createForm(SearchType::class, $dataSearch);

        //L’instruction $form->handleRequest($request); permet de gérer le traitement de la saisie du formulaire.
        $form->handleRequest($request);

        $events = $eventRepo->findSearch($dataSearch);
        
        ////$events = $eventRepo->getEventsOnDate($dataSearch);
        ////$events = $paginator->paginate($dataSearch,$request->query->getInt("query",1),100);
        
        // Limitation de page
        $limit = 50;

        // On récupère le numéro de page 
        $pages = (int)$request->query->get("page", 1);


         //On récupère les events de la page en fonction du filtre
        // $events = $eventRepo->getPaginatedEvents($pages, $limit);


        return $this->render('event/index.html.twig', [
            'events' => $events,
            'pages'=> $pages,
            'form'=> $form->createView(),
            'limit' =>$limit
        ]);
    }

    
    // /**
    //  * @Route("/evenements", name="event")
    //  */
    // public function Search(Request $request): Response
    // {

    //     $events = $this->entityManager->getRepository(Event::class)->findAll();
        
    //     $search = new Search();
    //     $form = $this->createForm(SearchType::class,$search);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()){
    //         $events = $this->entityManager->getRepository(Event::class)->findWithSearch($search);
    //         $search = $form->getData();
            
    //     }

    //     return $this->render('event/index.html.twig', [
    //         'events' => $events,
    //         'form'=> $form->createView()
    //     ]);
    // }

    /**
     * @Route("/event/{id}", name="show_event")
     */
    public function show(ManagerRegistry $doctrine, Event $event, Request $request, EventRepository $eventRepo, SessionInterface $session): Response{
        //$event = $eventrepo->findOneBy(['slug'=>$slug]);
        
        $events = $doctrine -> getRepository(Event::class)->findBy([],["name" => "ASC"]);
        
        
        // Partie commentaire
        //On crée le commentaire vierge
        $comment = new Comment;
        $cartForm=$this->createForm(AddCartType::class);
        $cartForm -> handleRequest($request);
        //On génère le formulaire
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm -> handleRequest($request);
        
        // IF FORM DU PANIER 
        if($cartForm->isSubmitted() && $cartForm->isValid()){
            $dataEvent= $cartForm->getData();
            $id= $event->getId();
            $eventObject = $eventRepo->find($id);
            
            $nb_participant = $session->get('nb_participant', $dataEvent['nb_participant']);
            

            $panier = $session->get('panier', []); // J'instancie mon futur panier
            // Je construit mon panier en lui assignant id comme index et je le remplit d'un tableau de données récupérer précédemment
            $panier[$id] = [
                'event' => $eventObject,
                'nb_participant' => $nb_participant,
            ];

            $session->set('panier', $panier);
            return $this->redirectToRoute('cart_index');
        }

        //traitement du formulaire DE COMMENTAIRE
        if($commentForm->isSubmitted() && $commentForm->isValid()){
            $comment->setDateCreate(new \DateTime('now'));
            $comment->setEvent($event);

            $em= $doctrine->getManager();
            $comment->setUser($this->getUser());
            $em->persist($comment);
            $em->flush();

            $this->addFlash('text', 'Votre commentaire à bien été envoyé.');
            return $this->redirectToRoute('show_event',["id"=>$event->getId()]);


        }

        return $this->render('event/show.html.twig',[
            'event'=> $event,
            'commentForm'=>$commentForm->createView(),
            'events' => $events,
            'cartForm'=>$cartForm->createView(),
        ]);
    }



    /**
     * @Route("/artiste/compte/ajout-event", name="add_event") 
     */
    public function AddEvent(ManagerRegistry $doctrine, Event $event = null, Request $request): Response{
        
        if(!$event) { 
            $event = new Event();  
        }


        $entityManager = $doctrine->getManager();
        $form = $this-> createForm(AddEventType::class, $event);
        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()){
            //On récupère les img transmises 
            $img = $form->get('photo')->getData();

            if($img) {
                //On boucle sur les images SI on veut ajouter Plusieurs images 
                //foreach($img as $imgs){
                // On généère new nom fichier
                   $fichier =md5(uniqid()).'.'.$img->guessExtension();
    
                // On va copier le fichier dans le dossier img, par intermdiaire de la methode moove
                    $img->move(
                    $this->getParameter('images_directory'),
                    $fichier);
    
                    // On créer on stock dans la bdd son nom et l'img stocké dans le disque 
                    $event->setPhoto($fichier);
                    //}
                    
                    $event = $form -> getData();
                    $event->setArtist($this->getUser());
                    $entityManager -> persist($event);
                    $entityManager -> flush();
                
                    
                } else {
                $event = $form -> getData();
                $event->setArtist($this->getUser());
                $entityManager -> persist($event);
                $entityManager -> flush();
            

            }


            return $this->redirectToRoute('account');
        }

        return $this->render('account/add.html.twig', [
                'form'=> $form ->createView()
        ]);
    }
    /**
     * @Route("/artiste/{id}/edit", name="event_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Event $event, ManagerRegistry $doctrine): Response
    {

        $events = $doctrine -> getRepository(Event::class)->findBy([],["name" => "ASC"]);

        $entityManager = $doctrine->getManager();
        $form = $this->createForm(AddEventType::class,$event);
        $form->handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()){
            //On récupère les img transmises 
            $img = $form->get('photo')->getData();

            if($img) {
                //On boucle sur les images SI on veut ajouter Plusieurs images 
                //foreach($img as $imgs){
                // On généère new nom fichier
                   $fichier =md5(uniqid()).'.'.$img->guessExtension();
    
                // On va copier le fichier dans le dossier img, par intermdiaire de la methode moove
                    $img->move(
                    $this->getParameter('images_directory'),
                    $fichier);
    
                    // On créer on stock dans la bdd son nom et l'img stocké dans le disque 
                    $nom = $event->getPhoto();
                    
                    if ($event->getPhoto() != null) {
                        # code...
                        // On supprime le fichier
                    unlink($this->getParameter('images_directory').'/'.$nom);
                    }
                    
                    // var_dump($this->getParameter('images_directory').'/'.$nom); die;
        
                    $event->setPhoto($fichier);
                    // $entityManager->setFile(''); //ici pour vider le nom de mon fichier dans mon entité
                    // unlink($this->getParameter('images_directory').'/'. $fichier['name']); //ici je supprime le fichier 
                    // $doctrine->getManager()->flush(); //sauvegarder dans la db
                    // //}
                    
                    $event = $form -> getData();
                    $event->setArtist($this->getUser());
                    $entityManager -> persist($event);
                    $entityManager -> flush();
                
                    
                } else {
                $event = $form -> getData();
                $event->setArtist($this->getUser());
                $entityManager -> persist($event);
                $entityManager -> flush();
            

            }

            return $this->redirectToRoute('event');
        }

        return $this->render('event/edit.html.twig',[
            'event'=> $event,
            'form'=> $form->createView(),
            'events' => $events
            
       
        ]);

    }
    /**
     * @Route("/evenements/delete/{id}", name="delete_event")
     */
    public function delete(ManagerRegistry $doctrine, Event $event, User $user)
    {
        if ($this->getUser() != $user) {
            return $this->render('account/error.html.twig');
        } else { 

        $entityManager = $doctrine->getManager();
        $entityManager->remove($event);
        $entityManager->flush();
        return $this->redirectToRoute("event");
    }
}

    
    /**
     * @Route("evenements/messagedelete/{id}", name="deleteComment")
     */
    public function deleteComment(ManagerRegistry $doctrine, Comment $message, User $user): Response
    {
        if ($this->getUser() != $user) {
            return $this->render('account/error.html.twig');
        } else { 
        $event= $message->getEvent();
        $entityManager = $doctrine->getManager();
        $entityManager->remove($message);
        $entityManager->flush();
        $this->addFlash("message", "Message supprimé.");

        return $this->redirectToRoute('show_event',["id"=>$event->getId()]);

    }

    
//  si le nombre de place == au nombre de place défini ( di nombr de plzce reserver st inferieur au cota) -> avec reservation propriété de lentité event faire un lenght supérieur ou égzlr alors pas afficher ces évènements 

}
    
    
      //  public function delete(Request $request, Event $event, ManagerRegistry $doctrine): Response{

    //     if($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))){
    //         $entityManager = $this->$doctrine->getDoctrine()->getManager();
    //         $entityManager->remove($event);
    //         $entityManager->flush();
    //     }
    //     return $this->redirectToRoute('event/index.html.twig',[
    //         'event'=>$event
    //     ]);
    //  }


    //  /**
    //   * @Route("/supprimer/image/{id}", name="event_delete_image", methods={"DELLETE"})
    //   */
    //  public function deleteImage( Request $request, ManagerRegistry $doctrine){
    //     // JSON js pour pas que n'importe qui puisse y accéder avec le token
    //     $data = json_decode($request->getContent(), true);
       
    //    //Vérifie si token est valid

    //     if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
    //         //On recupère nom de l'image
    //         $nom = $image->getName();
    //         //On supprime le fichier dans le fichier
    //         unlink($this->getParameter('images_directory').'/'.$nom);
    //         // Le classico On supprime l'netrée de la base
    //         $em= $this->$doctrine->getDoctrine()->getManager();
    //         $em->remove($image);
    //         $em->flush();

    //         //On répond en Json
    //         return new JsonResponse(['success'=>1]);

    //     }else {
    //         return new JsonResponse(['error'=> 'Token Invalide'], 400);
    //     }

    //  }


 }


