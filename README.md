## How To Start
1. Clone the repository 
2. Go to repo directory and run ```docker compose up -d```
3. Api should be available at http://localhost:80
4. Import data from remote API to local database by sending ```POST``` request to /products/sync

Products data is fetched from remote API and stored in local database, to improve performance and reduce the number of requests to the remote API.
## Endpoints 
If you are using Postman, you can import collection from file ```api_postman_collection.json``` to see all available endpoints.   

```GET``` /products/sync - _sync products form remote API_
```GET``` /products - _get all products_  
```GET``` /products/:id - _get product by id_   
```POST``` /products/sync - _sync products form remote API_   


```GET``` /orders - _get all orders_  
```GET``` /orders/:id - _get order by id_  
```POST``` /orders - _create order_  
```PATCH``` /orders/:id - _update order status_  

#### Example of request body for POST /orders
```
{
    "products": [
        {
            "id": 3,
            "qty": 333
        },
        {
            "id": 4,
            "qty": 331
        }
    ]
}
```
#### Example of request body for PATCH /orders/:id
```
{
    "status": "deleted"
}
```
Newly created orders have status "created" by default.
Status can be changed to "pending" or "deleted".

## Tests
In your shell run ```docker container exec -it php-fpm /bin/bash```. Then, inside the container type command ```bin/phpunit```
## TODO
- [ ] Add more tests
- [ ] Add users authentication and authorization 
- [x] Catch errors and return proper status codes
