## How to install

- This project is build up using Laravel sail Since this project contains an elastic search running in a docker container.
- Clone the project using `git clone https://github.com/amitleuva1987/book_store_backend.git`
- Copy `.env.example` file to `.env` and edit database credentials there
- Run `docker run --rm \ -u "$(id -u):$(id -g)" \ -v $(pwd):/var/www/html \ -w /var/www/html \ laravelsail/php81-composer:latest \ composer install --ignore-platform-reqs`
- Run `sail artisan key:generate`
- Run `sail artisan migrate` && `sail artisan db:seed`
