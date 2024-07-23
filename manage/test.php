<?php
// Database connection
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

// Function to insert a new inventory item
function addInventoryItem($conn, $item_type, $item_name, $quantity, $unit, $low_inventory_threshold) {
    $sql = "INSERT INTO inventory (item_type, item_name, quantity, unit, low_inventory_threshold) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", $item_type, $item_name, $quantity, $unit, $low_inventory_threshold);

    if ($stmt->execute()) {
        echo "New inventory item added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Function to display all inventory items
function displayInventoryItems($conn) {
    $sql = "SELECT id, item_type, item_name, quantity, unit, low_inventory_threshold, created_at, updated_at FROM inventory";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Item Type</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Low Inventory Threshold</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['item_type']}</td>
                    <td>{$row['item_name']}</td>
                    <td>{$row['quantity']}</td>
                    <td>{$row['unit']}</td>
                    <td>{$row['low_inventory_threshold']}</td>
                    <td>{$row['created_at']}</td>
                    <td>{$row['updated_at']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No inventory items found.";
    }
}

// Example usage
addInventoryItem($conn, 'seed', 'Corn Seeds', 100, 'kg', 10);
addInventoryItem($conn, 'fertilizer', 'NPK Fertilizer', 50, 'bags', 5);
addInventoryItem($conn, 'pesticide', 'Insecticide', 30, 'liters', 3);

displayInventoryItems($conn);

// Close connection
$conn->close();
?>
