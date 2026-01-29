# Technical Store Inventory System

## Setup
1. **Create database**
   - Import `database/schema.sql` into MySQL 8+.
   - This will create the database `tech_store` and the base tables.
2. **Configure app**
   - Edit `includes/config.php` to set database credentials and base URL if needed.
3. **Run app**
   - Place the project in your web root (e.g., XAMPP `htdocs`).
   - Visit `http://localhost/starnews/public/index.php`.
4. **Login**
   - Seed admin user:
     - Email: `admin@example.com`
     - Password: `password123` (update by changing the hash in `database/schema.sql` if desired).

## Notes
- Logged-in users can add stock in/out and mark returns.
- Equipment and locations are managed directly in the database seed or via SQL updates.
