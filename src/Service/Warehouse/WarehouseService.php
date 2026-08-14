<?php

namespace App\Service\Warehouse;

use App\Entity\Delivery\Delivery;
use App\Entity\Delivery\DeliveryItem;
use App\Entity\Warehouse\Warehouse;
use App\Entity\Warehouse\WarehouseStock;
use App\Repository\Warehouse\WarehouseStockRepository;
use Doctrine\ORM\EntityManagerInterface;

class WarehouseService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private WarehouseStockRepository $warehouseStockRepository
    ) {
    }

    /**
     * Обновява наличностите при доставка
     */
    public function updateStockFromDelivery(Delivery $delivery): void
    {
        foreach ($delivery->getItems() as $item) {
            $this->updateStockFromDeliveryItem($item);
        }
    }

    /**
     * Обновява наличността за конкретен продукт от доставка
     */
    public function updateStockFromDeliveryItem(DeliveryItem $item): void
    {
        $product = $item->getProduct();
        $quantity = $item->getQuantity();

        if (!$product || !$quantity) {
            return;
        }

        $warehouse = $item->getDelivery()->getWarehouse();
        
        if (!$warehouse) {
            return;
        }

        $stock = $this->warehouseStockRepository->findByWarehouseAndProduct(
            $warehouse->getId(),
            $product->getId()
        );

        if (!$stock) {
            // Създаваме нова наличност 	
            $stock = new WarehouseStock();
            $stock->setWarehouse($warehouse);
            $stock->setProduct($product);
            $stock->setQuantity('0.00');
            $stock->setCreatedBy($item->getDelivery()->getCreatedBy());
        }

        // Увеличаваме наличността
        $currentQuantity = (float) $stock->getQuantity();
        $newQuantity = $currentQuantity + (float) $quantity;
        $stock->setQuantity(number_format($newQuantity, 2, '.', ''));
        $stock->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($stock);
        $this->entityManager->flush();
    }

    /**
     * Намалява наличността при премахване на артикул от доставка
     */
    public function removeStockFromDeliveryItem(DeliveryItem $item): void
    {
        $product = $item->getProduct();
        $quantity = $item->getQuantity();

        if (!$product || !$quantity) {
            return;
        }

        $warehouse = $item->getDelivery()->getWarehouse();
        
        if (!$warehouse) {
            return;
        }

        $stock = $this->warehouseStockRepository->findByWarehouseAndProduct(
            $warehouse->getId(),
            $product->getId()
        );

        if (!$stock) {
            return; // Няма наличност за намаляване
        }

        // Намаляваме наличността
        $currentQuantity = (float) $stock->getQuantity();
        $newQuantity = $currentQuantity - (float) $quantity;
        
        // Не позволяваме отрицателна наличност
        if ($newQuantity < 0) {
            $newQuantity = 0;
        }
        
        $stock->setQuantity(number_format($newQuantity, 2, '.', ''));
        $stock->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($stock);
        $this->entityManager->flush();
    }

    /**
     * Връща първия склад като основен
     * В бъдеще може да се добави логика за избор на склад
     */
    private function getDefaultWarehouse(): ?Warehouse
    {
        $warehouseRepository = $this->entityManager->getRepository(Warehouse::class);
        return $warehouseRepository->findOneBy([]);
    }

    /**
     * Връща наличностите за конкретен склад
     */
    public function getStocksByWarehouse(int $warehouseId): array
    {
        return $this->warehouseStockRepository->findByWarehouse($warehouseId);
    }

    /**
     * Връща наличностите за конкретен продукт
     */
    public function getStocksByProduct(int $productId): array
    {
        return $this->warehouseStockRepository->findByProduct($productId);
    }

    /**
     * Връща общата наличност за продукт във всички складове
     */
    public function getTotalStockForProduct(int $productId): float
    {
        $stocks = $this->getStocksByProduct($productId);
        $total = 0.0;

        foreach ($stocks as $stock) {
            $total += (float) $stock->getQuantity();
        }

        return $total;
    }
} 