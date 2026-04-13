<?php
// =============================================
// html_table.php
// Renders SQL query results as an HTML table
// Shows all products with their categories
// =============================================

require_once 'dbconfig.php';

// Join products with categories to show category names
$sql = "
    SELECT
        p.product_id,
        p.product_name,
        p.brand,
        c.category_name,
        p.unit,
        p.price,
        p.stock_qty,
        p.reorder_level
    FROM products p
    INNER JOIN categories c ON p.category_id = c.category_id
    ORDER BY c.category_name, p.product_name
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aling Nena's Store – Product List</title>
    <style>
        body  { font-family: Arial, sans-serif; padding: 24px; background: #fffde7; }
        h2    { color: #e65100; }
        table { border-collapse: collapse; width: 100%; background: #fff; }
        th    { background: #e65100; color: #fff; padding: 10px 14px; text-align: left; }
        td    { padding: 9px 14px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) { background: #fff8e1; }
        .low  { color: red; font-weight: bold; }   /* low stock warning */
    </style>
</head>
<body>
<h2>Aling Nena's Sari-Sari Store – Product Inventory</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Brand</th>
            <th>Category</th>
            <th>Unit</th>
            <th>Price (₱)</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $row): ?>
        <?php
            // Flag low stock items (at or below reorder level)
            $isLow = $row['stock_qty'] <= $row['reorder_level'];
        ?>
        <tr>
            <td><?= htmlspecialchars($row['product_id'])   ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= htmlspecialchars($row['brand'])        ?></td>
            <td><?= htmlspecialchars($row['category_name'])?></td>
            <td><?= htmlspecialchars($row['unit'])         ?></td>
            <td>₱<?= number_format($row['price'], 2)       ?></td>
            <!-- Show red text if stock is at or below reorder level -->
            <td class="<?= $isLow ? 'low' : '' ?>">
                <?= htmlspecialchars($row['stock_qty']) ?>
                <?= $isLow ? ' ⚠ Low' : '' ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
