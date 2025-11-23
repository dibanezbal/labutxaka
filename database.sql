-- Database setup for Labutxaka expense tracker
-- Create database
CREATE DATABASE IF NOT EXISTS labutxaka;
USE labutxaka;

-- Create transactions table
CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('income', 'expense') NOT NULL,
    description VARCHAR(255) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    category VARCHAR(100) NOT NULL,
    transaction_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO transactions (type, description, amount, category, transaction_date) VALUES
('income', 'Monthly Salary', 2500.00, 'Salary', '2025-11-01'),
('expense', 'Rent Payment', 800.00, 'Housing', '2025-11-05'),
('expense', 'Groceries', 150.00, 'Food', '2025-11-10'),
('income', 'Freelance Project', 500.00, 'Freelance', '2025-11-15'),
('expense', 'Utilities', 120.00, 'Bills', '2025-11-20');
