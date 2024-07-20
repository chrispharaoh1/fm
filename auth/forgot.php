<?php
  session_start();
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  require 'vendor/autoload.php';

  //Create an instance; passing `true` enables exceptions
  $mail = new PHPMailer(true);


  require __DIR__ . "/config.php";
  $conn = new mysqli($config["db"]["hostname"],
  $config["db"]["username"],
  $config["db"]["password"],
  $config["db"]["database"]);

  $errors = array();
  $email = "";
  $code = rand(999999, 111111);

  //validating input
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $conn -> real_escape_string($_POST["email"]);

    //creating sessions
    $info = "We've sent a verification code (otp) to your email - $email";
    $_SESSION['info'] = $info;
    $_SESSION['mail'] = $email;

    if(empty($email)){
      $errors['er'] = "Please enter a valid email";
    }
    
    if(count($errors) == 0 && !empty($email)){
      //Checking if the email exisit in the database
      $query = "SELECT * FROM tbl_user WHERE email='$email'";
      if($run = mysqli_query($conn,$query)){

        if(mysqli_num_rows($run) > 0){

          //adding code to the database to be verified
          $updateQuery = "UPDATE tbl_user SET forgot='$code', active='active' WHERE email= '$email'";
          mysqli_query($conn,$updateQuery);

          if($row = mysqli_fetch_assoc($run)){
            $name = $row["fname"];

            try {

              $from = 'divalachrist@gmail.com';  // you mail
              $password = 'hnhz bllw zeja blyc';//"xgosumqjwfmimymn";  // your mail password
          
              //Server settings
              //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
              $mail->isSMTP();                                            //Send using SMTP
              $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
              $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
              $mail->Username   = $from;                     //SMTP username
              $mail->Password   = $password;                               //SMTP password
              $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
              $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
          
              //Recipients
              $mail->setFrom($from, 'My Profile');
              $mail->addAddress($email);     //Add a recipient
 
              //Content
              $mail->isHTML(true);                                  //Set email format to HTML
              $mail->Subject = 'Password resert';
              // $mail->Body    = "Your Verification code is <b>$code</b>";
              $mail->Body    = "
              <div class='container' style='max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>
              <h1 style='color: #333333;'>Password resert code</h1>
              <p style='color: #666666; font: size 15px;'>Dear $name,</p>
              <p style='color: #666666; font: size 15px;'>You requested for password resert. Please use the following verification code to set up new password:</p>
              <div class='verification-code' style='padding: 15px; background-color: #f9f9f9; border-radius: 5px; font-size: 18px; font-weight: bold; margin-top: 20px;'>$code</div>
              <p style='color: #666666; font: size 15px;'>If you did not request this verification code, you can ignore this email.</p>
              <p style='color: #666666; font: size 15px;'>Regards,</p>
              <p style='color: #666666; font: size 15px;'>My profile</p>
             </div>
             ";

              //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
              if($mail->send()){
                header('location: forgot-code.php');  
              }
                
              
          } catch (Exception $e) {
              //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
          }


             
          }
        } else{
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
              <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control rounded-input" id="email" value="<?php echo $email;?>" name="email" placeholder="Enter your registered email">
              </div>
              <p style="color:red;"><?php if(isset( $errors['er'])) echo $errors['er']; ?></p>
              <p style="color:red;"><?php if(isset( $errors['not'])) echo $errors['not']; ?></p>
              <button type="submit" style="width:50%; margin-left: 25%;margin-right: 25%;" class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


    <!-- Footer starts here -->
   
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
