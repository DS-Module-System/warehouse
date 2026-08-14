<?php

namespace App\EventListener\Warehouse;

use App\Entity\Delivery\DeliveryItem;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use App\Service\Warehouse\WarehouseService;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: DeliveryItem::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: DeliveryItem::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: DeliveryItem::class)]
class DeliveryListener
{
    public function __construct(
        private WarehouseService $warehouseService
    ) {
    }

    public function postPersist(DeliveryItem $entity): void
    {
        $this->warehouseService->updateStockFromDeliveryItem($entity);
    }

    public function postUpdate(DeliveryItem $entity): void
    {
        $this->warehouseService->updateStockFromDeliveryItem($entity);
    }

    public function postRemove(DeliveryItem $entity): void
    {
        $this->warehouseService->removeStockFromDeliveryItem($entity);
    }
} 