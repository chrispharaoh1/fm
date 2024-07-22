<?php
// Database connection
require __DIR__ . "/config.php";
$conn = new mysqli($config["db"]["hostname"],
$config["db"]["username"],
$config["db"]["password"],
$config["db"]["database"]);

     if(isset($_GET["id"])){
        $id = $_GET["id"];

        $sql = "DELETE FROM crops WHERE id = $id";
        if($conn->query($sql)){
            echo"
                <script>alert('One crop entry deleted!')</script>
            ";
            header("Location: crops.php"); die();
        }
        
    }
    
    
?>