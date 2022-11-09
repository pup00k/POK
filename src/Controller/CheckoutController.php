<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Orders;
use App\Form\OrderType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Stripe\Order;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CheckoutController extends AbstractController
{
    // /**
    //  * @Route("/checkout", name="checkout")
    //  */
    // public function index(Request $request, SessionInterface $session, EventRepository $eventRepository, ManagerRegistry $doctrine): Response
    // {   
    //     $entityManager = $doctrine->getManager();

    //     $order = new Orders;
    //     $form = $this->createForm(OrderType::class, null);
    //     $form -> handleRequest($request);
    //     $panier = $session->get('panier');
    //     $total = 0;
    //     // dd($panier);
    //     $user = $this->getUser();


    //     if($form->isSubmitted() && $form->isValid())
    //     {
    //     //     //  $form->$event->getPrice($panier['price']);
            

    //         foreach($panier as $item)
    //         {
    //             $pr = $doctrine->getRepository(Event::class)->find($item['event']->getId());
    //              $price = $pr->getPrice();
    //             // dd($event);
    //              $quantite = $item["nb_participant"]; 
    //              $total = $price*$quantite;
    //              $item = $form->getData();
    //              $form->get('ordered_at')->getData(new \DateTime('now'));
                 
    //         }

            
          
            
    //         $em= $doctrine->getManager();
    //         // $comment->setUser($this->getUser());
    //         $em->persist($order);
    //         $em->flush();
    //     }
        
    //     // // {% set foo = foo + item.programmation.atelier.price * item.nb_participant %}
            

    //     return $this->render('checkout/index.html.twig', [
    //         'form' => $form->createView(),
    //         'user'=>$user,
    //         'panier'=>$panier
    //     ]);
    // }

        /**
         * @Route("/checkout", name="checkout")
         */
        public function index(SessionInterface $session)
        {
            $panier = $session->get('panier');
            return $this->render('checkout/index.html.twig', [
                'panier' => $panier,
            ]);
        }
        /**
         * @Route("/add/reservation", name="add_order")
         */
        public function newOrder(SessionInterface $session, ManagerRegistry $doctrine)
        {
            $entityManager = $doctrine->getManager(); 
            // Récupère les infos nécessaire à la l'ajout d'une nouvelle révervation
            $panier = $session->get('panier');
            $user = $this->getUser();
            $now = new \DateTimeImmutable();
            // Boucle sur mon panier pour faire passer tous mes items/programmations dans la base de données
            foreach ($panier as $item) { 
                $r = new Orders();
                // Réhydrate mes objets programmations suite un soucis d'accès au données lors de test
                $pr = $doctrine->getRepository(Event::class)->find($item['event']->getId());
                // Utilise les setters de la classe Réservation pour la création de nouvelles instances
            
                $r->setName($user->getName());
                $r->setFirstname($user->getFirstname());
                $r->setEmail($user->getEmail());
                $r->setPrice($pr->getPrice());
                $r->addReserve($pr);
                // $r->setNbparticipant();
                // $r->setEvent($pr);
                // $r->setOrders();
                $r->setQuantity($item['nb_participant']);
                $r->setOrderedat($now);
                // Persist les données "set" (équivalent de la méthode prepare)
                $entityManager->persist($r);
                // Exécute la requête pour créer une nouvelles instances de réservations
                $entityManager->flush(); 
                // Vide mon panier en session
                $session->clear();
            }
            // Redirige vers la page de remerciement
            // Le paiement est déjà un succès si on est rediriger vers cette fonction
            return $this->redirectToRoute('home');
        }

}