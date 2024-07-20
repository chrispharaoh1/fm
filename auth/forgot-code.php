<?php
  session_start();

  $email = $_SESSION['mail'];
  if($email == false){
 header('Location: selectAccount.php');
}

  require __DIR__ . "/config.php";
  $conn = new mysqli($config["db"]["hostname"],
  $config["db"]["username"],
  $config["db"]["password"],
  $config["db"]["database"]);

  $errors = array();


  //validating input
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $code = $conn -> real_escape_string($_POST["code"]);
    $password = $conn -> real_escape_string($_POST["password"]);

     //creating sessions
     $info = "We've sent a verification code (otp) to your email - $email";
     $_SESSION['info'] = $info;
     $_SESSION['mail'] = $email;

    if(empty($email)){
      $errors['er'] = "Please enter a valid email";
      
    }
    else{
      //Checking if the email exisit in the database
      $query = "SELECT * FROM tbl_user WHERE email='$email'";
      if($run = mysqli_query($conn,$query)){

        if(mysqli_num_rows($run) > 0){
          
          $row = mysqli_fetch_assoc($run);
           
          $forgot = $row["forgot"];
          //if entered code has errors
          if($code != $forgot){
            $errors['cd'] = "You have entered a wrong code";
          }
          
            //cheching if password is matching security format
          if(preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)){
              //updating password in DB
              if(count($errors) == 0){
                if(!empty($password) && !empty($email)){
              $pass = password_hash($password, PASSWORD_DEFAULT);
              $updateQuery = "UPDATE tbl_user SET password ='$pass', forgot='0'  WHERE email= '$email'";
              if(mysqli_query($conn,$updateQuery)){
                  header('location: forgot-success.php');
              }
            }
            }
          }
        } 
        else{
          $errors['not'] = "User with that email does not exist, please create an account!";
        }
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Page Title -->
    <title>Forgot password</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/logo/favicon.png" type="image/x-icon">

    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/animate-3.7.0.css">
    <link rel="stylesheet" href="../assets/css/font-awesome-4.7.0.min.css">
    <link rel="stylesheet" href="../assets/fonts/flat-icon/flaticon.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-4.1.3.min.css">
    <link rel="stylesheet" href="../assets/css/owl-carousel.min.css">
    <link rel="stylesheet" href="../assets/css/nice-select.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
         .logo-area a img{
                width: 100%;
            }
        @media (min-width: 996px){
            .head{width: 95% !important;} 
           
          
        }

        @media (max-width: 995px){
            .logo-area a img{
                width: 40%;
            }
            .custom-navbar{
              padding-bottom:2%;
                
            }
        }

    
    .center-button {
      text-align: center;
    }
     
     </style>


</head>
<body>
    <!-- Preloader Starts -->
    <div class="preloader">
        <div class="spinner"></div>
    </div>
    <!-- Preloader End -->

    <!-- Top nav starts here -->

    <!-- Top nav ends here -->

        <!-- Content starts here -->
        <img src="../assets/images/my_profile.png" style="margin-left:auto;margin-right:auto; display:block; width:20%;" alt="logo"></img>

        
  <div class="container mt-5" style="padding-bottom:80px;">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
 
          <div class="card-body">
            <form method="post">
            <?php 
                    if(isset($_SESSION['info'])){
                        ?>
                        <div class="alert alert-success text-center" style="padding: 0.4rem 0.4rem">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                        <?php
                    }
                    ?>
              <div class="form-group">
                <label for="email">Resert code*</label>
                <input type="number" class="form-control rounded-input" id="email" value="<?php $code?>" name="code" placeholder="Enter code here">
              </div>
              <div class="form-group">
                <label for="email">New password*</label>
                <input type="password" class="form-control rounded-input" id="email" value="<?php ?>" name="password" placeholder="Enter new password">
              </div>


              <?php if (!empty($password)) {
                              
                              // Check if the password meets the requirements
                              if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
                                // Initialize an array to hold requirement statuses
                                $requirements = array(
                                "uppercase" => false,
                                "lowercase" => false,
                                "number" => false,
                                "symbol" => false,
                                "length" => false
                                );
              
                                // Check individual requirements
                                if (preg_match("/[A-Z]/", $password)) {
                                    $requirements["uppercase"] = true;
                                }
                                if (preg_match("/[a-z]/", $password)) {
                                    $requirements["lowercase"] = true;
                                }
                                if (preg_match("/\d/", $password)) {
                                  $requirements["number"] = true;
                                }
                                if (preg_match("/[@$!%*?&]/", $password)) {
                                  $requirements["symbol"] = true;
                                }
                                if (strlen($password) >= 8) {
                                  $requirements["length"] = true;
                                }
              
                                // Display the result with labels and indicators
                                echo "<strong>Password must contain:</strong><br>";
                                foreach ($requirements as $requirement => $met) {
                                  if ($met) {
                                     echo "<span style='color:green;'>&#10004; $requirement</span><br>";
                                  } else {
                                    echo "<span style='color:red;'>&#10008; $requirement</span><br>";
                                  }
                                }
                              } else {
                                    echo "Password meets all requirements.";
                              }
                              }?>
 
              <p style="color:red;"><?php if(isset( $errors['er'])) echo $errors['er']; ?></p>
              <p style="color:red;"><?php if(isset( $errors['not'])) echo $errors['not']; ?></p> 
              <p style="color:red;"><?php if(isset( $errors['cd'])) echo $errors['cd']; ?></p> 
              <button type="submit" style="width:50%; margin-left: 25%;margin-right: 25%;" class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


    <!-- Footer starts here -->
   
    <!-- Footer nav ends here -->

<!-- indentation -->
 <script src="../assets/js/vendor/jquery-2.2.4.min.js"></script>
	<script src="../assets/js/vendor/bootstrap-4.1.3.min.js"></script>
    <script src="../assets/js/vendor/wow.min.js"></script>
    <script src="../assets/js/vendor/owl-carousel.min.js"></script>
    <script src="../assets/js/vendor/jquery.nice-select.min.js"></script>
    <script src="../assets/js/vendor/ion.rangeSlider.js"></script>
    <script src="../assets/js/main.js"></script>
    <script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.classList.toggle('fa-eye-slash');
    });
  </script>
</body>
</html>
