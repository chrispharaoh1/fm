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
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .checkmark-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10%;
        }
        
        .checkmark-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #4CAF50;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            animation: popCircle 0.6s ease-in-out forwards;
        }
        
        .checkmark {
            width: 50px;
            height: 50px;
            border: solid #fff;
            border-width: 0 6px 6px 0;
            transform: rotate(45deg);
            animation: drawCheck 0.5s ease-in-out 0.6s forwards;
        }
        
        @keyframes popCircle {
            0% {
                transform: scale(0);
            }
            80% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }
        
        @keyframes drawCheck {
            0% {
                width: 0;
                height: 0;
            }
            100% {
                width: 25px;
                height: 50px;
            }
        }
    </style>
    <title>Success</title>
</head>
<body>
    <div class="container text-center">
        <div class="checkmark-wrapper">
            <div class="checkmark-circle">
                <div class="checkmark"></div>
            </div>
        </div>
        <h1 class="display-3">Success</h1>
        <p class="lead">One crop added successfully</p>
        <button class="btn btn-primary" onclick="window.location.href='crops.php'">Go Back</button>
    </div>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
