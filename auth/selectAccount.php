<?php
    session_start();
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
 
  
// Requiring config file
require __DIR__ . "/config.php";
//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);


$conn = new mysqli($config["db"]["hostname"],
$config["db"]["username"],
$config["db"]["password"],
$config["db"]["database"]);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$email = "";
$name = "";
$code = rand(999999, 111111);

// Form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Declaring array for errors 
    $errors = array();
    $date = date("d-m-Y");
    // Retrieve form data
    $accountType = $_POST["accountType"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password1"];
    $password2 = $_POST["password2"];

    $name = $conn -> real_escape_string($name);
    $email = $conn -> real_escape_string($email);
    $password = $conn -> real_escape_string($password);
    $password2 = $conn -> real_escape_string($password2);


    //Checking if the user already exist
    $emailQuery = "SELECT email FROM tbl_user WHERE email='$email'";
    $emailRun = mysqli_query($conn,$emailQuery);

    //session variables
    $info = "We've sent a verification code (otp) to your email - $email";
    $_SESSION['info'] = $info;
    $_SESSION['mail'] = $email;

    if($emailRun){
      if(!empty($email)){
        if(mysqli_num_rows($emailRun)>0){
          $errors['u'] = "User with that email already exist";
      } 
      }
  

    }

    // Account type requirement
    if(empty($accountType)){
        $errors['type'] = "Account type is required";
    }

    // Name type requirement
    if(empty($name)){
        $errors['name'] = "Name is required";
    }

      // Name type requirement
      if(empty($email)){
        $errors['email'] = "Email is required";
    }

      // Name type requirement
      if(empty($password)){
        $errors['pass'] = "Password is required";
    }


       // If passwords missmatch
       if($password != $password2){
        $errors['password'] = "Password not matching";
    }


    if(count($errors) == 0){
      if(!empty($name) && !empty($email) && !empty($name)){
        //Password verification starts here
        if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {

          //Insert data into the database
          // Hashing the password
          $hashed_password = password_hash($password, PASSWORD_DEFAULT);

          $fname = ""; // Extract first name from full name if needed
          $lname = $name;
          if (strpos($name, ' ') !== false) {
            list($fname, $lname) = explode(' ', $name, 2);
          }

          // Prepare and bind SQL statement
          $stmt = $conn->prepare("INSERT INTO tbl_user (fname, lname, email, password, accounttype, registrationDate, fullname, verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
          $stmt->bind_param("ssssssss",$fname, $lname, $email, $hashed_password, $accountType, $date, $name, $code);

          // Set parameters and execute
         

          if ($stmt->execute() === TRUE) {
            // echo "New record created successfully";

            // Send email if data inserted
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
              //$mail->addReplyTo('mccarthypharaoh@gmail.com', 'MC');
              // $mail->addCC('cc@example.com');
              // $mail->addBCC('bcc@example.com');
          
              // //Attachments
              // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
              // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
          
              //Content
              $mail->isHTML(true);                                  //Set email format to HTML
              $mail->Subject = 'Email verification code';
              // $mail->Body    = "Your Verification code is <b>$code</b>";
              $mail->Body    = "
              <div class='container' style='max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>
              <h1 style='color: #333333;'>Email Verification</h1>
              <p style='color: #666666; font: size 15px;'>Dear $name,</p>
              <p style='color: #666666; font: size 15px;'>Thank you for signing up. Please use the following verification code to complete your registration:</p>
              <div class='verification-code' style='padding: 15px; background-color: #f9f9f9; border-radius: 5px; font-size: 18px; font-weight: bold; margin-top: 20px;'>$code</div>
              <p style='color: #666666; font: size 15px;'>If you did not request this verification code, you can ignore this email.</p>
              <p style='color: #666666; font: size 15px;'>Regards,</p>
              <p style='color: #666666; font: size 15px;'>My profile</p>
             </div>
             ";

              //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
              if($mail->send()){
                header('location: confirm-code.php');
              }
                
              
          } catch (Exception $e) {
              //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
          }
          } else {
            echo "Error: " . $stmt->error;
       

        }

        $stmt->close();
        $conn->close();

        }
      }


    }

    
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign up</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    .step-content {
      display: none;
    }

    .step-content.active {
      display: block;
    }
  </style>
      <style>
         .logo-area a img{
                width: 100%;
            }
        @media (min-width: 996px){
            .head{width: 95% !important;} 

            .vl {
                    border-left: 6px solid green;
                    height: 200px;
                    
                }
           
          
        }

        @media (max-width: 995px){
            .logo-area a img{
                width: 40%;
            }
            .custom-navbar{
              padding-bottom:2%;

                
            }
            .vl {
                    border-left: 6px solid green;
                    width: 200px;
                    margin-top:20%;
            } 
        }

    
    .center-button {
      text-align: center;
    }

    #prof{
        margin-top: 10%;
    }
     
     </style>

</head>
<body>
    
    <!-- Footer starts here -->
    <?php include('../home/nav.php')?> 
    <!-- Footer nav ends here -->

    

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">

   <!-- form starts here  id="btn"-->
      <form id="stepForm"  method="post">
        <div class="step-content active" id="step1">
        <!-- <img src="../assets/images/my_profile.png" style="margin-left:auto;margin-right:auto; display:block; width:20%;" alt="logo"></img> -->
          <h4 sytle="text-align: center !imporatant; padding-bottom:20px;">Select type of account you want</h4>
          <div class="form-group">
            <!-- <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required> -->

    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-5" id="prof">
                <h3>IT'S FREE, GET MORE FROM MYPROFILE</h3>
              </div>

              <div class="col-md-1">
                <div class="vl"></div>
              </div>

              <div class="col-md-6">
                
                <p>Choose from 2 types of <span style="color:#ff9902">accounts*:</span></p>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="accountType" id="personalAccount" value="personal" checked>
                  <label class="form-check-label" for="personalAccount">
                    <strong>Personal acount</strong>
                    <p>With personal account you can search and and find jobs, Design and download profesional CVs, Create Portfolio and more.</p>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="accountType" id="companyAccount" value="company" >
                  <label class="form-check-label" for="companyAccount">
                  <strong>Company acount</strong>
                    <p>With this account you can advertise your vacancy, manage the selection process,  use the machine learning algorithms
                        avilable to select the best candidates and do more.
                    </p>
                    <p style="color:red" id="err"><?php if(isset( $errors['type'])) echo $errors['type']; ?></p>
                  </label>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
 </div>

  <button class="btn btn-primary next" type="button" style="width:20%; margin-left:40%; margin-right:40%; margin-bottom:50px;">Next</button>
  </div>
      <div class="step-content" id="step2">
        <img src="../assets/images/my_profile.png" style="margin-left:auto;margin-right:auto; display:block; width:20%;" alt="logo"></img>
          <!-- <h2>Step 2</h2> -->
          <!-- Fields for step two starts here here -->              
  <div class="container mt-5" style="padding-bottom:20px;">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            Sign up
          </div>
          <div class="card-body">
          <div class="form-group">
          
                <label for="name">Full name/Company name<span style="color:#ff9902">*</span></label>
                <input type="text" class="form-control rounded-input" name="name" id="email" placeholder="Enter full name or company name" value="<?php echo $name;?>">
                <p style="color:red" id="err"><?php if(isset( $errors['name'])) echo $errors['name']; ?></p>
              </div>
           
              <div class="form-group">
                <label for="email">Email address<span style="color:#ff9902">*</span></label>
                <input type="email" class="form-control rounded-input" name="email" id="email" placeholder="Enter email" value="<?php echo $email;?>">
                <p style="color:red" id="err"><?php if(isset( $errors['email'])) echo $errors['email']; ?></p>
                <p style="color:red" id="err"><?php if(isset( $errors['u'])) echo $errors['u']; ?></p>
              </div>
              <div class="form-group">
                <label for="password">Password<span style="color:#ff9902">*</span></label>
                <div class="input-group">
                  <input type="password" name="password1" class="form-control rounded-input" id="password" placeholder="Password">
                  <div class="input-group-append">
                    <span class="input-group-text">
                      <i class="fas fa-eye" id="togglePassword"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="password">Confirm Password<span style="color:#ff9902">*</span></label>
                <div class="input-group">
                  <input type="password" name="password2" class="form-control rounded-input" id="password" placeholder="Password">
                  <div class="input-group-append">
                    <span class="input-group-text">
                      <i class="fas fa-eye" id="togglePassword"></i>
                    </span>
                    
                  </div>
                  
                </div>
                <p style="color:red" id="err"><?php if(isset( $errors['password'])) echo $errors['password']; ?></p>
                <p style="color:red" id="err"><?php if(isset( $errors['pass'])) echo $errors['pass']; ?></p>
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
              </div>
              <div class="form-group text-center">
                <p style="padding-top:25px;">Already have an account? <a href="login.php" class="text-primary">Sign in</a></p>
              </div>
           
          </div>
        </div>
      </div>
    </div>
  </div>
          <!-- Fields for step 2 ends here -->
          <div class="h" style="margin-bottom:80px; margin-left:10px;">
          <button class="btn btn-primary prev" style="width:20%;" type="button">Previous</button>
          <button class="btn btn-success" id="btn" style="width:20%;" type="submit">Submit</button>
          </div>
        </div>
      </form>

      <!-- Form ends here -->
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
  $(document).ready(function () {
    var currentStep = 1;
    $('.next').click(function () {
      $('#step' + currentStep).removeClass('active');
      currentStep++;
      $('#step' + currentStep).addClass('active');
    });

    $('.prev').click(function () {
      $('#step' + currentStep).removeClass('active');
      currentStep--;
      $('#step' + currentStep).addClass('active');
    });

 
  });
</script>
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
   <!-- Footer starts here -->
   <?php include('../home/footer.php')?> 
    <!-- Footer nav ends here -->

</body>
</html>
