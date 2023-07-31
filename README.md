# KEFIS INVENTORY SYSTEM
### KEFIS MONOLITH ARCHITECTURE
### Running the application;

1. Git clone the repository
2. cd into the root folder of the repository
3. run composer install to download the necessary dependencies
4. Edit the .env file with the correct database environment; username and root password
5. Run, "php artisan migrate" to run database migrations
6. Run "php artisan db:seed --class=ProductSeeder" to seed the test products for the application
7. Run "php artisan key:generate" to generate the application key
8. Run "php artisan serve" to start the application on your local machine.

### Available endpoints;

#### Here are the available endpoints along with their URLs and their supposed functionalities:

- Endpoint: Get all products
  
    URL: /api/products
    Method: GET
    Functionality: Retrieves a list of all products with their fulfilled orders.

- Endpoint: Get a specific product
  
    URL: /api/products/{product_id}
    Method: GET
    Functionality: Retrieves details of a specific product identified by its product_id.

- Endpoint: Reduce inventory of a product
  
    URL: /api/products/{product_id}/reduce-inventory
    Method: POST
    Functionality: Reduces the inventory of a specific product identified by its product_id. This endpoint expects a JSON payload containing the quantity to be reduced from the product's inventory; 
    Request body;
```
{
  "quantity": 
}
```

- Endpoint: Dispatch a product
  
    URL: /api/products/{product_id}/dispatch
    Method: POST
    Functionality: Dispatches a product identified by its product_id.

- Endpoint: Reduce inventory of a store product
  
    URL: /api/store_products/{store_product}/reduce-inventory
    Method: POST
    Functionality: Reduces the inventory of a specific store product identified by its store_product. This endpoint expects a JSON payload containing the     quantity to be reduced from the store product's inventory.
    Request body;
    ```
    {
      "quantity": 
    }

- Endpoint: Get all store products
  
    URL: /api/store_products
    Method: GET
    Functionality: Retrieves a list of all store products with their details.

- Endpoint: Get a list of processed orders
  
    URL: /api/processed_orders
    Method: GET
    Functionality: Retrieves a list of processed orders with product details.




