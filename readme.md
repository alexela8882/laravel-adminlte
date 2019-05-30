# About

Laravel + AdminLTE is a ready to use Laravel app with [AdminLTE](https://adminlte.io/) Free premium.

This also includes the following:

1. [SweetAlert2](https://sweetalert2.github.io/) - for replacement of default alert
2. [spatie/laravel-permission](https://github.com/spatie/laravel-permission) - for roles & permissions
3. [dataTables.jaa.ajaxcrud](https://github.com/alexela8882/laravel-adminlte/blob/master/public/plugins/dataTables.jaa.ajaxcrud/) - custom ajax CRUD for AdminLTE Datatables

## Get Started

### Clone

```sh
$ git clone https://github.com/alexela8882/laravel-material-kit.git projectname
```

### Composer

`cd` into root folder of the project and run this command to install all dependencies

```sh
$ composer install
```

## Configure Backend

Cloning this project wont provide you a `.env` file. You can create using this command:

```sh
$ php -r "copy('.env.example', '.env');"
```

### Migrate & Seeder

Run this command after you done setting up your database and updating your `.env` file:

```sh
$ php artisan migrate
$ php artisan db:seed
```

### Generate key

```sh
$ php artisan key:generate
```

### Lastly

```sh
$ php artisan serve
```

## All Done!

You can now visit your website in [http://localhost:8000](http://localhost:8000).
