<?php

namespace App\Repository;

use App\Entity\Tickets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tickets|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tickets|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tickets[]    findAll()
 * @method Tickets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tickets::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Tickets $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Tickets $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

        /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function ventas($idTicket)
    {
        
        $query= 
            'SELECT tickets.id, tickets.fecha, usuarios.dni, productos.nombre, linea_ticket.precio , linea_ticket.cantidad, linea_ticket.precio * linea_ticket.cantidad AS total
            from productos, linea_ticket, tickets, usuarios
            where tickets.usuario_id = usuarios.id
            and linea_ticket.producto_id = productos.id
            and linea_ticket.ticket_id = tickets.id
            and tickets.pagado=true
            and tickets.id='.$idTicket;
        
        return $this->getEntityManager()->getConnection()->executeQuery($query)->fetchAllAssociative();


            
    }

        /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function maxTicket()
    {
        
        $query = $this->createQueryBuilder('t');
        $query->select('MAX(t.id) AS maxTicket');
    
        return $query->getQuery()->getResult();


            
    }


    // /**
    //  * @return Tickets[] Returns an array of Tickets objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tickets
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
