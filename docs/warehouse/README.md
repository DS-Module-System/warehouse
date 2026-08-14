# Warehouse Module

## Overview

The Warehouse module provides comprehensive warehouse management functionality for the ERP system. It allows users to manage warehouses, track product stock levels, and automatically update inventory when deliveries are received.

## Features

- **Warehouse Management**: Create, edit, and manage warehouses with addresses and descriptions
- **Stock Tracking**: Track product quantities in each warehouse
- **Automatic Stock Updates**: Automatically update stock levels when deliveries are received
- **Multi-warehouse Support**: Manage multiple warehouses with separate stock tracking
- **Search and Filter**: Advanced search capabilities for warehouses and stock levels
- **Integration**: Seamless integration with Product and Delivery modules

## Entity Structure

### Warehouse Entity

The Warehouse entity contains the following fields:

- **id** (int): Primary key
- **name** (string, 255 chars): Warehouse name (required)
- **address** (text, nullable): Warehouse address
- **description** (text, nullable): Warehouse description
- **createdAt** (datetime): Creation timestamp
- **createdBy** (BaseUser): User who created the warehouse
- **updatedAt** (datetime, nullable): Last update timestamp

### WarehouseStock Entity

The WarehouseStock entity tracks product quantities in warehouses:

- **id** (int): Primary key
- **warehouse** (Warehouse): Reference to warehouse
- **product** (Product): Reference to product
- **quantity** (decimal, 10.2): Product quantity in warehouse
- **createdAt** (datetime): Creation timestamp
- **createdBy** (BaseUser): User who created the stock entry
- **updatedAt** (datetime, nullable): Last update timestamp

**Unique Constraint**: Each warehouse-product combination can have only one stock entry.

## Installation

The Warehouse module is automatically installed with the system. No additional configuration is required.

## Usage

### Accessing Warehouses

Navigate to the "Warehouses" menu item in the left sidebar to access the warehouse management interface.

### Creating a Warehouse

1. Click the "New" button on the warehouses list page
2. Fill in the required fields:
   - **Name**: Warehouse name (required, max 255 characters)
   - **Address**: Warehouse address (optional)
   - **Description**: Warehouse description (optional)
3. Click "Save" to create the warehouse

### Managing Stock

1. Navigate to "Warehouse Stocks" to view all stock levels
2. Click "New" to add stock entries manually
3. Select warehouse and product
4. Enter quantity
5. Save the stock entry

### Automatic Stock Updates

When a delivery is created or updated:
1. The system automatically identifies products in the delivery
2. Stock levels are updated in the default warehouse
3. If no stock entry exists for a product, one is created
4. Quantities are added to existing stock levels

## Integration

### With Product Module

- Stock entries reference products from the Product module
- Product information (name, measure unit) is displayed in stock lists

### With Delivery Module

- Automatic stock updates when deliveries are processed
- Delivery items are used to calculate stock increases
- User who created the delivery is recorded as stock creator

## API Endpoints

The module provides the following REST endpoints:

### Warehouses
- `GET /warehouses` - List all warehouses
- `GET /warehouses/create` - Show create form
- `POST /warehouses/create` - Create new warehouse
- `GET /warehouses/{id}/edit` - Show edit form
- `POST /warehouses/{id}/edit` - Update warehouse
- `POST /warehouses/deletes` - Delete multiple warehouses

### Warehouse Stocks
- `GET /warehouse-stocks` - List all stock entries
- `GET /warehouse-stocks/create` - Show create form
- `POST /warehouse-stocks/create` - Create new stock entry
- `GET /warehouse-stocks/{id}/edit` - Show edit form
- `POST /warehouse-stocks/{id}/edit` - Update stock entry
- `POST /warehouse-stocks/deletes` - Delete multiple stock entries

## Database Schema

```sql
CREATE TABLE warehouse (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address LONGTEXT DEFAULT NULL,
    description LONGTEXT DEFAULT NULL,
    created_at DATETIME NOT NULL,
    created_by_id INT DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    FOREIGN KEY (created_by_id) REFERENCES base_user(id)
);

CREATE TABLE warehouse_stock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    warehouse_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    created_at DATETIME NOT NULL,
    created_by_id INT DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    FOREIGN KEY (warehouse_id) REFERENCES warehouse(id),
    FOREIGN KEY (product_id) REFERENCES product(id),
    FOREIGN KEY (created_by_id) REFERENCES base_user(id),
    UNIQUE KEY unique_warehouse_product (warehouse_id, product_id)
);
```

## Translation Keys

### English (warehouse.en.yaml)
- `leftMenu.warehouses`: 'Warehouses'
- `createWarehouseTitle`: 'Create Warehouse'
- `editWarehouseTitle`: 'Edit Warehouse'
- `createWarehouseStockTitle`: 'Create Stock'
- `editWarehouseStockTitle`: 'Edit Stock'
- `name`: 'Name'
- `address`: 'Address'
- `description`: 'Description'
- `warehouse`: 'Warehouse'
- `product`: 'Product'
- `quantity`: 'Quantity'
- `measure`: 'Measure Unit'
- `created_at`: 'Created At'
- `updated_at`: 'Updated At'
- `created_by`: 'Created By'
- `quantityFrom`: 'Quantity From'
- `quantityTo`: 'Quantity To'

### Bulgarian (warehouse.bg.yaml)
- `leftMenu.warehouses`: 'Складове'
- `createWarehouseTitle`: 'Създаване на склад'
- `editWarehouseTitle`: 'Редактиране на склад'
- `createWarehouseStockTitle`: 'Създаване на наличност'
- `editWarehouseStockTitle`: 'Редактиране на наличност'
- `name`: 'Име'
- `address`: 'Адрес'
- `description`: 'Описание'
- `warehouse`: 'Склад'
- `product`: 'Продукт'
- `quantity`: 'Количество'
- `measure`: 'Мярна единица'
- `created_at`: 'Създадена в'
- `updated_at`: 'Обновена в'
- `created_by`: 'Създадена от'
- `quantityFrom`: 'Количество от'
- `quantityTo`: 'Количество до'

## Dependencies

The Warehouse module depends on:
- Core Module System
- Product Module
- Delivery Module
- User Module

## Future Enhancements

Potential future features:
- Multiple warehouse selection in deliveries
- Stock transfers between warehouses
- Low stock alerts
- Stock movement history
- Barcode integration
- Advanced reporting
- Stock reservations
- Batch operations 