<?php
// =============================================
// update_record.php
// Demonstrates UPDATE using PDO
// Updates a product's stock quantity after restock
// =============================================

require_once 'dbconfig.php';

$productId    = 1;   // Royal Tru-Orange
$addedStock   = 24;  // New pieces added to shelf

// SQL: add to existing stock instead of overwriting it
$sql = "
    UPDATE products
    SET    stock_qty = stock_qty + :added   -- add to current stock
    WHERE  product_id = :product_id         -- only this product
";

$stmt = $pdo->prepare($sql);

// Bind both parameters safely
$stmt->bindValue(':added',      $addedStock, PDO::PARAM_INT);
$stmt->bindValue(':product_id', $productId,  PDO::PARAM_INT);

$stmt->execute();

$updated = $stmt->rowCount(); // 1 = updated, 0 = no change

echo "<pre>";
print_r([
    'product_id'   => $productId,
    'stock_added'  => $addedStock,
    'rows_updated' => $updated,
    'message'      => $updated > 0 ? 'Stock updated successfully.' : 'No changes made.'
]);
echo "</pre>";
?>
