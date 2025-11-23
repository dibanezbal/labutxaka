<?php
/**
 * Main Dashboard - List all transactions
 */

require_once 'Transaction.php';

$transaction = new Transaction();

// Get filter parameter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Get transactions based on filter
if ($filter === 'income' || $filter === 'expense') {
    $transactions = $transaction->getAll($filter);
} else {
    $transactions = $transaction->getAll();
}

// Get summary
$summary = $transaction->getSummary();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($transaction->delete($id)) {
        header('Location: index.php?deleted=1');
        exit;
    }
}

$pageTitle = APP_NAME;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><?php echo APP_NAME; ?></h1>
        </div>
    </header>

    <div class="container">
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">
                Transaction deleted successfully!
            </div>
        <?php endif; ?>

        <!-- Summary Cards -->
        <div class="summary">
            <div class="summary-card income">
                <h3>Total Income</h3>
                <div class="amount">
                    <?php echo CURRENCY_SYMBOL . number_format($summary['total_income'], 2); ?>
                </div>
                <small><?php echo $summary['income_count']; ?> transactions</small>
            </div>
            <div class="summary-card expense">
                <h3>Total Expenses</h3>
                <div class="amount">
                    <?php echo CURRENCY_SYMBOL . number_format($summary['total_expense'], 2); ?>
                </div>
                <small><?php echo $summary['expense_count']; ?> transactions</small>
            </div>
            <div class="summary-card balance">
                <h3>Balance</h3>
                <div class="amount">
                    <?php echo CURRENCY_SYMBOL . number_format($summary['balance'], 2); ?>
                </div>
                <small>Income - Expenses</small>
            </div>
        </div>

        <!-- Actions and Filters -->
        <div class="actions">
            <div class="filter-tabs">
                <a href="index.php?filter=all" class="<?php echo $filter === 'all' ? 'active' : ''; ?>">All</a>
                <a href="index.php?filter=income" class="<?php echo $filter === 'income' ? 'active' : ''; ?>">Income</a>
                <a href="index.php?filter=expense" class="<?php echo $filter === 'expense' ? 'active' : ''; ?>">Expenses</a>
            </div>
            <a href="add.php" class="btn btn-success">+ Add Transaction</a>
        </div>

        <!-- Transactions Table -->
        <div class="transactions-table">
            <?php if (count($transactions) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $trans): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($trans['transaction_date'])); ?></td>
                                <td>
                                    <span class="type-badge <?php echo $trans['type']; ?>">
                                        <?php echo ucfirst($trans['type']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($trans['description']); ?></td>
                                <td><?php echo htmlspecialchars($trans['category']); ?></td>
                                <td class="amount-value <?php echo $trans['type']; ?>">
                                    <?php 
                                    echo ($trans['type'] === 'income' ? '+' : '-') . 
                                         CURRENCY_SYMBOL . number_format($trans['amount'], 2); 
                                    ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit.php?id=<?php echo $trans['id']; ?>" class="btn btn-small">Edit</a>
                                        <a href="index.php?action=delete&id=<?php echo $trans['id']; ?>" 
                                           class="btn btn-small btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this transaction?')">
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <h3>No transactions found</h3>
                    <p>Start by adding your first transaction!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
