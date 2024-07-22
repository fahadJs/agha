<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "financial_management");

// Check connection
if (!$conn) {
    die("Connection failed: ". mysqli_connect_error());
}

// Query to retrieve all transactions
$query = "SELECT * FROM transactions";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Create an array to store the transaction data
$transaction_data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $transaction_data[] = $row;
}

// Close the database connection
mysqli_close($conn);

// Create a CSV file
$fp = fopen('transactions.csv', 'w');

// Write the header row
fputcsv($fp, array('Date', 'Type', 'Category', 'Amount', 'Description'));

// Write the transaction data
foreach ($transaction_data as $row) {
    fputcsv($fp, array($row['date'], $row['type'], $row['category'], $row['amount'], $row['description']));
}

// Close the CSV file
fclose($fp);

// Display a success message
echo "Data exported successfully!";
?>