## How to install

- This project is build up using Laravel sail Since this project contains an elastic search running in a docker container.
- Clone the project using `git clone https://github.com/amitleuva1987/book_store_backend.git`
- Copy `.env.example` file to `.env` and edit database credentials there
- Run `docker run --rm \ -u "$(id -u):$(id -g)" \ -v $(pwd):/var/www/html \ -w /var/www/html \ laravelsail/php81-composer:latest \ composer install --ignore-platform-reqs`
- Run `sail artisan key:generate`
- place below variables in .env file
- `SCOUT_DRIVER=elasticsearch`
- `ELASTICSEARCH_INDEX=scout`
- `ELASTICSEARCH_HOST=elasticsearch:9200`
- Run `sail artisan migrate` && `sail artisan db:seed` (data will get imported from https://fakerapi.it/api/v1/books?_quantity=100)
- Run `sail artisan scout:import 'App\Models\Product'` to make the search index

## API Implementation

below is the api list of the book store backend

1 . GET (request type) -> 'api/products' (route name) -> ProductController@index (controller and method)
this route return all the product (books) paginated data.

2 . GET (request type) -> api/products/{product} (route name) -> ProductController@show (controller and method)
this route returns a single product (book).

3 . POST (request type) -> api/search (route name) -> ProductSearchController@search (controller name and method)
this route expect the search query and filtery type as parameters and returns search data from elastic search

4 . POST (request type) -> api/login (route name) -> LoginController (controller name)
this route is used for login into the admin dashboard

5 . POST (request type) -> api/logout (route name) -> LogoutController (controller name)
this route name is for logging out the user

6 . GET (request type) -> api/user (route name)
this route returns logged in user

7 . POST (request type) -> api/products (route name) -> ProductController@store (controller name and method)
this route is used to create the new book record from admin dashboard

8 . POST (request type) -> api/update_product/{id} (route name) -> ProductController@productUpdate (controller name and method)
this route is used to update book data record from the admin dashboard

9 . DELETE (request type) -> api/products/{product} (route name) -> ProductController@destroy (controller name and method)
this route is used to delete book record from the admin dashboard

10 . GET (request type) -> api/get_image/{id} (route name)
this route is used to retrive product image.
