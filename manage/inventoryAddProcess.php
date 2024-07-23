<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'farm_management');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Input validation
$itemType = isset($_POST['itemType']) ? trim($_POST['itemType']) : '';
$itemName = isset($_POST['itemName']) ? trim($_POST['itemName']) : '';
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
$unit = isset($_POST['unit']) ? trim($_POST['unit']) : '';
$lowInventoryThreshold = isset($_POST['lowInventoryThreshold']) ? (int)$_POST['lowInventoryThreshold'] : 0;

$errors = [];

if (empty($itemType)) {
    $errors[] = 'Item type is required.';
}

if (empty($itemName)) {
    $errors[] = 'Item name is required.';
}

if ($quantity <= 0) {
    $errors[] = 'Quantity must be greater than zero.';
}

if (empty($unit)) {
    $errors[] = 'Unit is required.';
}

if ($lowInventoryThreshold <= 0) {
    $errors[] = 'Low inventory threshold must be greater than zero.';
}

if (count($errors) === 0) {
    $stmt = $conn->prepare("INSERT INTO inventory (item_type, item_name, quantity, unit, low_inventory_threshold) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('ssisi', $itemType, $itemName, $quantity, $unit, $lowInventoryThreshold);

    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    foreach ($errors as $error) {
        echo "<p style='color: red;'>$error</p>";
    }
}

$conn->close();
?>
