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

<?php include 'nav.php'; ?>
<?php
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get user role
$sql = "SELECT role FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$user_role = $result->fetch_assoc()['role'];

// Dashboard content
if ($user_role == 'admin') {
    ?>
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
    <ul>
        <li><a href="cash_flow.php">Cash Flow</a></li>
        <li><a href="income.php">Income</a></li>
        <li><a href="expenses.php">Expenses</a></li>
        <li><a href="transactions.php">Transactions</a></li>
    </ul>
    <?php
} else {
    ?>
    <h1>User Dashboard</h1>
    <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
    <ul>
        <li><a href="cash_flow.php">Cash Flow</a></li>
        <li><a href="income.php">Income</a></li>
        <li><a href="expenses.php">Expenses</a></li>
    </ul>
    <?php
}
?>
