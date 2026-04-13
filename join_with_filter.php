<?php
// =============================================
// join_with_filter.php
// Demonstrates JOIN of 3+ tables WITH filter
// Filter: Only 'Utang' transactions with
//         outstanding balance > 0
// =============================================

require_once 'dbconfig.php';

$sql = "
    SELECT
        t.transaction_id,
        cu.full_name        AS customer_name,
        cu.utang_balance,
        t.transaction_date,
        t.total_amount,
        t.amount_paid,
        (t.total_amount - t.amount_paid) AS balance_due,
        t.payment_method
    FROM sales_transactions t
    INNER JOIN customers cu ON t.customer_id = cu.customer_id    -- join customer info
    WHERE t.payment_method = :method                              -- filter: Utang only
      AND cu.utang_balance > :min_balance                        -- filter: has outstanding balance
    ORDER BY cu.utang_balance DESC
";

$stmt = $pdo->prepare($sql);

// Bind filter values safely using named parameters
$stmt->bindValue(':method',      'Utang', PDO::PARAM_STR);
$stmt->bindValue(':min_balance', 0,       PDO::PARAM_INT);

$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($results);
echo "</pre>";
?>
