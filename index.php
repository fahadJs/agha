<?php
require_once 'config.php';
require_once 'classes.php';
require_once 'functions.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<nav>
    <ul>
        <li><a href="index.php?dashboard">Dashboard</a></li>
        <li><a href="index.php?add_transaction">Add Transaction</a></li>
        <li><a href="index.php?integrate">Integrate with Bank API</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<?php
// Display the dashboard
if (isset($_GET['dashboard'])) {
    $report = new Report();
    $data = $report->generate();
    ?>
    <h1>Dashboard</h1>
    <table>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Category</th>
            <th>Amount</th>
            <th>Description</th>
        </tr>
        <?php foreach ($data as $row) {?>
            <tr>
                <td><?php echo $row['date'];?></td>
                <td><?php echo $row['type'];?></td>
                <td><?php echo $row['category'];?></td>
                <td><?php echo $row['amount'];?></td>
                <td><?php echo $row['description'];?></td>
            </tr>
        <?php }?>
    </table>

    <h2>Charts</h2>
    <canvas id="chart" width="400" height="200"></canvas>
    <script>
        var ctx = document.getElementById('chart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    <?php
                    $chart = new Chart();
                    $data = $chart->generate();
                    foreach ($data as $row) {
                        echo "'" . $row['label'] . "',";
                    }
                    ?>
                ],
                datasets: [{
                    label: 'Transaction Amount',
                    data: [
                        <?php
                        foreach ($data as $row) {
                            echo $row['value'] . ",";
                        }
                        ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <?php
}

// Add transaction form
elseif (isset($_GET['add_transaction'])) {
    ?>
    <h1>Add Transaction</h1>
    <form action="index.php" method="post">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date"><br><br>
        <label for="type">Type:</label>
        <select id="type" name="type">
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </select><br><br>
        <label for="category">Category:</label>
        <input type="text" id="category" name="category"><br><br>
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount"><br><br>
        <label for="description">Description:</label>
        <textarea id="description" name="description"></textarea><br><br>
        <input type="submit" value="Add Transaction">
    </form>

    <?php
    if (isset($_POST['date']) && isset($_POST['type']) && isset($_POST['category']) && isset($_POST['amount']) && isset($_POST['description'])) {
        $transaction = new Transaction(validate_input($_POST['date']), validate_input($_POST['type']), validate_input($_POST['category']), validate_input($_POST['amount']), validate_input($_POST['description']));
        $transaction->save();
        echo "Transaction added successfully!";
    }
}

// Integrate with bank API
elseif (isset($_GET['integrate'])) {
    $integration = new Integration();
    $integration->integrate();
    echo "Integration with bank API successful!";
}
?>
