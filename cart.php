<?php
require_once 'includes/header.php';
requireLogin();
if (isset($_GET['remove'])) {
    $removeId = (int)$_GET['remove'];
    $stmt = $pdo->prepare('DELETE FROM cart WHERE id = ? AND user_id = ?');
    $stmt->execute([$removeId, currentUserId()]);
    header('Location: cart.php');
    exit;
}
$stmt = $pdo->prepare('SELECT c.id AS cart_id, c.quantity, p.* FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ? ORDER BY c.id DESC');
$stmt->execute([currentUserId()]);
$items = $stmt->fetchAll();
$total = 0;
?>
<h2 class="mb-4">Shopping Cart</h2>
<?php if (!$items): ?>
    <div class="alert alert-info">Your cart is empty.</div>
<?php else: ?>
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <?php foreach ($items as $item): $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; ?>
                <div class="row align-items-center border-bottom py-3">
                    <div class="col-md-2"><img src="<?= e($item['image_url']) ?>" class="img-fluid rounded" alt=""></div>
                    <div class="col-md-4"><h5><?= e($item['name']) ?></h5><div class="text-muted">Qty: <?= (int)$item['quantity'] ?></div></div>
                    <div class="col-md-3">$<?= number_format((float)$item['price'], 2) ?></div>
                    <div class="col-md-2 fw-bold">$<?= number_format((float)$subtotal, 2) ?></div>
                    <div class="col-md-1"><a href="cart.php?remove=<?= (int)$item['cart_id'] ?>" class="btn btn-sm btn-outline-danger">X</a></div>
                </div>
            <?php endforeach; ?>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <h4>Total: $<?= number_format((float)$total, 2) ?></h4>
                <a href="checkout.php" class="btn btn-dark">Checkout</a>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php require_once 'includes/footer.php'; ?>
