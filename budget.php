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

// Query to retrieve budget data
$query = "SELECT * FROM budget";
$result = mysqli_query($conn, $query);

// Create an array to store the budget data
$budget_data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $budget_data[] = $row;
}

// Close the database connection
mysqli_close($conn);

// Display the budget data
?>
<h2>Budget</h2>
<table>
    <tr>
        <th>Category</th>
        <th>Budgeted Amount</th>
        <th>Actual Amount</th>
        <th>Variance</th>
    </tr>
    <?php foreach ($budget_data as $row) { ?>
    <tr>
        <td><?php echo $row['category']; ?></td>
        <td><?php echo $row['budgeted_amount']; ?></td>
        <td><?php echo $row['actual_amount']; ?></td>
        <td><?php echo $row['variance']; ?></td>
    </tr>
    <?php } ?>
</table>

<?php
// Add a form to add new budget items
?>
<h2>Add New Budget Item</h2>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="category">Category:</label>
    <input type="text" id="category" name="category"><br><br>
    <label for="budgeted_amount">Budgeted Amount:</label>
    <input type="number" id="budgeted_amount" name="budgeted_amount"><br><br>
    <input type="submit" value="Add Budget Item">
</form>

<?php
// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    $budgeted_amount = $_POST['budgeted_amount'];

    // Insert the new budget item into the database
    $query = "INSERT INTO budget (category, budgeted_amount) VALUES ('$category', '$budgeted_amount')";
    mysqli_query($conn, $query);
}
?>