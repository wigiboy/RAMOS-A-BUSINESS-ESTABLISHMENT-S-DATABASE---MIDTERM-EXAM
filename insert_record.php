<?php
// =============================================
// insert_record.php
// Demonstrates INSERT using PDO
// Adds a new suki (regular customer) to the DB
// =============================================

require_once 'dbconfig.php';

// New customer data — in real app, comes from $_POST form
$newCustomer = [
    'full_name'     => 'Kuya Ben Ramos',
    'address'       => 'Blk 6 Lot 9 Del Pilar St.',
    'phone'         => '09676666666',
    'utang_balance' => 0.00
];

// INSERT using named placeholders — NEVER put variables directly in SQL!
$sql = "
    INSERT INTO customers (full_name, address, phone, utang_balance)
    VALUES (:full_name, :address, :phone, :utang_balance)
";

$stmt = $pdo->prepare($sql);

// Pass the array directly — PDO binds each key to its matching placeholder
$stmt->execute($newCustomer);

$rowsInserted = $stmt->rowCount();     // Should be 1
$newId        = $pdo->lastInsertId();  // Auto-generated customer_id

echo "<pre>";
print_r([
    'rows_inserted'   => $rowsInserted,
    'new_customer_id' => $newId,
    'data_inserted'   => $newCustomer
]);
echo "</pre>";
?>
