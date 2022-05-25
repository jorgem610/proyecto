<?php

namespace App\Repository;

use App\Entity\Productos;
use App\Entity\Tickets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Productos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Productos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Productos[]    findAll()
 * @method Productos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Productos::class);
    }
    /**
     * busqueda de los que viene de search
     * @return void
     */
    // La funciond de busquedad
    public function search($palabras = null, $categoria = null)
    {
        $query = $this->createQueryBuilder('a');
        $query->where('a.activo = 1');
        if ($palabras != null) {
            $query->andWhere('MATCH_AGAINST(a.nombre, a.contenido) AGAINST (:palabras boolean)>0')
                ->setParameter('palabras', $palabras);
        }

        if ($categoria != null) {
            $query->leftJoin('a.categoria', 'c');
            $query->andWhere('c.id =:id')
                ->setParameter('id', $categoria);
        }

        return $query->getQuery()->getResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */


    public function add(Productos $entity, bool $flush = true): void
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
    public function remove(Productos $entity, bool $flush = true): void
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


    public function crearTicket(Tickets $ticket, bool $flush = true): void
    {
        $this->_em->persist($ticket);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */

    public function productosConStock()
    {
        return $this->createQueryBuilder('p')
            ->where('p.activo = true')
            ->andwhere('p.stock_minimo >0')
            ->orderBy('p.fecha_entrada', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */

    public function productosConCatStock($id=null, $especial=null)
    {
        $query = $this->createQueryBuilder('p');
        $query->where('p.activo = true');
        $query->andWhere('p.stock_minimo >0');
        if ($id != null) {
            $query->andWhere('p.categoria =:id')
                ->setParameter('id', $id);
        }
        if ($especial != null){
            $query->andWhere('p.especial =:especial')
            ->setParameter('especial', $especial);
        }
        $query->orderBy('p.fecha_entrada', 'DESC');
        return $query->getQuery()->getResult();
      
    }

    public function findAllPaginated($page = 1, $categoriaId=null,  $limit = 4){
        $query = $this->createQueryBuilder('p');
       
        // if($categoriaId!=null) {
        //     $qb->innerJoin(Categoria::class, 'c', Join::WITH, 'c.id = n.categoria');
        //     $qb->andWhere("c.id = :idcat")->setParameter("idcat", $categoriaId);
        // }
        
        $paginator = new Paginator($query);

        $paginator->getQuery()
            ->setFirstResult($limit*($page-1))
            ->setMaxResults($limit);

        return array('paginator' => $paginator, 'maxPages' => ceil($paginator->count()/$limit));
    }

   




    // /**
    //  * @return Productos[] Returns an array of Productos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Productos
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
