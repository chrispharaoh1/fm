<?php
  session_start();
  require __DIR__ . "/config.php";
  $conn = new mysqli($config["db"]["hostname"],
  $config["db"]["username"],
  $config["db"]["password"],
  $config["db"]["database"]);

  $errors = array();

  
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $conn -> real_escape_string($_POST["email"]);
    $password = $conn -> real_escape_string($_POST["password"]);

    //Getting user data from the database
    $query = "SELECT * FROM tbl_user WHERE email='$email'";
    if($run = mysqli_query($conn,$query)){
      $numberOfRows = mysqli_num_rows($run);
      if($numberOfRows > 0){
        $row = mysqli_fetch_assoc($run);
        $pass = $row['password'];
        
        //Checking if the password entered matches the password in the database
        if(password_verify($password,$pass)){
         //Creating sessions to log the user in
         $_SESSION['mail'] = $row['email'];
         $_SESSION['fname'] = $row['fname'];
         $_SESSION['lname'] = $row['lname'];
         $_SESSION['fullname'] = $row['fullname'];
         $_SESSION['accountType'] = $row['accounttype'];
         $_SESSION['lastLogin'] = $row['lastlogin'];
         $_SESSION['id'] = $row['id'];

        //===========================Personal Account validation====================================
         if($row['accounttype']=="personal" && $row['verified']=="verified" && $row['active']=="active"){
            //Login this person to the personal portal
            header('location: ../jobseeker/index.php');
         }

         //if account is verified but not active
         if($row['accounttype']=="personal" && $row['verified']=="verified" && $row['active']=="inactive"){
          //Login this person to the personal portal
          $errors['inactive'] = "Your account has been suspended, please contact support to continue!";
       }

           //if account is unverified and not active
           if($row['accounttype']=="personal" && $row['verified']=="unverified" && $row['active']=="inactive"){
            //Login this person to the personal portal
            $errors['unverified'] = "Your account is not verified yet, please verify first before signing in!";
         }

         //==========================Company validation=========================================

         if($row['accounttype']=="company" && $row['verified']=="verified" && $row['active']=="active"){
          //Login this person to the personal portal
          header('location: ../company/index.php');
       }

       //if account is verified but not active
       if($row['accounttype']=="company" && $row['verified']=="verified" && $row['active']=="inactive"){
        //Login this person to the personal portal
        $errors['inactive2'] = "Your account has been suspended, please contact support to continue!";
     }

         //if account is unverified and not active
         if($row['accounttype']=="company" && $row['verified']=="unverified" && $row['active']=="inactive"){
          //Login this person to the personal portal
          $errors['unverified2'] = "Your account is not verified yet, please verify first before signing in!";
       }
   
        }else{
          //if password is wrong
          $errors['pass'] = "Email and password combination wrong!";
            }

      }else{
        $errors['er'] = "User with that email does not exisit";
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
    <title>sign in</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/images/logo/favicon.png" type="image/x-icon">

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
    <?php include('../home/nav.php')?> 
    <!-- Top nav ends here -->

        <!-- Content starts here -->
    <img src="../assets/images/my_profile.png" style="margin-left:auto;margin-right:auto; display:block; width:20%;" alt="logo"></img>

        
  <div class="container mt-5" style="padding-bottom:80px;">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            Login
          </div>
          <div class="card-body">
            <form method="post">
              <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control rounded-input" id="email" name="email" placeholder="Enter email">
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                  <input type="password" class="form-control rounded-input" id="password" name="password" placeholder="Password">
                  <div class="input-group-append">
                    <span class="input-group-text">
                      <i class="fas fa-eye" id="togglePassword"></i>
                    </span>
                  </div>
                  
                </div>

              </div>
              <p style="color:red;"><?php if(isset( $errors['er'])) echo $errors['er']; ?></p>
              <p style="color:red;"><?php if(isset( $errors['pass'])) echo $errors['pass']; ?></p> 
              <p style="color:red;"><?php if(isset( $errors['inactive'])) echo $errors['inactive']; ?></p>
              <p style="color:red;"><?php if(isset( $errors['unverified'])) echo $errors['unverified']; ?></p>
              <p style="color:red;"><?php if(isset( $errors['inactive2'])) echo $errors['inactive2']; ?></p>
              <p style="color:red;"><?php if(isset( $errors['unverified2'])) echo $errors['unverified2']; ?></p>
              <div class="form-group">
                <a href="forgot.php" class="text-primary">Forgot password?</a>
              </div>
              <button type="submit" style="width:50%; margin-left: 25%;margin-right: 25%;" class="btn btn-primary">Login</button>
              <div class="form-group text-center">
                <p style="padding-top:25px;">Don't have an account? <a href="selectAccount.php" class="text-primary">Create Account</a></p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


    <!-- Footer starts here -->
    <?php include('../home/footer.php')?> 
    <!-- Footer nav ends here -->



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
