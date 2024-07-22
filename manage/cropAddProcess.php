<?php
require __DIR__ . "/config.php";

// Initialize error array
$errors = [];
$message = "";

// Validate inputs
$cropName = trim($_POST['cropName']);
$datePlanted = trim($_POST['datePlanted']);
$growthStage = trim($_POST['growth']);
$farmSize = trim($_POST['farmsize']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($cropName === "") {
        $errors[] = "Crop Name is required.";
    }

    if ($datePlanted === "") {
        $errors[] = "Date Planted is required.";
    }

    if ($growthStage === "") {
        $errors[] = "Growth Stage is required.";
    }

    if ($farmSize === "" || !is_numeric($farmSize) || intval($farmSize) <= 0) {
        $errors[] = "Farm Size must be a positive number.";
    }

    if (empty($errors)) {
        // Database connection
        $conn = new mysqli($config["db"]["hostname"],
        $config["db"]["username"],
        $config["db"]["password"],
        $config["db"]["database"]);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO crops (crop_name, planting_date, growth_stage, farm_size) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $cropName, $datePlanted, $growthStage, $farmSize);

        if ($stmt->execute()) {
            $message = "New record created successfully";
        } else {
            $errors[] = "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error {
            color: red;
        }
    </style>
    <title>Add Crop</title>
</head>
<body>
    <div class="container mt-5">
        <?php if ($message) { echo '<div class="alert alert-success">' . $message . '</div>'; } ?>
        <?php if (!empty($errors)) { ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error) { echo '<li>' . $error . '</li>'; } ?>
                </ul>
            </div>
        <?php } ?>
        <form id="cropForm" method="post" action="cropAddProcess.php">
            <div class="mb-3">
                <label for="cropName" class="form-label">Crop Name</label>
                <input type="text" class="form-control"
