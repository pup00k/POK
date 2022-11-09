<?php



namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
* @Route("/cart", name="cart_")
*/
class CartController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SessionInterface $session, EventRepository $eventRepository): Response
    {
        $panier = $session->get('panier');
        // $panier = $session->get("panier",[]);
        
        // //On "frabrique" les données
        // //$dataPanier = [];
        // $total =0;
        // foreach($panier as $id => $quantite){
        //     $event = $eventRepository->find($id);
        //     $panier[] = [
        //         "event"=> $event,
        //         "quantite"=>$quantite
        //     ];
        //     $total += $event->getPrice()* $quantite;
        // }
        return $this->render('cart/index.html.twig', [
            'panier' => $panier,

        ]);        
    }

    //      /**
    //      * @Route("/add/{id}", name="add")
    //      */
    //      public function add($id,Event $event, SessionInterface $session, EventRepository $eventRepo){
    //         // On récupère le panier actuel 
           

    //         $session->set('panier', $panier);
    //         $panier = $session->get("panier", array());
    //         $id = $event->getId(); //Permet de restreindre essentiellement aux ID's (events), qui existent. 
    //         if(!empty($panier[$id])){
    //          $panier[$id]++;
    //         }else{
    //         $panier[$id]=1;
    //         }
    // // On sauvegarde dans la session, 
    //         $session->set('panier',$panier);
         
    //         return $this->redirectToRoute("cart_index");
    //     }
        
    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove($id,Event $event, SessionInterface $session){
        // On récupère le panier actuel 
        $panier = $session->get("panier", array());
        $id = $event->getId(); //Permet de restreindre essentiellement aux ID's (events), qui existent. 
        // SI panier n'existe pas 
        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else {
                unset($panier[$id]);
            }
// On sauvegarde dans la session, 
        $session->set('panier',$panier);
         var_dump($panier);
        
    }
    return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id,Event $event, SessionInterface $session){
        // On récupère le panier actuel 
        $panier = $session->get("panier", array());
        $id = $event->getId(); //Permet de restreindre essentiellement aux ID's (events), qui existent. 
        // SI panier n'existe pas 
        if(!empty($panier[$id])){
            unset($panier[$id]);
            }
// On sauvegarde dans la session, 
        $session->set('panier',$panier);
         var_dump($panier);
        return $this->redirectToRoute("cart_index");
    }
    }

    
    