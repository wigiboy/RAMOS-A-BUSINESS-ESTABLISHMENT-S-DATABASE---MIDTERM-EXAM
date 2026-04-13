<?php
// =============================================
// fetch_all_demo.php
// Demonstrates PDO fetchAll()
// Retrieves ALL products from the store
// =============================================

require_once 'dbconfig.php'; // Load database connection

// SQL query to get all products
$sql = "SELECT product_id, product_name, brand, unit, price, stock_qty FROM products";

// Prepare the statement (safer than direct query)
$stmt = $pdo->prepare($sql);

// Execute the query
$stmt->execute();

// fetchAll() returns ALL rows at once as an array of arrays
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Print result inside <pre> tags for readable formatting
echo "<pre>";
print_r($products);
echo "</pre>";
?>
