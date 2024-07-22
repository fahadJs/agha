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

// Get expenses data
$sql = "SELECT * FROM expenses";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Display expenses data
?>
<h1>Expenses</h1>
<table>
    <tr>
        <th>Date</th>
        <th>Type</th>
        <th>Amount</th>
        <th>Description</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $row['date']; ?></td>
        <td><?php echo $row['type']; ?></td>
        <td><?php echo $row['amount']; ?></td>
        <td><?php echo $row['description']; ?></td>
    </tr>
    <?php } ?>
</table>

<!-- Add new expense entry form -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="date">Date:</label>
    <input type="date" id="date" name="date"><br><br>
    <label for="type">Type:</label>
    <input type="text" id="type" name="type"><br><br>
    <label for="amount">Amount:</label>
    <input type="number" id="amount" name="amount"><br><br>
    <label for="description">Description:</label>
    <textarea id="description" name="description"></textarea><br><br>
    <input type="submit" name="add_expense" value="Add Expense Entry">
</form>

<?php
// Add new expense entry
if (isset($_POST['add_expense'])) {
    $date = $_POST['date'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $sql = "INSERT INTO expenses (date, type, amount, description) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $date, $type, $amount, $description);
    $stmt->execute();
    header('Location: expenses.php');
    exit;
}
?>


