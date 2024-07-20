<?php 
session_start();
require __DIR__ . "/config.php";
$conn = new mysqli($config["db"]["hostname"],
$config["db"]["username"],
$config["db"]["password"],
$config["db"]["database"]);

$errors = array();

      $email = $_SESSION['mail'];
      if($email == false){
     header('Location: selectAccount.php');
}

 //Checking if the user already exist
 $emailQuery = "SELECT * FROM tbl_user WHERE email= '$email'";
 $emailRun = mysqli_query($conn,$emailQuery);

 if($emailRun){
    $row = mysqli_fetch_assoc($emailRun);
        $databaseCode = $row['verified'];
    
    if(isset($_POST["submitcode"])){
        $code = $conn -> real_escape_string($_POST["code"]);
        //checking if the entered coded matches the code in the database
        if($code == $databaseCode){
            $updateQuery = "UPDATE tbl_user SET verified='verified', active='active' WHERE email= '$email'";
            if(mysqli_query($conn,$updateQuery)){
                header('location: success.php');
            }
            
        }else{
            $errors['u'] = "You have entered a wrong code! Please copy and paste the code sent to your email";
        }
        
    }
 }



?>



<!doctype html>
<html>
<head>
<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<title>forgot password</title>
<link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css' rel='stylesheet'>
<link href='https://use.fontawesome.com/releases/v5.7.2/css/all.css' rel='stylesheet'>
<style>@import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

* {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif
}

body {
    height: 100vh;
    background: linear-gradient(to top, white 50%, grey 96%) no-repeat
}

.container {
    margin: 50px auto
}

.panel-heading {
    text-align: center;
    margin-bottom: 10px
}

#forgot {
    min-width: 100px;
    margin-left: auto;
    text-decoration: none
}

a:hover {
    text-decoration: none
}

.form-inline label {
    padding-left: 10px;
    margin: 0;
    cursor: pointer
}

.btn.btn-primary {
    margin-top: 20px;
    border-radius: 15px
}

.panel {
    min-height: 380px;
    box-shadow: 20px 20px 80px rgb(218, 218, 218);
    border-radius: 12px
}

.input-field {
    border-radius: 5px;
    padding: 5px;
    display: flex;
    align-items: center;
    cursor: pointer;
    border: 1px solid #ddd;
    color: #4343ff
}

input[type='text'],
input[type='password'] {
    border: none;
    outline: none;
    box-shadow: none;
    width: 100%
}

.fa-eye-slash.btn {
    border: none;
    outline: none;
    box-shadow: none
}

img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 50%;
    position: relative
}

a[target='_blank'] {
    position: relative;
    transition: all 0.1s ease-in-out
}

.bordert {
    border-top: 1px solid #aaa;
    position: relative
}

.bordert:after {
    content: "My Profile";
    position: absolute;
    top: -13px;
    left: 35%;
    background-color: #fff;
    padding: 0px 8px
}

@media(max-width: 360px) {
    #forgot {
        margin-left: 0;
        padding-top: 10px
    }

    body {
        height: 100%
    }

    .container {
        margin: 30px 0
    }

    .bordert:after {
        left: 25%
    }
}</style>
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script type='text/javascript' src='https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js'></script>
<script type='text/javascript' src='https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js'></script>
</head>
<body oncontextmenu='return false' class='snippet-body'>
<div class="container">
    <div class="row">
        <div class="offset-md-2 col-lg-5 col-md-7 offset-lg-4 offset-md-3">
            <div class="panel border bg-white">
                <div class="panel-heading">
                    <h3 class="pt-3 font-weight-bold">OTP Verification</h3>
                </div>
                <?php 
                    if(isset($_SESSION['info'])){
                        ?>
                        <div class="alert alert-success text-center" style="padding: 0.4rem 0.4rem">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                        <?php
                    }
                    ?>

                <?php
                    ?>
                <div class="panel-body p-3">
                    <form method="POST" autocomplete="">
                        <div class="form-group py-2">
                            <div class="input-field"> <span class="far fa-user p-2"></span>
                             <input type="text" name="code"  placeholder="Enter verification code" required> </div>
                        </div>
                        <p style="color:red" id="err"><?php if(isset( $errors['u'])) echo $errors['u']; ?></p>
                        <div class="form-inline">             
                        <button type="submit" name="submitcode" class="btn btn-primary btn-block mt-3">Submit</button>
                    </form>
                    
                </div>
                &#160
                <div class="mx-3 my-2 py-2 bordert">
                    
            </div>
            <a href="login.php" class="font-weight-bold" style="margin-left:20%">Already have account? singin!</a>
        </div>
    </div>
</div>
<script type='text/javascript'></script>
</body>
</html>