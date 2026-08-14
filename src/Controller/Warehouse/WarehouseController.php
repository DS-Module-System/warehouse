<?php

namespace App\Controller\Warehouse;

use App\Controller\Core\CoreBaseController;
use App\Entity\Warehouse\Warehouse;
use App\Form\Warehouse\WarehouseForm;
use App\Form\Warehouse\WarehouseSearchForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/warehouses', name: 'warehouse_')]
class WarehouseController extends CoreBaseController
{
    protected string $entityClass = Warehouse::class;
    protected string $formClass = WarehouseForm::class;
    protected string $searchFormClass = WarehouseSearchForm::class;
    protected string $moduleTemplateName = 'warehouse';

    #[Route(path: '', name: 'list')]
    #[IsGranted('ROLE_WAREHOUSE_VIEW')] 
    public function list(Request $request): Response
    {
        return $this->baseList($request, $request->query->getInt('page', 1));
    }

    #[Route(path: '/create', name: 'create')]
    #[IsGranted('ROLE_WAREHOUSE_CREATE')] 
    public function create(Request $request): Response
    {
        $this->callbacks['preCreatePersist'] = function (Warehouse $entity) {
            $entity->setCreatedBy($this->getUser());
            return $entity;
        };

        return $this->baseCreate($request);
    }

    #[Route(path: '/{id}/edit', name: 'edit')]
    #[IsGranted('ROLE_WAREHOUSE_EDIT')] 
    public function edit($id, Request $request): Response
    {
        $this->callbacks['preEditPersist'] = function (Warehouse $entity) {
            $entity->setUpdatedAt(new \DateTimeImmutable());
            return $entity;
        };

        return $this->baseEdit($request, $id);
    }

    #[Route(path: '/deletes', name: 'deletes')]
    #[IsGranted('ROLE_WAREHOUSE_DELETE')] 
    public function deletes(Request $request): Response
    {
        return $this->baseDeletes($request);
    }
} 