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

// Fetch data from the database
$sql = "SELECT crop_name, planting_date, growth_stage, farm_size, yield_prediction FROM crops";
$result = $conn->query($sql);
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
  <title>Home</title>

  <!-- Google Font: Source Sans Pro -->

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
            <h1 class="m-0">Crop Management</h1>
            
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Crop Management</li>
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
        <!-- Button -->
        <div class="create-db-button" data-bs-toggle="modal" data-bs-target="#createCropModal">
            <i class="fas fa-seedling fa-3x"></i> <!-- Seedling icon for adding new crop -->
            <div>Create a new crop</div>
        </div>
    </div>
<!-- 
    Table -->

<div class="card" style="width: 95%; margin-top: 20px; margin-left: auto; margin-right: auto; ">
<div class="container mt-5">
<div class='table-responsive'>
<table id="example" class="table table-striped table-bordered ">
            <thead>
                <tr>
                    <th>Crop Name</th>
                    <th>Date Planted</th>
                    <th>Growth Stage</th>
                    <th>Farm Size</th>
                    <th>Yield Prediction</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['crop_name']}</td>
                                <td>{$row['planting_date']}</td>
                                <td>{$row['growth_stage']}</td>
                                <td>{$row['farm_size']}</td>
                                <td>{$row['yield_prediction']}</td>
                                <td>
                                    <div class='action-dropdown'>
                                        <i class='fas fa-ellipsis-v' data-bs-toggle='dropdown'></i>
                                        <ul class='dropdown-menu'>
                                            <li><a class='dropdown-item' href='#'><i class='fa fa-edit'></i>&#160;Edit</a></li>
                                            <li><a class='dropdown-item' href='#'><i class='fa fa-trash'></i>&#160;Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
              </div>
    </div>
      </div>

    <!-- Modal -->
    <div class="modal fade" id="createCropModal" tabindex="-1" aria-labelledby="createCropModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCropModalLabel">Add New Crop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="container mt-5">
        <?php if (isset($message)) { echo '<div class="alert alert-success">' . $message . '</div>'; } ?>
        <?php if (isset($errors) && count($errors) > 0) { ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error) { echo '<li>' . $error . '</li>'; } ?>
                </ul>
            </div>
        <?php } ?>
        <form id="cropForm" method="post" action="cropAddProcess.php" style="padding-bottom: 50px;">
            <div class="mb-3">
                <label for="cropName" class="form-label">Crop Name</label>
                <input type="text" class="form-control" id="cropName" name="cropName" required>
            </div>
            <div class="mb-3">
                <label for="datePlanted" class="form-label">Date Planted</label>
                <input type="date" class="form-control" id="datePlanted" name="datePlanted" required>
            </div>
            <div class="mb-3">
                <label for="growth" class="form-label">Growth Stage</label>
                <input type="text" class="form-control" id="growth" name="growth" required>
            </div>
            <div class="mb-3">
                <label for="farm" class="form-label">Farm Size (Total area in meters)</label>
                <input type="number" class="form-control" id="farm" name="farmsize" required>
            </div>
            <button type="submit" class="btn btn-primary">+ &#160; Save</button>
        </form>
    </div>
            </div>
        </div>
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
    <!-- <script>
        document.getElementById('cropForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const cropName = document.getElementById('cropName').value;
            alert('Crop Name: ' + cropName); // Replace this with actual save logic
            // Close the modal
            var myModal = new bootstrap.Modal(document.getElementById('createCropModal'));
            myModal.hide();
        });
    </script> -->


    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>

</body>
</html>
