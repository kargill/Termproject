<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';
$cartCountValue = isLoggedIn() ? cartCount($pdo, currentUserId()) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3" href="index.php">ReMarket</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="products.php">Browse</a></li>
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item"><a class="nav-link" href="sell_item.php">Sell Item</a></li>
                    <li class="nav-item"><a class="nav-link" href="my_orders.php">My Orders</a></li>
                    <?php if (isAdmin()): ?>
                        <li class="nav-item"><a class="nav-link" href="admin/index.php">Admin</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-outline-light position-relative" href="cart.php">Cart <span class="badge text-bg-warning ms-1"><?= $cartCountValue ?></span></a>
                <?php if (isLoggedIn()): ?>
                    <span class="text-light">Hi, <?= e($_SESSION['user_name']) ?></span>
                    <a class="btn btn-warning" href="logout.php">Logout</a>
                <?php else: ?>
                    <a class="btn btn-outline-light" href="login.php">Login</a>
                    <a class="btn btn-warning" href="register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<div class="container py-4">
