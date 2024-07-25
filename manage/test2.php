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

// Fetch data grouped by transaction date
$sql = "SELECT transaction_date, 
               SUM(CASE WHEN transaction_type = 'expense' THEN amount ELSE 0 END) as total_expense, 
               SUM(CASE WHEN transaction_type = 'revenue' THEN amount ELSE 0 END) as total_revenue 
        FROM financial_transactions 
        GROUP BY transaction_date";
$result = $conn->query($sql);

$dates = [];
$expenses = [];
$revenues = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $dates[] = $row['transaction_date'];
        $expenses[] = $row['total_expense'];
        $revenues[] = $row['total_revenue'];
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
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <style>
        .chart-container {
            width: 80%;
            margin: auto;
        }
    </style>
</head>
<body>
<div class="chart-container">
    <div id="financialChart"></div>
    <button onclick="updateChart('column')">Bar Chart</button>
    <button onclick="updateChart('line')">Line Chart</button>
    <button onclick="updateChart('pie')">Pie Chart</button>
</div>

<script>
    const dates = <?php echo json_encode($dates); ?>;
    const expenses = <?php echo json_encode($expenses); ?>;
    const revenues = <?php echo json_encode($revenues); ?>;

    let chartType = 'line'; // Default chart type

    const chartOptions = {
        chart: {
            renderTo: 'financialChart',
            type: chartType
        },
        title: {
            text: 'Financial Transactions'
        },
        xAxis: {
            categories: dates,
            title: {
                text: 'Date'
            }
        },
        yAxis: {
            title: {
                text: 'Amount'
            },
            min: 0
        },
        series: [
            {
                name: 'Expenses',
                data: expenses,
                color: 'rgba(255, 99, 132, 0.7)'
            },
            {
                name: 'Revenues',
                data: revenues,
                color: 'rgba(75, 192, 192, 0.7)'
            }
        ]
    };

    let financialChart = Highcharts.chart(chartOptions);

    function updateChart(type) {
        financialChart.update({
            chart: {
                type: type
            }
        });

        if (type === 'pie') {
            const pieData = [
                {
                    name: 'Expenses',
                    y: expenses.reduce((a, b) => a + b, 0),
                    color: 'rgba(255, 99, 132, 0.7)'
                },
                {
                    name: 'Revenues',
                    y: revenues.reduce((a, b) => a + b, 0),
                    color: 'rgba(75, 192, 192, 0.7)'
                }
            ];

            financialChart.series[0].setData(pieData);
            financialChart.series[1].remove();
        } else {
            financialChart.series[0].setData(expenses);
            financialChart.series[1].setData(revenues);

            if (financialChart.series.length === 1) {
                financialChart.addSeries({
                    name: 'Revenues',
                    data: revenues,
                    color: 'rgba(75, 192, 192, 0.7)'
                });
            }
        }
    }
</script>
</body>
</html>
