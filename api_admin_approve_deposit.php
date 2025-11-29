<?php
// ============================================
// API: Approve/Reject Deposit
// File: /api/admin/approve_deposit.php
// ============================================

require_once '../../db_config.php';

// Get request data
$admin_telegram_id = $_POST['admin_telegram_id'] ?? null;
$deposit_id = $_POST['deposit_id'] ?? null;
$action = $_POST['action'] ?? null; // 'approve' or 'reject'

// Verify admin
if (!$admin_telegram_id || !isAdmin($admin_telegram_id)) {
    echo json_encode([
        'success' => false,
        'error' => 'Unauthorized'
    ]);
    exit;
}

if (!$deposit_id || !$action) {
    echo json_encode([
        'success' => false,
        'error' => 'Missing required parameters'
    ]);
    exit;
}

try {
    $pdo->beginTransaction();

    // Get deposit details
    $stmt = $pdo->prepare("SELECT * FROM deposits WHERE id = ?");
    $stmt->execute([$deposit_id]);
    $deposit = $stmt->fetch();

    if (!$deposit) {
        throw new Exception('Deposit not found');
    }

    if ($deposit['status'] !== 'pending') {
        throw new Exception('Deposit already processed');
    }

    if ($action === 'approve') {
        // Update deposit status
        $stmt = $pdo->prepare("
            UPDATE deposits 
            SET status = 'approved', approved_at = NOW(), approved_by = ? 
            WHERE id = ?
        ");
        $stmt->execute([
            getUserByTelegramId($admin_telegram_id)['id'],
            $deposit_id
        ]);

        // Add balance to user
        $balances = updateUserBalance($deposit['user_id'], $deposit['amount'], 'add');

        // Log transaction
        logTransaction(
            $deposit['user_id'],
            $deposit['telegram_id'],
            'deposit',
            $deposit['amount'],
            "Deposit approved via {$deposit['crypto_type']}"
        );

        // Log admin action
        $stmt = $pdo->prepare("
            INSERT INTO admin_logs (admin_id, action, target_user_id, details) 
            VALUES (?, 'approve_deposit', ?, ?)
        ");
        $stmt->execute([
            getUserByTelegramId($admin_telegram_id)['id'],
            $deposit['user_id'],
            "Approved deposit #{$deposit_id} for \${$deposit['amount']}"
        ]);

    } else if ($action === 'reject') {
        // Update deposit status
        $stmt = $pdo->prepare("
            UPDATE deposits 
            SET status = 'rejected', approved_at = NOW(), approved_by = ? 
            WHERE id = ?
        ");
        $stmt->execute([
            getUserByTelegramId($admin_telegram_id)['id'],
            $deposit_id
        ]);

        // Log admin action
        $stmt = $pdo->prepare("
            INSERT INTO admin_logs (admin_id, action, target_user_id, details) 
            VALUES (?, 'reject_deposit', ?, ?)
        ");
        $stmt->execute([
            getUserByTelegramId($admin_telegram_id)['id'],
            $deposit['user_id'],
            "Rejected deposit #{$deposit_id}"
        ]);
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Deposit ' . $action . 'd successfully'
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Error in approve_deposit.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
