<?php
session_start();
include('dbconnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fno = mysqli_real_escape_string($conn, $_POST['fno']);
    $org = mysqli_real_escape_string($conn, $_POST['org']);
    $dest = mysqli_real_escape_string($conn, $_POST['dest']);
    $dep_time = mysqli_real_escape_string($conn, $_POST['dep_time']);
    $p_vip = (float)$_POST['p_vip'];
    $p_biz = (float)$_POST['p_biz'];
    $p_eco = (float)$_POST['p_eco'];

    $query = "INSERT INTO flights (flight_no, origin, destination, departure_datetime, price_vip, price_business, price_economy, status) 
              VALUES ('$fno', '$org', '$dest', '$dep_time', '$p_vip', '$p_biz', '$p_eco', 1)";

    if (mysqli_query($conn, $query)) {
        // Use a RELATIVE path to stay in the same folder
        echo "<script>alert('Flight Deployed!'); window.location.href='admin_panel.php';</script>";
    } else {
        echo "DB Error: " . mysqli_error($conn);
    }
} else {
    header("Location: dashboard.php");
}
?>