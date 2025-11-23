# Labutxaka - Expense Tracker

A simple CRUD (Create, Read, Update, Delete) web application for tracking personal income and expenses, built with PHP and MySQL.

## Features

- ✅ Add, edit, and delete income/expense transactions
- ✅ View all transactions in a clean, organized table
- ✅ Filter transactions by type (Income/Expense)
- ✅ Dashboard with summary statistics (Total Income, Total Expenses, Balance)
- ✅ Category-based organization
- ✅ Responsive design for desktop and mobile
- ✅ Date-based tracking

## Requirements

- PHP 7.0 or higher
- MySQL 5.6 or higher
- Web server (Apache, Nginx, or PHP built-in server)

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/dibanezbal/labutxaka.git
cd labutxaka
```

### 2. Set up the database

1. Create a MySQL database:
```bash
mysql -u root -p
```

2. Import the database schema:
```bash
mysql -u root -p < database.sql
```

Or manually execute the SQL commands in `database.sql` using phpMyAdmin or your preferred MySQL client.

### 3. Configure database connection

Edit `config.php` and update the database credentials:

```php
define('DB_HOST', 'localhost');    // Your MySQL host
define('DB_USER', 'root');         // Your MySQL username
define('DB_PASS', '');             // Your MySQL password
define('DB_NAME', 'labutxaka');    // Database name
```

### 4. Start the application

#### Using PHP built-in server:
```bash
php -S localhost:8000
```

Then open your browser and navigate to: `http://localhost:8000`

#### Using Apache/Nginx:
Configure your web server to serve the application directory and access it through your configured domain/port.

## Usage

### Dashboard
- View all transactions with filtering options
- See summary statistics (total income, expenses, and balance)
- Quick actions to add, edit, or delete transactions

### Add Transaction
1. Click the "+ Add Transaction" button
2. Fill in the form with transaction details:
   - Type (Income or Expense)
   - Description
   - Amount
   - Category
   - Date
3. Click "Save Transaction"

### Edit Transaction
1. Click the "Edit" button on any transaction in the table
2. Modify the transaction details
3. Click "Update Transaction"

### Delete Transaction
1. Click the "Delete" button on any transaction
2. Confirm the deletion in the popup dialog

## Project Structure

```
labutxaka/
├── config.php           # Database configuration
├── db.php              # Database connection utility
├── Transaction.php     # Transaction model (CRUD operations)
├── index.php           # Main dashboard (Read)
├── add.php             # Add transaction form (Create)
├── edit.php            # Edit transaction form (Update)
├── style.css           # Application styles
├── database.sql        # Database schema and sample data
└── README.md           # This file
```

## Database Schema

### transactions table

| Column           | Type             | Description                      |
|------------------|------------------|----------------------------------|
| id               | INT (PK)         | Auto-incrementing primary key    |
| type             | ENUM             | 'income' or 'expense'            |
| description      | VARCHAR(255)     | Transaction description          |
| amount           | DECIMAL(10,2)    | Transaction amount               |
| category         | VARCHAR(100)     | Category name                    |
| transaction_date | DATE             | Date of transaction              |
| created_at       | TIMESTAMP        | Record creation timestamp        |
| updated_at       | TIMESTAMP        | Record update timestamp          |

## Technologies Used

- **Backend**: PHP (procedural and OOP)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3
- **Architecture**: Simple MVC-like pattern

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Contributing

This is a university project for an MVP. Contributions are welcome for educational purposes.

## Support

For issues and questions, please open an issue on the GitHub repository.