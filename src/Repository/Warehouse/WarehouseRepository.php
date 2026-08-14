<?php

namespace App\Repository\Warehouse;

use App\Entity\Warehouse\Warehouse;
use App\Repository\Core\CoreRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Warehouse>
 *
 * @method Warehouse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Warehouse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Warehouse[]    findAll()
 * @method Warehouse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarehouseRepository extends ServiceEntityRepository implements CoreRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Warehouse::class);
    }

    public function save(Warehouse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Warehouse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getPaginatedQuery(array $searchFormData = []): Query
    {
        $qb = $this->createQueryBuilder('w');
        
        // Търсене по име
        if (!empty($searchFormData['name'])) {
            $qb->andWhere('w.name LIKE :name')
               ->setParameter('name', '%' . $searchFormData['name'] . '%');
        }
        
        // Търсене по адрес
        if (!empty($searchFormData['address'])) {
            $qb->andWhere('w.address LIKE :address')
               ->setParameter('address', '%' . $searchFormData['address'] . '%');
        }
        
        // Търсене по дата от
        if (!empty($searchFormData['dateFrom'])) {
            $qb->andWhere('w.createdAt >= :dateFrom')
               ->setParameter('dateFrom', $searchFormData['dateFrom']);
        }
        
        // Търсене по дата до
        if (!empty($searchFormData['dateTo'])) {
            $qb->andWhere('w.createdAt <= :dateTo')
               ->setParameter('dateTo', $searchFormData['dateTo']);
        }
        
        // Сортиране
        $qb->orderBy('w.createdAt', 'DESC');
        
        return $qb->getQuery();
    }
} 