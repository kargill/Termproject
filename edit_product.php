<?php
require_once '../includes/functions.php';
require_once '../includes/db.php';
requireAdmin();
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) die('Product not found');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $category = trim($_POST['category'] ?? '');
    $condition = trim($_POST['item_condition'] ?? '');
    $stock = (int)($_POST['stock'] ?? 1);
    $image = $product['image_url'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = uploadImage('image', '../assets/uploads/');
        if (str_starts_with($image, '../')) $image = substr($image, 3);
    }
    $update = $pdo->prepare('UPDATE products SET name=?, description=?, price=?, image_url=?, category=?, item_condition=?, stock=? WHERE id=?');
    $update->execute([$name, $description, $price, $image, $category, $condition, $stock, $id]);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Edit Product</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light"><div class="container py-4"><div class="card shadow-sm border-0 rounded-4"><div class="card-body p-4">
<h2 class="mb-4">Edit Product</h2>
<form method="post" enctype="multipart/form-data">
<div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($product['description']) ?></textarea></div>
<div class="row g-3"><div class="col-md-6"><label class="form-label">Price</label><input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price']) ?>" class="form-control" required></div><div class="col-md-6"><label class="form-label">Stock</label><input type="number" name="stock" value="<?= (int)$product['stock'] ?>" min="1" class="form-control" required></div></div>
<div class="row g-3 mt-1"><div class="col-md-6"><label class="form-label">Category</label><input type="text" name="category" value="<?= htmlspecialchars($product['category']) ?>" class="form-control" required></div><div class="col-md-6"><label class="form-label">Condition</label><input type="text" name="item_condition" value="<?= htmlspecialchars($product['item_condition']) ?>" class="form-control" required></div></div>
<div class="mt-3"><label class="form-label">Replace Image</label><input type="file" name="image" class="form-control" accept="image/*"></div>
<button class="btn btn-dark mt-4">Save Changes</button>
<a href="index.php" class="btn btn-outline-secondary mt-4">Cancel</a>
</form>
</div></div></div></body></html>
