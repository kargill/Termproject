<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';
requireLogin();
$productId = (int)($_POST['product_id'] ?? 0);
$quantity = max(1, (int)($_POST['quantity'] ?? 1));

$stmt = $pdo->prepare('SELECT id, stock FROM products WHERE id = ?');
$stmt->execute([$productId]);
$product = $stmt->fetch();
if (!$product) {
    header('Location: products.php');
    exit;
}

$existing = $pdo->prepare('SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?');
$existing->execute([currentUserId(), $productId]);
$row = $existing->fetch();
if ($row) {
    $newQty = min($product['stock'], $row['quantity'] + $quantity);
    $update = $pdo->prepare('UPDATE cart SET quantity = ? WHERE id = ?');
    $update->execute([$newQty, $row['id']]);
} else {
    $insert = $pdo->prepare('INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)');
    $insert->execute([currentUserId(), $productId, min($product['stock'], $quantity)]);
}
header('Location: cart.php');
exit;
