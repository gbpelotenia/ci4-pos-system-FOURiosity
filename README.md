# CI4 POS System

A student-friendly CodeIgniter 4 Point of Sale System covering the three group modules:

- **Mendoza:** Authentication and Staff Management
- **Catangay:** Product and Customer Management
- **Mantes:** Sales, Dashboard, Layout, and Navigation

## Features

- Session-based login/logout
- Staff CRUD with avatar upload
- Product CRUD with image upload
- Customer CRUD
- Record a sale with optional customer
- Automatic stock deduction inside a database transaction
- Sales history with product, customer, staff, quantity, total price, and date
- Dashboard totals and low-stock list
- CSRF protection on forms

## Requirements

- PHP 8.2 or newer
- MySQL / MariaDB
- Composer

This project targets CodeIgniter 4.7.4.

## 1. Install

```bash
composer install
```

## 2. Configure environment

Copy `env.example` to `.env` and update the database settings.

Create a MySQL database named:

```text
ci4_pos
```

Do not commit `.env` to GitHub.

## 3. Create tables

```bash
php spark migrate
```

## 4. Create the first login account

```bash
php spark db:seed UserSeeder
```

Default login:

```text
Username: admin
Password: admin123
```

Change the password after first login if this is used beyond classroom testing.

## 5. Run

```bash
php spark serve
```

Open:

```text
http://localhost:8080
```

## Project structure

```text
app/Controllers/
  Auth.php
  Users.php
  Products.php
  Customers.php
  Sales.php
  Dashboard.php

app/Models/
  UserModel.php
  ProductModel.php
  CustomerModel.php
  SaleModel.php

app/Filters/AuthFilter.php

app/Config/
  Routes.php
  auth.php
  users.php
  products.php
  customers.php
  sales.php
  dashboard.php
```

## Test flow

1. Login as `admin` / `admin123`.
2. Add a product with stock, for example 20.
3. Add a customer.
4. Record a sale of 3 units.
5. Confirm stock changes from 20 to 17.
6. Open Sales History and verify product, customer, staff, quantity, total, and date.
7. Return to Dashboard and verify transaction and sales totals.
8. Add a staff account and test avatar upload.
9. Logout.

## GitHub

A typical first push is:

```bash
git init
git add .
git commit -m "Complete CodeIgniter 4 POS system"
git branch -M main
git remote add origin YOUR_REPOSITORY_URL
git push -u origin main
```
