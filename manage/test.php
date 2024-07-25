<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farm_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data
$sql = "SELECT transaction_type, SUM(amount) as total_amount FROM financial_transactions GROUP BY transaction_type";
$result = $conn->query($sql);

$expenses = 0;
$revenues = 0;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row['transaction_type'] == 'expense') {
            $expenses = $row['total_amount'];
        } elseif ($row['transaction_type'] == 'revenue') {
            $revenues = $row['total_amount'];
        }
    }
} else {
    echo "No data found";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Transactions Chart</title>
    <script src="../assets/js/chart.js"></script>
    <style>
        .chart-container {
            width: 80%;
            margin: auto;
        }
    </style>
      <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body>
<div class="chart-container">
    <canvas id="financialChart"></canvas>
    <button onclick="updateChart('bar')">Bar Chart</button>
    <button onclick="updateChart('line')">Line Chart</button>
    <button onclick="updateChart('pie')">Pie Chart</button>
</div>

<script>
    const expenses = <?php echo $expenses; ?>;
    const revenues = <?php echo $revenues; ?>;

    const ctx = document.getElementById('financialChart').getContext('2d');
    let chartType = 'bar'; // Default chart type
    let financialChart = new Chart(ctx, {
        type: chartType,
        data: {
            labels: ['Expenses', 'Revenues'],
            datasets: [{
                label: 'Amount',
                data: [expenses, revenues],
                backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(75, 192, 192, 0.2)'],
                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(75, 192, 192, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    function updateChart(type) {
        financialChart.destroy();
        financialChart = new Chart(ctx, {
            type: type,
            data: {
                labels: ['Expenses', 'Revenues'],
                datasets: [{
                    label: 'Amount',
                    data: [expenses, revenues],
                    backgroundColor: type === 'pie' ? ['rgba(255, 99, 132, 0.2)', 'rgba(75, 192, 192, 0.2)'] : ['rgba(255, 99, 132, 0.2)', 'rgba(75, 192, 192, 0.2)'],
                    borderColor: ['rgba(255, 99, 132, 1)', 'rgba(75, 192, 192, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: type !== 'pie' ? {
                    y: {
                        beginAtZero: true
                    }
                } : {}
            }
        });
    }
</script>
<?php
    include '../nav/footer.php';  //Top navbar
  ?>
</div>
<!-- ./wrapper -->



<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="../dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="../plugins/chart.js/Chart.min.js"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../dist/js/pages/dashboard3.js"></script>
</body>
</html>
