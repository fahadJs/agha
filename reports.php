<?php
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Validate user input
function validate_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}
?>

<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "financial_management");

// Check connection
if (!$conn) {
    die("Connection failed: ". mysqli_connect_error());
}

// Query to retrieve transaction data
$query = "SELECT * FROM transactions";
$result = mysqli_query($conn, $query);

// Create an array to store the transaction data
$transaction_data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $transaction_data[] = $row;
}

// Close the database connection
mysqli_close($conn);

// Create a report
echo "<h2>Transaction Report</h2>";
echo "<table>";
echo "<tr><th>Date</th><th>Type</th><th>Category</th><th>Amount</th><th>Description</th></tr>";
foreach ($transaction_data as $row) {
    echo "<tr>";
    echo "<td>".$row['date']."</td>";
    echo "<td>".$row['type']."</td>";
    echo "<td>".$row['category']."</td>";
    echo "<td>".$row['amount']."</td>";
    echo "<td>".$row['description']."</td>";
    echo "</tr>";
}
echo "</table>";
?>