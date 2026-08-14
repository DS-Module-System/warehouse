<?php

namespace App\Controller\Warehouse;

use App\Controller\Core\CoreBaseController;
use App\Entity\Warehouse\WarehouseStock;
use App\Form\Warehouse\WarehouseStockForm;
use App\Form\Warehouse\WarehouseStockSearchForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/warehouse-stocks', name: 'warehouse_stock_')]
class WarehouseStockController extends CoreBaseController
{
    protected string $entityClass = WarehouseStock::class;
    protected string $formClass = WarehouseStockForm::class;
    protected string $searchFormClass = WarehouseStockSearchForm::class;
    protected string $moduleTemplateName = 'warehouse_stock';

    #[Route(path: '', name: 'list')]
    #[IsGranted('ROLE_WAREHOUSE_STOCK_VIEW')] 
    public function list(Request $request): Response
    {
        return $this->baseList($request, $request->query->getInt('page', 1));
    }

    #[Route(path: '/create', name: 'create')]
    #[IsGranted('ROLE_WAREHOUSE_STOCK_CREATE')] 
    public function create(Request $request): Response
    {
        $this->callbacks['preCreatePersist'] = function (WarehouseStock $entity) {
            $entity->setCreatedBy($this->getUser());
            return $entity;
        };

        return $this->baseCreate($request);
    }

    #[Route(path: '/{id}/edit', name: 'edit')]
    #[IsGranted('ROLE_WAREHOUSE_STOCK_EDIT')] 
    public function edit($id, Request $request): Response
    {
        $this->callbacks['preEditPersist'] = function (WarehouseStock $entity) {
            $entity->setUpdatedAt(new \DateTimeImmutable());
            return $entity;
        };

        return $this->baseEdit($request, $id);
    }

    #[Route(path: '/deletes', name: 'deletes')]
    #[IsGranted('ROLE_WAREHOUSE_STOCK_DELETE')] 
    public function deletes(Request $request): Response
    {
        return $this->baseDeletes($request);
    }
} 