<?php
require_once 'includes/header.php';
requireLogin();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $category = trim($_POST['category'] ?? '');
    $condition = trim($_POST['item_condition'] ?? '');
    $stock = (int)($_POST['stock'] ?? 1);

    $check = $pdo->prepare('SELECT id FROM users WHERE id = ?');
    $check->execute([currentUserId()]);
    if (!$check->fetch()) {
        $message = 'Session user not found. Please login again.';
    } elseif ($name === '' || $description === '' || $category === '' || $condition === '' || $price <= 0 || $stock < 1) {
        $message = 'Please fill all fields with valid values.';
    } else {
        $image = uploadImage('image');
        $stmt = $pdo->prepare('INSERT INTO products (name, description, price, image_url, category, item_condition, stock, seller_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$name, $description, $price, $image, $category, $condition, $stock, currentUserId()]);
        header('Location: products.php');
        exit;
    }
}
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <h2 class="mb-4">Sell an Item</h2>
                <?php if ($message): ?><div class="alert alert-danger"><?= e($message) ?></div><?php endif; ?>
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3"><label class="form-label">Product Name</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4" required></textarea></div>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Price</label><input type="number" step="0.01" name="price" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">Stock</label><input type="number" name="stock" min="1" value="1" class="form-control" required></div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="">Select</option>
                                <option>Electronics</option><option>Books</option><option>Clothing</option><option>Vinyl Records</option><option>Collectibles</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Condition</label>
                            <select name="item_condition" class="form-select" required>
                                <option value="">Select</option>
                                <option>Used - Like New</option><option>Used - Very Good</option><option>Used - Good</option><option>Used - Acceptable</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3"><label class="form-label">Image</label><input type="file" name="image" class="form-control" accept="image/*"></div>
                    <button class="btn btn-dark mt-4">Add Product</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
