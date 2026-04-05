<?php
require_once 'includes/header.php';
requireLogin();
$stmt = $pdo->prepare('SELECT c.*, p.name, p.price, p.stock FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?');
$stmt->execute([currentUserId()]);
$items = $stmt->fetchAll();
if (!$items) {
    echo '<div class="alert alert-info">Your cart is empty.</div>';
    require_once 'includes/footer.php';
    exit;
}
$total = 0;
foreach ($items as $item) {
    $total += $item['price'] * $item['quantity'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->beginTransaction();
    try {
        $order = $pdo->prepare('INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, ?)');
        $order->execute([currentUserId(), $total, 'Completed']);
        $orderId = (int)$pdo->lastInsertId();
        $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
        $stockStmt = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?');
        foreach ($items as $item) {
            $itemStmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
            $stockStmt->execute([$item['quantity'], $item['product_id'], $item['quantity']]);
        }
        $clear = $pdo->prepare('DELETE FROM cart WHERE user_id = ?');
        $clear->execute([currentUserId()]);
        $pdo->commit();
        header('Location: my_orders.php?placed=1');
        exit;
    } catch (Throwable $e) {
        $pdo->rollBack();
        $error = 'Checkout failed: ' . $e->getMessage();
    }
}
?>
<h2 class="mb-4">Checkout</h2>
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        <?php if (!empty($error)): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
        <ul class="list-group mb-4">
            <?php foreach ($items as $item): ?>
                <li class="list-group-item d-flex justify-content-between">
                    <span><?= e($item['name']) ?> x <?= (int)$item['quantity'] ?></span>
                    <strong>$<?= number_format($item['price'] * $item['quantity'], 2) ?></strong>
                </li>
            <?php endforeach; ?>
        </ul>
        <h4 class="mb-4">Total: $<?= number_format((float)$total, 2) ?></h4>
        <form method="post"><button class="btn btn-dark">Place Order</button></form>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
