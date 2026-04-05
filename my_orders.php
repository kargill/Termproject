<?php
require_once 'includes/header.php';
requireLogin();
$stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC');
$stmt->execute([currentUserId()]);
$orders = $stmt->fetchAll();
?>
<h2 class="mb-4">My Orders</h2>
<?php if (isset($_GET['placed'])): ?><div class="alert alert-success">Order placed successfully.</div><?php endif; ?>
<?php if (!$orders): ?>
    <div class="alert alert-info">No orders yet.</div>
<?php else: ?>
    <?php foreach ($orders as $order): ?>
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between flex-wrap mb-3">
                    <div><strong>Order #<?= (int)$order['id'] ?></strong></div>
                    <div>Status: <span class="badge text-bg-success"><?= e($order['status']) ?></span></div>
                    <div>Date: <?= e($order['order_date']) ?></div>
                    <div>Total: <strong>$<?= number_format((float)$order['total_price'], 2) ?></strong></div>
                </div>
                <?php
                $itemsStmt = $pdo->prepare('SELECT oi.quantity, oi.price, p.name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?');
                $itemsStmt->execute([$order['id']]);
                $orderItems = $itemsStmt->fetchAll();
                ?>
                <ul class="mb-0">
                    <?php foreach ($orderItems as $item): ?>
                        <li><?= e($item['name'] ?? 'Deleted Product') ?> x <?= (int)$item['quantity'] ?> - $<?= number_format($item['price'] * $item['quantity'], 2) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php require_once 'includes/footer.php'; ?>
