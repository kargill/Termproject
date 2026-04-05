<?php
require_once 'includes/header.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT p.*, u.name AS seller_name FROM products p LEFT JOIN users u ON p.seller_id = u.id WHERE p.id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) {
    echo '<div class="alert alert-danger">Product not found.</div>';
    require_once 'includes/footer.php';
    exit;
}
?>
<div class="row g-4">
    <div class="col-md-6"><img src="<?= e($product['image_url']) ?>" class="product-img-lg shadow-sm" alt="<?= e($product['name']) ?>"></div>
    <div class="col-md-6">
        <span class="badge bg-secondary-subtle text-dark mb-2"><?= e($product['category']) ?></span>
        <h2><?= e($product['name']) ?></h2>
        <p class="text-muted">Condition: <?= e($product['item_condition']) ?></p>
        <p><?= nl2br(e($product['description'])) ?></p>
        <p>Seller: <strong><?= e($product['seller_name'] ?? 'Unknown') ?></strong></p>
        <p>Stock: <strong><?= (int)$product['stock'] ?></strong></p>
        <h3 class="mb-3">$<?= number_format((float)$product['price'], 2) ?></h3>
        <?php if (isLoggedIn()): ?>
            <form method="post" action="add_to_cart.php" class="d-flex gap-2">
                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                <input type="number" name="quantity" value="1" min="1" max="<?= (int)$product['stock'] ?>" class="form-control" style="max-width:120px;">
                <button class="btn btn-dark">Add to Cart</button>
            </form>
        <?php else: ?>
            <a href="login.php" class="btn btn-dark">Login to Buy</a>
        <?php endif; ?>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
