# Warehouse Module - Installation Guide

This comprehensive guide covers the complete installation and configuration of the ERP Warehouse Module.

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Installation Steps](#installation-steps)
3. [Configuration](#configuration)
4. [Database Setup](#database-setup)
5. [Menu Integration](#menu-integration)
6. [Security Configuration](#security-configuration)
7. [Testing](#testing)
8. [Troubleshooting](#troubleshooting)

## Prerequisites

### System Requirements
- **PHP**: 8.0 or higher
- **Symfony**: 6.0 or higher
- **Database**: MySQL 8.0 or higher
- **Composer**: Latest version
- **Web Server**: Apache/Nginx

### Required Extensions
- PHP PDO extension
- PHP Doctrine extensions
- PHP XML extension
- PHP JSON extension

### Dependencies
- ERP Core Module System
- Product Module
- Delivery Module
- User Module
- Symfony Form component
- Doctrine ORM
- Twig templating engine

## Installation Steps

### Step 1: Verify Core System

Ensure the ERP Core Module System is properly installed:

```bash
# Check if core system is working
php bin/console about
```

### Step 2: Database Migration

Generate and run the database migration for the Warehouse entities:

```bash
# Generate migration
php bin/console make:migration

# Review the generated migration file
# It should be in migrations/ directory

# Run the migration
php bin/console doctrine:migrations:migrate
```

### Step 3: Clear Cache

Clear the application cache to ensure new routes and configurations are loaded:

```bash
# Clear cache
php bin/console cache:clear

# Clear cache in production (if applicable)
php bin/console cache:clear --env=prod
```

### Step 4: Verify Installation

Test the module installation:

```bash
# Check if routes are registered
php bin/console debug:router | grep warehouse

# Check if entities are recognized
php bin/console debug:container | grep Warehouse
```

## Configuration

### Entity Configuration

The Warehouse module includes two main entities:

#### Warehouse Entity
- **name**: String field for warehouse name (required, max 255 characters)
- **address**: Text field for warehouse address (optional)
- **description**: Text field for warehouse description (optional)
- **createdAt**: DateTime field for creation timestamp
- **createdBy**: ManyToOne relationship with BaseUser
- **updatedAt**: DateTime field for last update timestamp

#### WarehouseStock Entity
- **warehouse**: ManyToOne relationship with Warehouse
- **product**: ManyToOne relationship with Product
- **quantity**: Decimal field for product quantity (precision: 10, scale: 2)
- **createdAt**: DateTime field for creation timestamp
- **createdBy**: ManyToOne relationship with BaseUser
- **updatedAt**: DateTime field for last update timestamp

**Unique Constraint**: Each warehouse-product combination can have only one stock entry.

### Form Configuration

The module includes four form types:

1. **WarehouseForm**: Main form for creating/editing warehouses
2. **WarehouseSearchForm**: Search form for filtering warehouses
3. **WarehouseStockForm**: Form for creating/editing stock entries
4. **WarehouseStockSearchForm**: Search form for filtering stock entries

### Repository Configuration

The repositories extend ServiceEntityRepository and provide:

- Basic CRUD operations
- Pagination support
- Search functionality
- Custom methods for stock management

### Service Configuration

The WarehouseService provides:

- Automatic stock updates from deliveries
- Stock management methods
- Warehouse selection logic

### Event Listener Configuration

The DeliveryListener automatically:

- Updates stock levels when deliveries are created
- Updates stock levels when deliveries are modified
- Creates new stock entries if they don't exist

## Database Setup

### Table Structure

The warehouse table includes:

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
```

The warehouse_stock table includes:

```sql
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

### Indexes

Recommended indexes for performance:

```sql
CREATE INDEX idx_warehouse_name ON warehouse(name);
CREATE INDEX idx_warehouse_created_at ON warehouse(created_at);
CREATE INDEX idx_warehouse_stock_warehouse ON warehouse_stock(warehouse_id);
CREATE INDEX idx_warehouse_stock_product ON warehouse_stock(product_id);
CREATE INDEX idx_warehouse_stock_quantity ON warehouse_stock(quantity);
```

## Menu Integration

### Automatic Integration

The warehouse module automatically integrates with the menu system through:

- Route configuration in WarehouseController and WarehouseStockController
- Translation keys for menu items
- Role-based access control

### Manual Menu Configuration

If manual configuration is needed, add to your menu configuration:

```yaml
# config/packages/menu.yaml
warehouse_menu:
    label: 'warehouse.leftMenu.warehouses'
    route: 'warehouse_list'
    icon: 'fas fa-warehouse'
    roles: ['ROLE_WAREHOUSE_VIEW']
```

## Security Configuration

### Role Configuration

The module includes the following roles:

```yaml
# config/roles/warehouse.yaml
parameters:
    warehouse_roles:
        "ROLE_WAREHOUSE_VIEW": "Can view warehouse information"
        "ROLE_WAREHOUSE_CREATE": "Can create new warehouses"
        "ROLE_WAREHOUSE_EDIT": "Can edit warehouse information"
        "ROLE_WAREHOUSE_DELETE": "Can delete warehouses"
        "ROLE_WAREHOUSE_STOCK_VIEW": "Can view warehouse stock information"
        "ROLE_WAREHOUSE_STOCK_CREATE": "Can create new warehouse stock entries"
        "ROLE_WAREHOUSE_STOCK_EDIT": "Can edit warehouse stock information"
        "ROLE_WAREHOUSE_STOCK_DELETE": "Can delete warehouse stock entries"
```

### Access Control

Configure access control in your security configuration:

```yaml
# config/packages/security.yaml
security:
    access_control:
        - { path: ^/warehouses, roles: ROLE_WAREHOUSE_VIEW }
        - { path: ^/warehouse-stocks, roles: ROLE_WAREHOUSE_STOCK_VIEW }
```

## Testing

### 1. Test Warehouse Management

1. Navigate to the Warehouses page in your browser
2. Try creating a new warehouse with the following test data:
   - Name: "Main Warehouse"
   - Address: "123 Main Street, City"
   - Description: "Primary warehouse for all products"
3. Verify that the warehouse is saved and appears in the list
4. Test the edit functionality by modifying the warehouse
5. Test the search functionality by filtering by name or address

### 2. Test Stock Management

1. Navigate to the Warehouse Stocks page
2. Try creating a new stock entry with test data
3. Verify that the stock entry is saved and appears in the list
4. Test the search functionality by filtering by warehouse or product

### 3. Test Integration with Deliveries

1. Create a delivery with products
2. Verify that stock levels are automatically updated
3. Check that the stock entries are created for new products

## Troubleshooting

### Common Issues

1. **Routes not found**: Clear the cache and verify the controller is properly registered
2. **Database errors**: Ensure the migration was executed successfully
3. **Translation issues**: Check that the translation files are in the correct location
4. **Stock not updating**: Verify that the DeliveryListener is properly registered
5. **Form validation errors**: Ensure all required fields are properly validated

### Debug Commands

```bash
# Check entity relationships
php bin/console doctrine:schema:validate

# Generate SQL for relationships
php bin/console doctrine:schema:update --dump-sql

# Check if routes are registered
php bin/console debug:router | grep warehouse

# Check if event listener is registered
php bin/console debug:container | grep DeliveryListener

# Clear cache after changes
php bin/console cache:clear
```

### Verification Commands

```bash
# Check if the Warehouse entity is recognized
php bin/console doctrine:schema:validate

# Check if all routes are registered
php bin/console debug:router | grep warehouse

# Check if the event listener is registered
php bin/console debug:container | grep DeliveryListener
```

## Next Steps

After successful installation:

1. Configure user roles and permissions
2. Set up multiple warehouses if needed
3. Configure stock alerts
4. Implement stock transfers
5. Add barcode integration
6. Set up advanced reporting

The Warehouse module is now ready for production use and provides a solid foundation for warehouse management in your ERP system. 