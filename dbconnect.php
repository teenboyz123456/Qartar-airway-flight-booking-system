<?php
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "qartar_airways"; // <--- This MUST match the name in phpMyAdmin

// Attempt to connect
$conn = mysqli_connect($host, $user, $pass, $db_name);

// Check if connection was successful
if (!$conn) {
    // If it fails, this will tell you WHY (e.g., wrong password or wrong DB name)
    die("Connection failed: " . mysqli_connect_error());
}
?>