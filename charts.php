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

// Query to retrieve income and expense data
$query = "SELECT SUM(amount) as total, type FROM transactions GROUP BY type";
$result = mysqli_query($conn, $query);

// Create an array to store the data
$data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = array('label' => $row['type'], 'value' => $row['total']);
}

// Close the database connection
mysqli_close($conn);

// Create a chart using Google Charts
echo "<script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>";
echo "<script type='text/javascript'>";
echo "google.charts.load('current', {packages: ['corechart']});";
echo "google.charts.setOnLoadCallback(drawChart);";
echo "function drawChart() {";
echo "var data = google.visualization.arrayToDataTable([";
echo "['Type', 'Total'],";
foreach ($data as $row) {
    echo "['".$row['label']."', ".$row['value']."],";
}
echo "]);";
echo "var options = {";
echo "title: 'Income and Expense Chart',";
echo "legend: 'none'";
echo "};";
echo "var chart = new google.visualization.PieChart(document.getElementById('chart_div'));";
echo "chart.draw(data, options);";
echo "}";
echo "</script>";
echo "<div id='chart_div' style='width: 500px; height: 300px;'></div>";
?>