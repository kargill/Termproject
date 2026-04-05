<?php
require_once '../includes/functions.php';
require_once '../includes/db.php';
requireAdmin();
$products = $pdo->query('SELECT p.*, u.name AS seller_name FROM products p LEFT JOIN users u ON p.seller_id = u.id ORDER BY p.id DESC')->fetchAll();
$orders = $pdo->query('SELECT o.*, u.name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Admin Dashboard</h1>
        <div><a href="../index.php" class="btn btn-outline-dark">Back to Site</a></div>
    </div>
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h3 class="mb-3">Products</h3>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Seller</th><th>Actions</th></tr></thead>
                            <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= (int)$product['id'] ?></td>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td>$<?= number_format((float)$product['price'], 2) ?></td>
                                    <td><?= htmlspecialchars($product['seller_name'] ?? 'Unknown') ?></td>
                                    <td>
                                        <a href="edit_product.php?id=<?= (int)$product['id'] ?>" class="btn btn-sm btn-dark">Edit</a>
                                        <a href="delete_product.php?id=<?= (int)$product['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h3 class="mb-3">Orders</h3>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead><tr><th>ID</th><th>User</th><th>Total</th><th>Status</th></tr></thead>
                            <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= (int)$order['id'] ?></td>
                                    <td><?= htmlspecialchars($order['name']) ?></td>
                                    <td>$<?= number_format((float)$order['total_price'], 2) ?></td>
                                    <td><?= htmlspecialchars($order['status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body></html>
