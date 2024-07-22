<?php
    require __DIR__ . "..config/config.php";
    $conn = new mysqli($config["db"]["hostname"],
    $config["db"]["username"],
    $config["db"]["password"],
    $config["db"]["database"]);
?>