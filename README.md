# laravel-openapi

Open .env file, set the following variables:

```
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```
Running Migrations

```
php artisan migrate
```

Running seeders

```
php artisan db:seed
```

Generate access tokens:

```
php artisan passport:install --force
```

start the first laravel's local development server as api server:

```
php artisan serve
```

start the second laravel's local development server as application server:

```
php artisan serve
```

Open .env file, set the following options:
```
APP_URL=         #api server url
PAYPAL_SANDBOX_CLIENT_ID=
PAYPAL_SANDBOX_CLIENT_SECRET=
```

Login with the following generated users:
user1@gmail.com, user2@gmail.com, user3@gmail.com, user4@gmail.com, user5@gmail.com

Password for all generated users: helloworld

Generate the openapi documentation run the following command:
```
php artisan l5:generate
```

The api documentation can be accessed at /api/documentation or the full url eg. http://127.0.0.1:8001/api/documentation
