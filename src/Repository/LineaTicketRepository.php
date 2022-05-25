<?php

namespace App\Repository;

use App\Entity\LineaTicket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LineaTicket|null find($id, $lockMode = null, $lockVersion = null)
 * @method LineaTicket|null findOneBy(array $criteria, array $orderBy = null)
 * @method LineaTicket[]    findAll()
 * @method LineaTicket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LineaTicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LineaTicket::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(LineaTicket $entity, bool $flush = true): void
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
    public function remove(LineaTicket $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function sacarLineaTicket($productos_id, $ticket_id)
    {   
        return $this->createQueryBuilder('l')
            ->where('l.productos = :productos_id')
            ->setParameter('productos_id', $productos_id)
            ->andWhere('l.ticket = :ticket_id' )
            ->setParameter('ticket_id', $ticket_id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return LineaTicket[] Returns an array of LineaTicket objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LineaTicket
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
