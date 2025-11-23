<?php
/**
 * Edit Transaction Form
 */

require_once 'Transaction.php';

$error = '';
$transactionModel = new Transaction();

// Get transaction ID
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = intval($_GET['id']);
$trans = $transactionModel->getById($id);

if (!$trans) {
    header('Location: index.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'type' => $_POST['type'],
        'description' => trim($_POST['description']),
        'amount' => floatval($_POST['amount']),
        'category' => trim($_POST['category']),
        'transaction_date' => $_POST['transaction_date']
    ];
    
    // Validation
    if (empty($data['description'])) {
        $error = 'Description is required';
    } elseif ($data['amount'] <= 0) {
        $error = 'Amount must be greater than 0';
    } elseif (empty($data['category'])) {
        $error = 'Category is required';
    } elseif (empty($data['transaction_date'])) {
        $error = 'Date is required';
    } else {
        if ($transactionModel->update($id, $data)) {
            header('Location: index.php');
            exit;
        } else {
            $error = 'Failed to update transaction';
        }
    }
} else {
    // Populate form with existing data
    $_POST = $trans;
}

$pageTitle = 'Edit Transaction - ' . APP_NAME;
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
        <a href="index.php" class="back-link">← Back to Dashboard</a>

        <div class="form-container">
            <h2>Edit Transaction</h2>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="edit.php?id=<?php echo $id; ?>">
                <div class="form-group">
                    <label for="type">Type *</label>
                    <select id="type" name="type" required>
                        <option value="income" <?php echo ($_POST['type'] === 'income') ? 'selected' : ''; ?>>
                            Income
                        </option>
                        <option value="expense" <?php echo ($_POST['type'] === 'expense') ? 'selected' : ''; ?>>
                            Expense
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <input type="text" 
                           id="description" 
                           name="description" 
                           value="<?php echo htmlspecialchars($_POST['description']); ?>"
                           required
                           placeholder="e.g., Monthly salary, Grocery shopping">
                </div>

                <div class="form-group">
                    <label for="amount">Amount (<?php echo CURRENCY_SYMBOL; ?>) *</label>
                    <input type="number" 
                           id="amount" 
                           name="amount" 
                           step="0.01" 
                           min="0.01"
                           value="<?php echo htmlspecialchars($_POST['amount']); ?>"
                           required
                           placeholder="0.00">
                </div>

                <div class="form-group">
                    <label for="category">Category *</label>
                    <input type="text" 
                           id="category" 
                           name="category" 
                           value="<?php echo htmlspecialchars($_POST['category']); ?>"
                           required
                           placeholder="e.g., Salary, Food, Housing, Bills">
                </div>

                <div class="form-group">
                    <label for="transaction_date">Date *</label>
                    <input type="date" 
                           id="transaction_date" 
                           name="transaction_date" 
                           value="<?php echo $_POST['transaction_date']; ?>"
                           required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-success">Update Transaction</button>
                    <a href="index.php" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
