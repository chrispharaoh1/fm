<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli('localhost', 'root', '', 'farm_management');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $transactionType = $conn->real_escape_string($_POST['transactionType']);
    $description = $conn->real_escape_string($_POST['description']);
    $amount = $conn->real_escape_string($_POST['amount']);
    $category = $conn->real_escape_string($_POST['category']);

    $sql = "INSERT INTO financial_transactions (transaction_type, description, amount, category) 
            VALUES ('$transactionType', '$description', '$amount', '$category')";

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
