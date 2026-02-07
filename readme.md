# Restaurant Management System

A Laravel-based restaurant management system that helps manage orders, menus, and staff.

## Requirements

- PHP >= 7.4
- MySQL >= 5.7
- Composer
- Node.js & NPM
- Git

## Installation Steps

1. Clone the repository

```bash
git clone https://github.com/neddy1298/restaurant-laravel.git
cd restaurant-laravel
```

2. Install PHP dependencies

```bash
composer install
```

3. Install NPM dependencies

```bash
npm install
```

4. Create and setup .env file

```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your database in .env file

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_restaurant
DB_USERNAME=root
DB_PASSWORD=
```

6. Run database migrations and seeders

```bash
php artisan migrate:fresh --seed
```

7. Compile assets

```bash
npm run dev
```

8. Start the development server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Default Users

Default users are created during seeding.

You should set the passwords for these users in your `.env` file before running the seeders using the following variables:

- `OWNER_PASSWORD` (for owner@resto.com)
- `ADMIN_PASSWORD` (for admin@resto.com)
- `WAITER_PASSWORD` (for waiter@resto.com)
- `CASHIER_PASSWORD` (for kasir@resto.com)
- `CUSTOMER_PASSWORD` (for pelanggan@resto.com)

If these variables are not set, random passwords will be generated and displayed in the console output when you run `php artisan migrate:fresh --seed`.

## Features

- User Management (Admin, Staff, etc.)
- Menu Management
- Order Processing
- Transaction History
- Reporting

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
