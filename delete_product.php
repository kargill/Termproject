<?php
require_once '../includes/functions.php';
require_once '../includes/db.php';
requireAdmin();
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
$stmt->execute([$id]);
header('Location: index.php');
exit;
