# Warehouse Module - Quick Start Guide

This guide provides a quick setup for the ERP Warehouse Module.

## Prerequisites

- PHP 8.0 or higher
- Symfony 6.0 or higher
- MySQL 8.0 or higher
- Composer

## Quick Installation

### 1. Database Migration

```bash
# Generate migration for Warehouse entities
php bin/console make:migration

# Run the migration
php bin/console doctrine:migrations:migrate
```

### 2. Clear Cache

```bash
# Clear application cache
php bin/console cache:clear
```

### 3. Verify Installation

Access the warehouse module at: `/warehouses`

## Module Features

### Core Functionality
- **Create Warehouses**: Add new warehouse records with name, address, and description
- **Edit Warehouses**: Modify existing warehouse information
- **List Warehouses**: View all warehouses with search and pagination
- **Delete Warehouses**: Remove warehouse records
- **Manage Stock**: Track product quantities in each warehouse
- **Automatic Updates**: Stock levels update automatically when deliveries are received

### Fields Available

#### Warehouse
- **Name**: Warehouse name (required, max 255 characters)
- **Address**: Warehouse address (optional)
- **Description**: Warehouse description (optional)
- **Created At**: Timestamp when the record was created
- **Created By**: User who created the warehouse record
- **Updated At**: Timestamp when the record was last updated

#### Warehouse Stock
- **Warehouse**: Reference to warehouse
- **Product**: Reference to product
- **Quantity**: Product quantity in warehouse (decimal, 2 places)
- **Created At**: Timestamp when the stock entry was created
- **Created By**: User who created the stock entry
- **Updated At**: Timestamp when the stock entry was last updated

## Basic Usage

### Creating a Warehouse
1. Navigate to `/warehouses`
2. Click "New" button
3. Fill in the required fields:
   - Name (required)
   - Address (optional)
   - Description (optional)
4. Save the warehouse

### Managing Stock
1. Navigate to `/warehouse-stocks`
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

### Menu Integration
The warehouse module automatically integrates with the main menu system.

### User Permissions
The module includes role-based access control:
- `ROLE_WAREHOUSE_VIEW`: View warehouses
- `ROLE_WAREHOUSE_CREATE`: Create new warehouses
- `ROLE_WAREHOUSE_EDIT`: Edit existing warehouses
- `ROLE_WAREHOUSE_DELETE`: Delete warehouses
- `ROLE_WAREHOUSE_STOCK_VIEW`: View warehouse stock
- `ROLE_WAREHOUSE_STOCK_CREATE`: Create new stock entries
- `ROLE_WAREHOUSE_STOCK_EDIT`: Edit existing stock entries
- `ROLE_WAREHOUSE_STOCK_DELETE`: Delete stock entries

## Troubleshooting

### Common Issues

1. **Routes not found**: Clear the cache and verify the controller is properly registered
2. **Database errors**: Ensure the migration was executed successfully
3. **Translation issues**: Check that the translation files are in the correct location
4. **Stock not updating**: Verify that the DeliveryListener is properly registered

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

Once you're comfortable with basic warehouse management, consider:

- Setting up multiple warehouses
- Configuring stock alerts
- Implementing stock transfers
- Adding barcode integration
- Setting up advanced reporting

The Warehouse module is designed to be simple yet powerful, providing a solid foundation for warehouse management in your ERP system. 