<?php
require_once 'includes/header.php';
$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');
$params = [];
$sql = 'SELECT * FROM products WHERE 1=1';
if ($search !== '') {
    $sql .= ' AND (name LIKE ? OR description LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($category !== '') {
    $sql .= ' AND category = ?';
    $params[] = $category;
}
$sql .= ' ORDER BY id DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
$categories = $pdo->query('SELECT DISTINCT category FROM products ORDER BY category')->fetchAll(PDO::FETCH_COLUMN);
?>
<h2 class="mb-4">Browse Products</h2>
<form class="row g-3 mb-4">
    <div class="col-md-5"><input type="text" name="search" class="form-control" placeholder="Search products" value="<?= e($search) ?>"></div>
    <div class="col-md-4">
        <select name="category" class="form-select">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= e($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>><?= e($cat) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3 d-grid"><button class="btn btn-dark">Filter</button></div>
</form>
<div class="row g-4">
    <?php foreach ($products as $product): ?>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                <img src="<?= e($product['image_url']) ?>" class="card-img-top" alt="<?= e($product['name']) ?>">
                <div class="card-body d-flex flex-column">
                    <span class="badge bg-secondary-subtle text-dark mb-2 align-self-start"><?= e($product['category']) ?></span>
                    <h4><?= e($product['name']) ?></h4>
                    <p class="text-muted"><?= e(mb_strimwidth($product['description'], 0, 90, '...')) ?></p>
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <span class="fs-3 fw-bold">$<?= number_format((float)$product['price'], 2) ?></span>
                        <a href="product.php?id=<?= (int)$product['id'] ?>" class="btn btn-dark">View</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php require_once 'includes/footer.php'; ?>
