<?php
session_start();
include('dbconnect.php');

// 1. Get Data from the POST request
$user_id = $_SESSION['user_id'] ?? 1; 
$flight_id = $_POST['flight_id'] ?? 0;
$seat_number = $_POST['seat'] ?? ''; 
$total_price = $_POST['price'] ?? 0;

// 2. Validation
if (empty($seat_number) || $flight_id == 0) {
    header("Location: booking.php?error=selection_lost");
    exit();
}

// 3. Insert into Database 
$sql = "INSERT INTO bookings (user_id, flight_id, seat_number, amount) 
        VALUES ('$user_id', '$flight_id', '$seat_number', '$total_price')";

if (mysqli_query($conn, $sql)) {
    // 4. THE PROFESSIONAL SUCCESS UI
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Booking Confirmed | Qatar Airways</title>
        <style>
            :root {
                --qatar-burgundy: #700029;
                --qatar-gold: #c4a163;
                --text-gray: #555;
            }
            body { 
                font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; 
                background-color: #f4f4f4; 
                margin: 0; 
                display: flex; 
                justify-content: center; 
                align-items: center; 
                height: 100vh; 
            }
            .confirmation-card {
                background: white;
                width: 500px;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                overflow: hidden;
                text-align: center;
                border-top: 8px solid var(--qatar-burgundy);
            }
            .header {
                padding: 30px;
                background: #fff;
            }
            .qatar-logo {
                font-weight: bold;
                font-size: 24px;
                color: var(--qatar-burgundy);
                letter-spacing: 2px;
                text-transform: uppercase;
                margin-bottom: 10px;
            }
            .success-icon {
                font-size: 60px;
                color: #2ecc71;
                margin: 20px 0;
            }
            .details {
                background: #fafafa;
                padding: 30px;
                border-top: 1px dashed #ccc;
                border-bottom: 1px dashed #ccc;
            }
            .seat-badge {
                display: inline-block;
                padding: 10px 20px;
                background: var(--qatar-burgundy);
                color: white;
                border-radius: 5px;
                font-size: 20px;
                font-weight: bold;
                margin-top: 10px;
            }
            .footer {
                padding: 25px;
                font-size: 14px;
                color: var(--text-gray);
            }
            .loading-bar {
                height: 4px;
                width: 100%;
                background: #eee;
                position: relative;
                overflow: hidden;
            }
            .loading-fill {
                height: 100%;
                background: var(--qatar-gold);
                width: 0%;
                animation: progress 3s linear forwards;
            }
            @keyframes progress {
                0% { width: 0%; }
                100% { width: 100%; }
            }
        </style>
    </head>
    <body>

    <div class="confirmation-card">
        <div class="header">
            <div class="qatar-logo">Qatar Airways</div>
            <div class="success-icon">✓</div>
            <h2 style="margin:0; color: #333;">Booking Confirmed</h2>
            <p style="color: var(--text-gray);">Thank you for choosing to fly with us.</p>
        </div>

        <div class="details">
            <p style="margin: 0; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">Confirmed Seat</p>
            <div class="seat-badge"><?php echo $seat_number; ?></div>
            <p style="margin-top: 15px; font-weight: bold;">Total Paid: $<?php echo number_format($total_price, 2); ?></p>
        </div>

        <div class="footer">
            <p>Your e-ticket is being generated...</p>
            <div class="loading-bar">
                <div class="loading-fill"></div>
            </div>
        </div>
    </div>

    <script>
        // Redirecting after 3 seconds
        setTimeout(function(){ 
            window.location.href='booking.php.php'; 
        }, 3000);
    </script>

    </body>
    </html>
    <?php
} else {
    echo "Database Error: " . mysqli_error($conn);
}
?>