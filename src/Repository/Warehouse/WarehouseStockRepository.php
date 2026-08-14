<?php

namespace App\Repository\Warehouse;

use App\Entity\Warehouse\WarehouseStock;
use App\Repository\Core\CoreRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WarehouseStock>
 *
 * @method WarehouseStock|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarehouseStock|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarehouseStock[]    findAll()
 * @method WarehouseStock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarehouseStockRepository extends ServiceEntityRepository implements CoreRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarehouseStock::class);
    }

    public function save(WarehouseStock $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WarehouseStock $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getPaginatedQuery(array $searchFormData = []): Query
    {
        $qb = $this->createQueryBuilder('ws')
            ->leftJoin('ws.warehouse', 'w')
            ->leftJoin('ws.product', 'p')
            ->addSelect('w', 'p');
        
        // Търсене по склад
        if (!empty($searchFormData['warehouse'])) {
            $qb->andWhere('w.id = :warehouse')
               ->setParameter('warehouse', $searchFormData['warehouse']);
        }
        
        // Търсене по продукт
        if (!empty($searchFormData['product'])) {
            $qb->andWhere('p.id = :product')
               ->setParameter('product', $searchFormData['product']);
        }
        
        // Търсене по количество от
        if (!empty($searchFormData['quantityFrom'])) {
            $qb->andWhere('ws.quantity >= :quantityFrom')
               ->setParameter('quantityFrom', $searchFormData['quantityFrom']);
        }
        
        // Търсене по количество до
        if (!empty($searchFormData['quantityTo'])) {
            $qb->andWhere('ws.quantity <= :quantityTo')
               ->setParameter('quantityTo', $searchFormData['quantityTo']);
        }
        
        // Сортиране
        $qb->orderBy('w.name', 'ASC')
            ->addOrderBy('p.name', 'ASC');
        
        return $qb->getQuery();
    }

    /**
     * Намира наличност за конкретен склад и продукт
     */
    public function findByWarehouseAndProduct(int $warehouseId, int $productId): ?WarehouseStock
    {
        return $this->findOneBy([
            'warehouse' => $warehouseId,
            'product' => $productId
        ]);
    }

    /**
     * Връща всички наличности за конкретен склад
     */
    public function findByWarehouse(int $warehouseId): array
    {
        return $this->createQueryBuilder('ws')
            ->leftJoin('ws.product', 'p')
            ->addSelect('p')
            ->where('ws.warehouse = :warehouseId')
            ->setParameter('warehouseId', $warehouseId)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Връща всички наличности за конкретен продукт
     */
    public function findByProduct(int $productId): array
    {
        return $this->createQueryBuilder('ws')
            ->leftJoin('ws.warehouse', 'w')
            ->addSelect('w')
            ->where('ws.product = :productId')
            ->setParameter('productId', $productId)
            ->orderBy('w.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
} 