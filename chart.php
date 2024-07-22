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
class Chart {
    private $title;
    private $data;

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function display() {
       ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <canvas id="chart" width="400" height="200"></canvas>
        <script>
            var ctx = document.getElementById('chart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($this->data, 'label'));?>,
                    datasets: [{
                        label: '',
                        data: <?php echo json_encode(array_column($this->data, 'value'));?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)'
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
}
?>