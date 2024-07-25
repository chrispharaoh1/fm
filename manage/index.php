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
//End of connection
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://open-weather13.p.rapidapi.com/city/zomba/EN",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "x-rapidapi-host: open-weather13.p.rapidapi.com",
        "x-rapidapi-key: ebc49c9d63mshce19a6df52c6843p12f37bjsnc6b2da25ad19"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $weather = json_decode($response, true);
    $temp = intval(($weather['main']['temp']) - 32) * (5/9) ;
    $humidity = $weather['main']['humidity'];
    $suggestion = "";

    if ($temp > 30) {
        $suggestion = "It's hot, Make sure to water your crops frequently.";
    } elseif ($temp < 0) {
        $suggestion = "It's cold, Protect your livestocks from the cold.";
    } else {
        $suggestion = "The weather is moderate. Your crops should be doing well.";
    }
}
?>

<?php
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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home</title>

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
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navigation bars -->
  <?php
    include '../nav/topNav.php';  //Top navbar
    include '../nav/leftNav.php';  //left Navbar
  ?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
            
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Home</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">


  <!-- Row for chart and weather -->
    <div class="row justify-content-center">
      <!-- chart card -->




    <div class="col-md-8">
    <div class="card weather-card">
    <div class="chart-container">
    <h3 style="text-align:center;"><u>Expenses and Revenue chart</u></h3>
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
        </div>
  </div>





        <!-- weather card -->
        <div class="col-md-4">
            <div class="card weather-card">
                <div class="card-body">
                    <h3 style="text-align:center;">Weather Information for Zomba</h3>
                    <p class="card-text">Temperature: <?= $temp ?>°C</p>
                    <p class="card-text">Humidity: <?= $humidity ?>%</p>
                    <p class="card-text font-weight-bold">Suggestion: <?= $suggestion ?></p>
                </div>
            </div>
<!-- Card for budgets -->
          <div class="card weather-card">
          <h6 style="text-align: center;">Recent Budgets</h6>
          <div class='table-responsive mt-3'>
            <table id="budgetsTable" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Budget Name</th>
                  <th>Amount</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $conn = new mysqli('localhost', 'root', '', 'farm_management');
                if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
                }

                $result = $conn->query("SELECT * FROM budgets ORDER BY id DESC LIMIT 2");
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                          <td>{$row['budget_name']}</td>
                          <td>{$row['amount']}</td>
                          <td>{$row['start_date']}</td>
                          <td>{$row['end_date']}</td>
                        </tr>";
                }

                $conn->close();
                ?>
              </tbody>
            </table>
          </div>
          </div>



        </div>
    </div>










      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
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
