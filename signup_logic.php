<?php
include('dbconnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (fullname, email, password) VALUES ('$name', '$email', '$pass')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: login.php?success=1");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>