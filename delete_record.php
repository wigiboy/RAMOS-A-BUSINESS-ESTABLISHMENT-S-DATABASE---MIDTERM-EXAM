<?php
// =============================================
// delete_record.php
// Demonstrates DELETE using PDO
// Removes a restock log entry by ID
// =============================================

require_once 'dbconfig.php';

// ID of the restock log to delete
// In a real app this comes from $_POST or $_GET
$restockIdToDelete = 4;

// DELETE with a named placeholder for safety
$sql  = "DELETE FROM restock_logs WHERE restock_id = :restock_id";
$stmt = $pdo->prepare($sql);

// Bind as integer to prevent SQL injection
$stmt->bindValue(':restock_id', $restockIdToDelete, PDO::PARAM_INT);
$stmt->execute();

$deleted = $stmt->rowCount(); // 1 = deleted, 0 = not found

echo "<pre>";
print_r([
    'restock_id_deleted' => $restockIdToDelete,
    'rows_deleted'       => $deleted,
    'message'            => $deleted > 0 ? 'Record deleted successfully.' : 'No record found.'
]);
echo "</pre>";
?>
