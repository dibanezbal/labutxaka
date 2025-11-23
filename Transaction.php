<?php
/**
 * Transaction Model
 * Handles CRUD operations for transactions
 */

require_once 'db.php';

class Transaction {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    /**
     * Get all transactions
     * @param string $type Filter by type (income/expense) or null for all
     * @return array
     */
    public function getAll($type = null) {
        $sql = "SELECT * FROM transactions";
        if ($type) {
            $sql .= " WHERE type = ?";
        }
        $sql .= " ORDER BY transaction_date DESC, id DESC";
        
        if ($type) {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("s", $type);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->db->query($sql);
        }
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Get transaction by ID
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    /**
     * Create new transaction
     * @param array $data
     * @return bool
     */
    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO transactions (type, description, amount, category, transaction_date) 
             VALUES (?, ?, ?, ?, ?)"
        );
        
        $stmt->bind_param(
            "ssdss",
            $data['type'],
            $data['description'],
            $data['amount'],
            $data['category'],
            $data['transaction_date']
        );
        
        return $stmt->execute();
    }
    
    /**
     * Update transaction
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare(
            "UPDATE transactions 
             SET type = ?, description = ?, amount = ?, category = ?, transaction_date = ? 
             WHERE id = ?"
        );
        
        $stmt->bind_param(
            "ssdssi",
            $data['type'],
            $data['description'],
            $data['amount'],
            $data['category'],
            $data['transaction_date'],
            $id
        );
        
        return $stmt->execute();
    }
    
    /**
     * Delete transaction
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM transactions WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Get summary statistics
     * @return array
     */
    public function getSummary() {
        $sql = "SELECT 
                    SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
                    SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense,
                    COUNT(CASE WHEN type = 'income' THEN 1 END) as income_count,
                    COUNT(CASE WHEN type = 'expense' THEN 1 END) as expense_count
                FROM transactions";
        
        $result = $this->db->query($sql);
        $summary = $result->fetch_assoc();
        
        $summary['balance'] = $summary['total_income'] - $summary['total_expense'];
        
        return $summary;
    }
}
