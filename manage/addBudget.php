<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli('localhost', 'root', '', 'farm_management');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $budgetName = $conn->real_escape_string($_POST['budgetName']);
    $amount = $conn->real_escape_string($_POST['amount']);
    $startDate = $conn->real_escape_string($_POST['startDate']);
    $endDate = $conn->real_escape_string($_POST['endDate']);

    $sql = "INSERT INTO budgets (budget_name, amount, start_date, end_date) 
            VALUES ('$budgetName', '$amount', '$startDate', '$endDate')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header("Location: finance.php");
    exit();
}
?>
