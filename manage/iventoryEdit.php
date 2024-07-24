<?php
// Database connection
require __DIR__ . "/config.php";
$conn = new mysqli($config["db"]["hostname"],
$config["db"]["username"],
$config["db"]["password"],
$config["db"]["database"]);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$itemType = $itemName = $quantity = $threshold = "";
$errors = [];

// Fetch data by ID

    $id = $_GET['id'];
    $sql = "SELECT * FROM inventory WHERE id = $id";
    
    if($result = $conn->query($sql)){
        $row = $result->fetch_assoc();
    }


// Validate and update data
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $itemType = trim($_POST["itemtype"]);
    $itemName = trim($_POST["itemname"]);
    $quantity = trim($_POST["quantity"]);
    $unit = trim($_POST["unit"]);
    $threshold = trim($_POST["threshold"]);

    // Validate input
    if (empty($itemType)) $errors[] = "Item type is required.";
    if (empty($unit)) $errors[] = "Unit is required.";
    if (empty($itemName)) $errors[] = "Item Name is required.";
    if (empty($quantity)) $errors[] = "Quantity is required.";
    if (empty($threshold) || !is_numeric($threshold)) $errors[] = "Threshold is required and must be a number.";

    // Update data if no errors
    if (empty($errors)) {
        $sql = "UPDATE inventory SET item_type = ?, item_name = ?, quantity = ?, low_inventory_threshold = ?, unit = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssddsi", $itemType, $itemName, $quantity, $threshold, $unit, $id);


        //instering in reporting table
        $itemId = intval($_GET['id']);
        $AvailableQuantity = $row['quantity'];
        //calculating used quantity
        $enteredValue = $quantity;
        $newValue = $enteredValue - $AvailableQuantity;
        
        $query = "INSERT INTO `inventory_usage`(`inventory_id`, `quantity_used`) VALUES ($itemId,'$newValue')";
        mysqli_query($conn, $query);

       $totalLeft = $newValue + $AvailableQuantity;

        if($totalLeft <= $row['low_inventory_threshold']){
            echo "Yes";
        }

        if ($stmt->execute()) {
            // header("Location: success.php"); // Redirect to a success page
            
            // exit();
            echo "<script>alert('Record updated successifully')</script>";
        } else {
            $errors[] = "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>


<style>
        .create-db-button {
            border: 2px dashed #ddd;
            text-align: center;
            padding: 40px;
            border-radius: 10px;
            cursor: pointer;
        }
        .create-db-button:hover {
            background-color: #f8f8f8;
        }

        
        .error {
            color: red;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Iventory Edit</title>

  <!-- Google Font: Source Sans Pro -->

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>

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
            <h1 class="m-0">Iventory Edit</h1>
            
          </div><!-- /.col -->
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Iventory</a></li>
              <li class="breadcrumb-item active">Edit</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
      
        <div class="row">
        
        <div class="container mt-5">
        <a href="iventory.php" class="btn btn-secondary" style="margin-bottom: 30px;"><i class="fas fa-arrow-left"></i> Back</a>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form method="post" style="padding-bottom: 50px;">
            <input type="hidden" name="id" value="<?php echo intval($_GET['id']); ?>">

            <div class="mb-3">
                        <label for="editItemType" class="form-label"  >Item Type</label>
                        <select class="form-control" id="editItemType" name="itemtype" value="<?php echo $row['item_type']; ?>">
                            <option value="seed">Seed</option>
                            <option value="fertilizer">Fertilizer</option>
                            <option value="pesticide">Pesticide</option>
                        </select>
                    </div>
            <div class="mb-3">
                <label for="datePlanted" class="form-label">Item name</label>
                <input type="text" class="form-control" id="datePlanted" name="itemname" value="<?php echo $row['item_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="growth" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="growth" name="quantity" value="<?php echo $row['quantity']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="farm" class="form-label">Unit</label>
                <input type="text" class="form-control" id="farm" name="unit" value="<?php echo $row['unit']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="farm" class="form-label">Low Inventory Threshold</label>
                <input type="number" class="form-control" id="threashhold" name="threshold" value="<?php echo $row['low_inventory_threshold']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
           
            <button type="button" class="btn btn-danger" onclick="window.location.href='iventory.php'">Cancel</button>
        </form>
    </div>

          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
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

<script src="../assets/js/jquery-3.7.0.js"></script>
    <script src="../assets/js/jquery.dataTables.min.js"></script>

    <script src="../assets/js/jquery-3.7.0.js"></script>
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/dataTables.bootstrap5.min.js"></script>

    <script src="../assets/js/bootstrap.bundle2.min.js"></script>
 
</body>
</html>
