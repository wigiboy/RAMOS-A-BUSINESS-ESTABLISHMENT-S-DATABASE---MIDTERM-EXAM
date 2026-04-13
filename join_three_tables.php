<?php
// =============================================
// join_three_tables.php
// Demonstrates JOIN across 3 tables
// Joins: sale_items + products + categories
// Shows what was sold and which category it belongs to
// =============================================

require_once 'dbconfig.php';

$sql = "
    SELECT
        si.sale_item_id,
        si.transaction_id,
        p.product_name,          -- from products table
        p.brand,
        c.category_name,         -- from categories table
        si.quantity,
        si.unit_price,
        si.subtotal
    FROM sale_items si
    INNER JOIN products   p ON si.product_id   = p.product_id    -- link item to product
    INNER JOIN categories c ON p.category_id   = c.category_id   -- link product to category
    ORDER BY si.transaction_id, si.sale_item_id
";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($items);
echo "</pre>";
?>
