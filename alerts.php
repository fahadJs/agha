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

// Query to retrieve alert data
$query = "SELECT * FROM alerts";
$result = mysqli_query($conn, $query);

// Create an array to store the alert data
>alert_data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $alert_data[] = $row;
}

// Close the database connection
mysqli_close($conn);

// Display the alert data
?>
<h2>Alerts</h2>
<table>
    <tr>
        <th>Type</th>
        <th>Message</th>
        <th>Date</th>
    </tr>
    <?php foreach ($alert_data as $row) { ?>
    <tr>
        <td><?php echo $row['type']; ?></td>
        <td><?php echo $row['message']; ?></td>
        <td><?php echo $row['date']; ?></td>
    </tr>
    <?php } ?>
</table>

<?php
// Add a form to add new alerts
?>
<h2>Add New Alert</h2>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="type">Type:</label>
    <input type="text" id="type" name="type"><br><br>
    <label for="message">Message:</label>
    <input type="text" id="message" name="message"><br><br>
    <input type="submit" value="Add Alert">
</form>

<?php
// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $message = $_POST['message'];

    // Insert the new alert into the database
    $query = "INSERT INTO alerts (type, message) VALUES ('$type', '$message')";
    mysqli_query($conn, $query);
}
?>