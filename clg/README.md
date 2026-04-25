# College Management System (XAMPP Ready)

## Setup Guide
1. Copy the `clg` folder to `xampp/htdocs/`.
2. Start Apache and MySQL in XAMPP.
3. Open `http://localhost/phpmyadmin` and create/import SQL file `clg/database/init.sql`.
4. Confirm DB credentials in `clg/config/constants.php`.
5. Visit `http://localhost/clg/index.php`.

## Default Login Credentials
- Admin: `admin@huparicollege.edu` / `Password@123`
- Staff: `staff@huparicollege.edu` / `Password@123`
- Student: `student@huparicollege.edu` / `Password@123`

## Security Included
- PDO prepared statements.
- CSRF tokens for all mutation forms.
- XSS-safe output escaping helper.
- Session fixation prevention and timeout.
- File upload type and size checks.
- Error logging to `logs/app.log`.
