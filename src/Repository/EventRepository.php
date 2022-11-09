<?php

namespace App\Repository;

use DateTime;
use App\Entity\Event;
use App\Classe\Search;
use App\Data\SearchData;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use ContainerF7C6844\PaginatorInterface_82dac15;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Event::class);
        $this->paginator = $paginator;
    }


    
    // /**
    //  * @return Product[]
    //  */
    // public function findWithSearch(Search $search){
        
    //     $query = $this
    //     ->createQueryBuilder('e')
    //     ->select('c','e')
    //     ->join('e.category', 'c');

    //     if(!empty($search->categories)){ // Si l'utilsiateur recherche une catégroei tu execute ça 
    //         $query = $query 
    //             ->andWhere('c.id IN (:categories)')
    //             ->setParameter('categories', $search->categories);


    //     }

    //     if(!empty($search->categories)){ //Si l"user recherche en text du execute ça 
    //         $query = $query
    //         ->andWhere('e.name LIKE :string')
    //         ->setParameter('string',"{$search->string}%"); //
    //     }

    //     return $query->getQuery()->getResult(); // Je veux que tu return les resultats
            
    // }
    public function add(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // /**
    //  * @return Product[]
    //  */
    // public function findSearch(SearchData $search): array{

    //     $query = $this
    //     ->createQueryBuilder('e')
    //     ->select('c', 'e')
    //     ->join('e.categorie_event', 'c');

    //     if(!empty($search->q)){
    //         $query = $query 
    //         ->andWhere('e.name LIKE q')
    //         ->setParameter('q', "%{$search->q}%");
    //     }
    //     return $query->getQuery()->getResult();

    // }
    /**
     * @return PaginationInterface
     */
    public function findSearch(SearchData $search): PaginationInterface{

        $query = $this->createQueryBuilder('e')
        ->leftJoin('e.categorie_event', 'c')
        ->leftJoin('e.artist', 'p');

        if(!empty($search->q)){
            $query = $query 
            ->andWhere('e.name LIKE :q')
            ->orWhere('p.pseudo LIKE :q')
            ->setParameter('q', "%{$search->q}%");
        }

        if(!empty($search->min) || $search->min !== null){
            $query = $query
            ->andWhere('e.price >= :min')
            ->setParameter('min', $search->min);
        }

        if(!empty($search->max) || $search->max !== null){
            $query = $query
            ->andWhere('e.price <= :max')
            ->setParameter('max', $search->max);
        }

        if(!empty($search->categories)) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        }

        if(!empty($search->dateStart) || $search->dateStart !==null){

            $query = $query
            ->andWhere('e.date_start >= :dt')
            ->setParameter('dt',($search->dateStart));

        }
        // if(!empty($search->dateEnd) || $search->dateEnd !==null){

        //     $query = $query
        //     ->andWhere('e.date_end <= :dte')
        //     ->setParameter('dte',($search->dateEnd));

        // }

        // if(!empty($search->countryLocation) || $search->countryLocation !== null){
        //     $query = $query
        //     ->andWhere('s.country_location = :country')
        //     ->setParameter('country', $search->countryLocation);
        // }

        $query = $query->getQuery();
        return $this->paginator->paginate(
            $query, 
            $search->page,
            10
        );

        } 
//     /**
//      * @return Event
//      */
//     public function getEventsOnDate(){
//         $date = new \DateTime();
//         $query = $this->createQueryBuilder('e')
//         ->where('date(e.date == :dt')
//         ->setParameter('dt', $date->format('Y-m-d'));

//         return $query->getQuery()->getResult();
// }
    }

      
    

    

    //  /**
    //   * @return void
    //   */
    // public function getPaginatedEvents($page, $limit, $filtres = null){
    //     $query = $this->createQueryBuilder('event');


    //         // SELECT NAME, name_category
    //         // FROM event
    //         // INNER JOIN event_category ec ON event.id = ec.event_id
    //         // INNER JOIN category ON ec.category_id = category.id

    //     // On filtre les données 
    //     if($filtres != null  ){
    //         $query ->where('event.category IN (:cats)')
    //         ->setParameter(':cats', array_values($filtres));
    //     }

    //     $query->orderBy('event.date_start')
    //     ->setFirstResult(($page * $limit) - $limit)
    //     ->setMaxResults($limit);
    
    //     return $query->getQuery()->getResult();
    // }


    // /**
    //   * @return void
    //   */
    // public function getTotalEvents($filtres = null){
    //     $query = $this-> createQueryBuilder('event')
    //     ->select('COUNT(event)');

    //     // On filtre les données 
    //     if($filtres != null  ){
    //         $query ->andWhere('event.category IN (:cats)')
    //         ->setParameter(':cats', array_values($filtres));
    //     }        

    //     return $query->getQuery()->getSingleScalarResult();

    // }


//    /**
//     * @return Event[] Returns an array of Event objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

