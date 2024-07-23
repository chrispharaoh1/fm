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
$cropName = $datePlanted = $growth = $farmSize = "";
$errors = [];

// Fetch data by ID

    $id = $_GET['id'];
    $sql = "SELECT crop_name, planting_date, growth_stage, farm_size, yield_prediction FROM crops WHERE id = $id";
    
    if($result = $conn->query($sql)){
        $row = $result->fetch_assoc();
    }


// Validate and update data
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $cropName = trim($_POST["cropName"]);
    $datePlanted = trim($_POST["datePlanted"]);
    $growth = trim($_POST["growth"]);
    $farmSize = trim($_POST["farmsize"]);

    // Validate input
    if (empty($cropName)) $errors[] = "Crop Name is required.";
    if (empty($datePlanted)) $errors[] = "Date Planted is required.";
    if (empty($growth)) $errors[] = "Growth Stage is required.";
    if (empty($farmSize) || !is_numeric($farmSize)) $errors[] = "Farm Size is required and must be a number.";

    // Update data if no errors
    if (empty($errors)) {
        $sql = "UPDATE crops SET crop_name = ?, planting_date = ?, growth_stage = ?, farm_size = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdi", $cropName, $datePlanted, $growth, $farmSize, $id);

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
  <title>Edit crop</title>

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
            <h1 class="m-0">Crop Edit</h1>
            
          </div><!-- /.col -->
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Crops</a></li>
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
        <a href="crops.php" class="btn btn-secondary" style="margin-bottom: 30px;"><i class="fas fa-arrow-left"></i> Back</a>
        <h2>Edit Crop</h2>
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
                <label for="cropName" class="form-label">Crop Name</label>
                <input type="text" class="form-control" id="cropName" name="cropName" value="<?php echo $row['crop_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="datePlanted" class="form-label">Date Planted</label>
                <input type="date" class="form-control" id="datePlanted" name="datePlanted" value="<?php echo $row['planting_date']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="growth" class="form-label">Growth Stage</label>
                <input type="text" class="form-control" id="growth" name="growth" value="<?php echo $row['growth_stage']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="farm" class="form-label">Farm Size (Total area in meters)</label>
                <input type="number" class="form-control" id="farm" name="farmsize" value="<?php echo $row['farm_size']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
           
            <button type="button" class="btn btn-danger" onclick="window.location.href='crops.php'">Cancel</button>
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
