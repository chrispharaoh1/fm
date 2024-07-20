<?php
// Requiring config file
require __DIR__ . "/config.php";

$conn = new mysqli($config["db"]["hostname"],
$config["db"]["username"],
$config["db"]["password"],
$config["db"]["database"]);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Declaring array for errors 
    $errors = array();
    // Retrieve form data
    $accountType = $_POST["accountType"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password1"];
    $password2 = $_POST["password1"];

    // Account type requirement
    if(empty($accountType)){
        $errors['type'] = "First Name is required";
    }

    // Name type requirement
    if(empty($accountType)){
        $errors['name'] = "First Name is required";
    }

      // Name type requirement
      if(empty($accountType)){
        $errors['email'] = "First Name is required";
    }

       // If passwords missmatch
       if($password != $password2){
        $errors['password'] = "First Name is required";
    }

    if(count($errors) == 0){

         // Password requirements check
    // if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
    //     die("Password must contain at least one uppercase letter, one lowercase letter, one number, one symbol, and be at least 8 characters long.");
    // } no error

    // Hashing the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO tbl_user (fname, lname, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fname, $lname, $email, $hashed_password);

    // Set parameters and execute
    $fname = ""; // Extract first name from full name if needed
    $lname = $name;
    if (strpos($name, ' ') !== false) {
        list($fname, $lname) = explode(' ', $name, 2);
    }

    if ($stmt->execute() === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    }

   
}
?>
