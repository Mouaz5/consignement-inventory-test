# Consignment Inventory Management System

A modern web application for managing consignment inventory with vendor management, goods tracking, and receipt generation. Built with Laravel, Inertia.js, and Vue 3.

## Features

- **User Authentication** - Sanctum token-based API authentication
- **Role-Based Access** - Support for vendors and regular users
- **Inventory Management** - List and filter goods by vendor, category, name, and date range
- **Vendor Management** - Vendors can add, edit, and delete their goods
- **Receipt Generation** - Generate and print receipts for selected vendors
- **Category Management** - Create and manage product categories
- **Responsive Design** - Beautiful UI with TailwindCSS
- **Pagination** - Efficient data loading with pagination

## Tech Stack

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Vue 3, Inertia.js, TailwindCSS
- **Authentication**: Laravel Sanctum
- **Database**: MySQL
- **Build Tool**: Vite

## Prerequisites

Before you begin, ensure you have the following installed:

- **PHP** 8.2 or higher
- **Composer** (PHP package manager)
- **Node.js** 18+ and npm
- **Git**

## Installation & Setup

### 1. Clone the Repository

```bash
git clone <repository-url>
cd consignment-inventory
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the example environment file and generate an application key:

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Database Setup

configure MySQL in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=consignment_inventory
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Run Migrations

```bash
php artisan migrate
```

### 7. Seed Categories (Optional)

```bash
php artisan db:seed
```

Or manually add categories via the UI.

## Running the Project

### Development Mode

**Terminal 1 - Start Laravel Development Server:**

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

**Terminal 2 - Start Vite Development Server:**

```bash
npm run dev
```

This enables hot module reloading for Vue components.

### Production Build

```bash
npm run build
php artisan serve
```

## Project Structure

```
consignment-inventory/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php
│   │       ├── InventoryController.php
│   │       ├── GoodsController.php
│   │       ├── CategoryController.php
│   │       └── ProfileController.php
│   └── Models/
│       ├── User.php
│       ├── Vendor.php
│       ├── Good.php
│       ├── Category.php
│       └── Receipt.php
├── database/
│   └── migrations/
├── resources/
│   ├── js/
│   │   ├── Pages/
│   │   │   ├── Auth/
│   │   │   ├── Inventory/
│   │   │   ├── Vendor/Goods/
│   │   │   └── Categories/
│   │   ├── Components/
│   │   ├── Layouts/
│   │   └── app.js
│   └── views/
│       └── app.blade.php
├── routes/
│   ├── web.php
│   └── api.php
└── composer.json
```

## Usage

### Register & Login

1. Visit `http://localhost:8000/register`
2. Create an account with:
   - Name, Email, Password
   - Phone, Address
   - Role (Vendor or User)
3. Login at `http://localhost:8000/login`

### For Regular Users

- View all inventory items
- Filter by vendor, category, name, or date range
- Generate and print receipts for vendors

### For Vendors

- Manage your goods (Add, Edit, Delete)
- Create new categories if needed
- View your inventory

## API Endpoints

### Authentication
- `POST /api/register` - Register new user
- `POST /api/login` - Login user
- `POST /api/logout` - Logout user
- `GET /api/user` - Get authenticated user

### Inventory (Web Routes)
- `GET /inventory` - View inventory with filters
- `POST /inventory/receipt` - Generate receipt
- `GET /inventory/receipt/{receiptId}` - View receipt

### Vendor Goods
- `GET /vendor/goods` - List vendor's goods
- `POST /vendor/goods` - Create good
- `GET /vendor/goods/{id}/edit` - Edit good
- `PUT /vendor/goods/{id}` - Update good
- `DELETE /vendor/goods/{id}` - Delete good

### Categories
- `GET /categories/create` - Create category form
- `POST /categories` - Store category

## Database Schema

### Users Table
- id, name, email, password, phone, address, role, created_at, updated_at

### Vendors Table
- id, user_id, phone, address, created_at, updated_at

### Goods Table
- id, vendor_id, category_id, name, description, price, quantity, recived_date, created_at, updated_at

### Categories Table
- id, name, created_at, updated_at

### Receipts Table
- id, vendor_id, user_id, total_amount, printed_at, created_at, updated_at

## Troubleshooting

### Port 8000 Already in Use

```bash
php artisan serve --port=8001
```

### Database Connection Error

Ensure `.env` has correct database configuration and the database file/server is running.

### npm Dependencies Issues

```bash
rm -rf node_modules package-lock.json
npm install
```

### Clear Application Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Development Tips

- Use `php artisan tinker` for interactive shell
- Check logs in `storage/logs/laravel.log`
- Use `php artisan migrate:fresh --seed` to reset database

## License

This project is open-sourced software licensed under the MIT license.
