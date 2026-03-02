<?php
session_start();
include('dbconnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        // WE SET THE SESSION KEYS HERE
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname']; 
        
        header("Location: booking.php.php");
        exit();
    } else {
        header("Location: login.php?error=1");
    }
}
?>