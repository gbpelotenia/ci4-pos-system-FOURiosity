# FOURiosity POS – Custom Dashboard Setup

The customized dashboard adds a seven-day sales chart, today's sales and gross profit, top-selling products, low-stock alerts, recent transactions, categories, suppliers, and expense tracking.

## Existing installation

1. Back up your database.
2. Open PowerShell in the project folder.
3. Run `php spark migrate` to add the new tables and product fields.
4. Run `php spark serve`.
5. Open `http://localhost:8080`.

## Fresh installation

1. Start Apache and MySQL in XAMPP.
2. Import `ci4_pos.sql` in phpMyAdmin.
3. Run `composer install` if the `vendor` folder is missing.
4. Run `php spark serve` and open `http://localhost:8080`.

## Important

Enter each product's cost price on the Products page. Gross profit is calculated as `(selling price - cost price) × quantity sold`. Existing products initially have a cost price of zero, so update them for accurate profit figures.
