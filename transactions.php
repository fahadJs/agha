<?php
// Check if the user is logged in
session_start();
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


<?php include 'nav.php';?>

<?php
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get transactions data
$sql = "SELECT * FROM transactions";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Display transactions data
?>
<h1>Transactions</h1>
<table>
    <tr>
        <th>Date</th>
        <th>Type</th>
        <th>Amount</th>
        <th>Description</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) {?>
    <tr>
        <td><?php echo htmlspecialchars($row['date']);?></td>
        <td><?php echo htmlspecialchars($row['type']);?></td>
        <td><?php echo htmlspecialchars($row['amount']);?></td>
        <td><?php echo htmlspecialchars($row['description']);?></td>
    </tr>
    <?php }?>
</table>

<!-- Add new transaction entry form -->
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" onsubmit="return validateForm()">
    <label for="date">Date:</label>
    <input type="date" id="date" name="date"><br><br>
    <label for="type">Type:</label>
    <select id="type" name="type">
        <option value="income">Income</option>
        <option value="expense">Expense</option>
    </select><br><br>
    <label for="amount">Amount:</label>
    <input type="number" id="amount" name="amount"><br><br>
    <label for="description">Description:</label>
    <textarea id="description" name="description"></textarea><br><br>
    <input type="submit" name="add_transaction" value="Add Transaction Entry">
</form>

<script>
    function validateForm() {
        var date = document.getElementById("date").value;
        var type = document.getElementById("type").value;
        var amount = document.getElementById("amount").value;
        var description = document.getElementById("description").value;

        if (date == "" || type == "" || amount == "" || description == "") {
            alert("Please fill in all fields.");
            return false;
        }

        return true;
    }
</script>

<?php
// Add new transaction entry
if (isset($_POST['add_transaction'])) {
    $date = $_POST['date'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $sql = "INSERT INTO transactions (date, type, amount, description) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $date, $type, $amount, $description);
    $stmt->execute();
    header('Location: transactions.php');
    exit;
}
?>