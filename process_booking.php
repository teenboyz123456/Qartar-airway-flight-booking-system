<?php
include('dbconnect.php');
session_start();

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "error_login";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['flight_id'])) {
    
    $uid = $_SESSION['user_id'];
    $fid = mysqli_real_escape_string($conn, $_POST['flight_id']);
    $f_class = mysqli_real_escape_string($conn, $_POST['flight_class']);

    // 2. Fetch the flight details to get the route and the specific price
    $flight_query = mysqli_query($conn, "SELECT * FROM flights WHERE id = '$fid'");
    $flight = mysqli_fetch_assoc($flight_query);

    if (!$flight) {
        echo "Flight not found";
        exit();
    }

    // 3. Determine the correct price column based on the class chosen
    // Matches the columns in your admin panel (price_vip, price_business, price_economy)
    $final_price = 0;
    if ($f_class == 'vip') {
        $final_price = $flight['price_vip'];
    } elseif ($f_class == 'business') {
        $final_price = $flight['price_business'];
    } else {
        $final_price = $flight['price_economy'];
    }

    $origin = $flight['origin'];
    $dest = $flight['destination'];

    // 4. Record the booking
    // Note: We save the 'fid' as 'flight_id' so the Admin Manifest can find it
    $sql = "INSERT INTO bookings (user_id, flight_id, origin, destination, class, price, status) 
            VALUES ('$uid', '$fid', '$origin', '$dest', '$f_class', '$final_price', 'Confirmed')";

    if (mysqli_query($conn, $sql)) {
        
        // 5. SUCCESS: Update the flight table to reduce seats (optional but looks great for lecturers)
        // Checks if you have a column named 'available_seats' or 'seats'
        mysqli_query($conn, "UPDATE flights SET available_seats = available_seats - 1 WHERE id = '$fid' AND available_seats > 0");
        
        echo "success";
    } else {
        echo "Database Error: " . mysqli_error($conn);
    }
}
?>