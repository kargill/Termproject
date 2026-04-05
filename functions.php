<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function isAdmin(): bool {
    return !empty($_SESSION['is_admin']);
}

function requireAdmin(): void {
    if (!isLoggedIn() || !isAdmin()) {
        header('Location: ../login.php');
        exit;
    }
}

function currentUserId(): int {
    return (int)($_SESSION['user_id'] ?? 0);
}

function cartCount(PDO $pdo, int $userId): int {
    if ($userId <= 0) return 0;
    $stmt = $pdo->prepare('SELECT COALESCE(SUM(quantity),0) FROM cart WHERE user_id = ?');
    $stmt->execute([$userId]);
    return (int)$stmt->fetchColumn();
}

function uploadImage(string $fieldName, string $targetDir = 'assets/uploads/'): string {
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return 'https://via.placeholder.com/600x400?text=No+Image';
    }

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $tmpName = $_FILES[$fieldName]['tmp_name'];
    $mime = mime_content_type($tmpName);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif'
    ];

    if (!isset($allowed[$mime])) {
        return 'https://via.placeholder.com/600x400?text=Invalid+Image';
    }

    $filename = uniqid('product_', true) . '.' . $allowed[$mime];
    $path = rtrim($targetDir, '/') . '/' . $filename;

    if (!move_uploaded_file($tmpName, $path)) {
        return 'https://via.placeholder.com/600x400?text=Upload+Failed';
    }

    return $path;
}
