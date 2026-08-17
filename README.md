# Warehouse

Складове и наличности по продукт. Обновява стоката при доставки и се ползва от поръчки и производство.

## Функционалност

- CRUD на складове
- CRUD на складови наличности (уникална двойка склад + продукт)
- Автоматично увеличаване на количеството при доставка (`DeliveryListener`)

## Интеграция в системата

Copy-in модул: файловете се копират в хоста под `App\`.

- Пътища: `src/Controller|Entity|EventListener|Form|Repository|Service/Warehouse/`, `templates/warehouse/`, `templates/warehouse_stock/`, `translations/warehouse*.yaml`, `config/roles/warehouse.yaml`
- Меню: Складове и Складови наличности при `ROLE_WAREHOUSE_VIEW` / `ROLE_WAREHOUSE_STOCK_VIEW`
- Роли: `ROLE_WAREHOUSE_{VIEW,CREATE,EDIT,DELETE}`, `ROLE_WAREHOUSE_STOCK_{VIEW,CREATE,EDIT,DELETE}`
- Маршрути: `/warehouses`, `/warehouse-stocks`

Зависи от **product**. Слуша **delivery** (`DeliveryItem`). Използва се от **order** и **production**.

## Структура

- `WarehouseController`, `WarehouseStockController`
- Ентитети: `Warehouse`, `WarehouseStock`
- `WarehouseService`
- `EventListener\Warehouse\DeliveryListener` — persist/update/remove на редове от доставка

## Зависимости

- **erp-core**
- **product**
- **delivery** (за listener-а; без него наличностите се управляват ръчно)

## Документация

- [docs/warehouse/README.md](docs/warehouse/README.md)
- [docs/warehouse/installation-guide.md](docs/warehouse/installation-guide.md)
- [docs/warehouse/quick-start.md](docs/warehouse/quick-start.md)
