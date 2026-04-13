<?php
// =============================================
// fetch_demo.php
// Demonstrates PDO fetch()
// Retrieves customers ONE ROW AT A TIME
// =============================================

require_once 'dbconfig.php'; // Load database connection

$sql  = "SELECT customer_id, full_name, address, phone, utang_balance FROM customers";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// fetch() gets ONE row per call — loop until no rows remain
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // $row = one customer record as associative array
    echo "<pre>";
    print_r($row);
    echo "</pre>";
}
// Loop ends automatically when fetch() returns false (no more rows)
?>
