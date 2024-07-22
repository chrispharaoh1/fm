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
    </style>
     <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<?php
  session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crop_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add crop
if (isset($_POST['add_crop'])) {
    $name = $_POST['crop_name'];
    $sql = "INSERT INTO crops (name) VALUES ('$name')";
    $conn->query($sql);
}

// Add field
if (isset($_POST['add_field'])) {
    $name = $_POST['field_name'];
    $crop_id = $_POST['crop_id'];
    $sql = "INSERT INTO fields (name, crop_id) VALUES ('$name', $crop_id)";
    $conn->query($sql);
}

// Fetch crops and fields
$crops = $conn->query("SELECT * FROM crops");
$fields = $conn->query("SELECT fields.id, fields.name, crops.name AS crop_name FROM fields JOIN crops ON fields.crop_id = crops.id");

?>
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

<div class="row">

<div class="container mt-5">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Register Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>John Doe</td>
                    <td>john.doe@example.com</td>
                    <td>2023-07-20</td>
                    <td>
                        <div class="action-dropdown">
                            <i class="fas fa-ellipsis-v" data-bs-toggle="dropdown"></i>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item" href="#">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Jane Doe</td>
                    <td>jane.doe@example.com</td>
                    <td>2023-07-21</td>
                    <td>
                        <div class="action-dropdown">
                            <i class="fas fa-ellipsis-v" data-bs-toggle="dropdown"></i>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item" href="#">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- More rows as needed -->
            </tbody>
        </table>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>

</div>


    <!-- Modal -->
    <div class="modal fade" id="createCropModal" tabindex="-1" aria-labelledby="createCropModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCropModalLabel">Add New Crop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="cropForm">
                        <div class="mb-3">
                            <label for="cropName" class="form-label">Crop Name</label>
                            <input type="text" class="form-control" id="cropName" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('cropForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const cropName = document.getElementById('cropName').value;
            alert('Crop Name: ' + cropName); // Replace this with actual save logic
            // Close the modal
            var myModal = new bootstrap.Modal(document.getElementById('createCropModal'));
            myModal.hide();
        });
    </script>





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
</body>
</html>
