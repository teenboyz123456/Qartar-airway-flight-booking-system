<?php
session_start();
include('dbconnect.php');

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION['user_id'];

// Fetch the most recent booking with Flight Details
$query = "SELECT b.*, f.flight_no, f.origin, f.destination, f.dep_time, f.gate 
          FROM bookings b 
          JOIN flights f ON b.flight_id = f.id 
          WHERE b.user_id = '$uid' 
          ORDER BY b.id DESC LIMIT 1";

$result = mysqli_query($conn, $query);
$booking = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | Qatar Airways</title>
    <style>
        :root { --qatar: #700029; }
        body { font-family: 'Segoe UI', sans-serif; background: #eef2f3; margin: 0; padding: 40px; }
        
        .ticket {
            background: white; width: 700px; margin: 50px auto;
            border-radius: 15px; display: flex; overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            border-left: 10px solid var(--qatar);
        }
        .main-ticket { padding: 30px; flex: 2; position: relative; }
        .stub { 
            padding: 30px; flex: 1; background: var(--qatar); 
            color: white; text-align: center; border-left: 2px dashed #ddd;
        }
        .flight-info { display: flex; justify-content: space-between; margin: 20px 0; }
        .city-code { font-size: 32px; font-weight: bold; color: var(--qatar); margin: 0; }
        .label { font-size: 11px; color: #999; text-transform: uppercase; font-weight: bold; }
        .data { font-size: 16px; font-weight: 600; margin-bottom: 15px; }
        .barcode { height: 50px; width: 100%; background: #333; margin-top: 20px; opacity: 0.1; }
    </style>
</head>
<body>

<h2 style="text-align:center; color: var(--qatar);">Welcome back, <?php echo $_SESSION['fullname']; ?></h2>

<?php if($booking): ?>
    <div class="ticket">
        <div class="main-ticket">
            <div class="label">Passenger</div>
            <div class="data"><?php echo $_SESSION['fullname']; ?></div>

            <div class="flight-info">
                <div>
                    <p class="city-code"><?php echo substr($booking['origin'], 0, 3); ?></p>
                    <p class="label"><?php echo $booking['origin']; ?></p>
                </div>
                <div style="font-size: 24px; padding-top: 10px;">✈</div>
                <div>
                    <p class="city-code"><?php echo substr($booking['destination'], 0, 3); ?></p>
                    <p class="label"><?php echo $booking['destination']; ?></p>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:10px;">
                <div><p class="label">Flight</p><p class="data"><?php echo $booking['flight_no']; ?></p></div>
                <div><p class="label">Gate</p><p class="data"><?php echo $booking['gate'] ?? 'B12'; ?></p></div>
                <div><p class="label">Boarding</p><p class="data"><?php echo $booking['dep_time'] ?? '14:30'; ?></p></div>
            </div>
            <div class="barcode"></div>
        </div>

        <div class="stub">
            <h3 style="margin-top:0;">BOARDING PASS</h3>
            <p class="label" style="color:#ffcdd2">Seat Number</p>
            <h1 style="font-size: 45px; margin: 10px 0;"><?php echo $booking['seat_number']; ?></h1>
            <p class="label" style="color:#ffcdd2">Class</p>
            <p><?php echo strtoupper($booking['class'] ?? 'Economy'); ?></p>
            <hr style="opacity:0.3">
            <button onclick="window.print()" style="background:white; color:var(--qatar); border:none; padding:10px; border-radius:5px; cursor:pointer; font-weight:bold;">Print Ticket</button>
        </div>
    </div>
<?php else: ?>
    <div style="text-align:center; padding: 50px;">
        <p>You haven't booked any flights yet.</p>
        <a href="index.php" style="color: var(--qatar); font-weight:bold;">Search for flights now</a>
    </div>
<?php endif; ?>

</body>
</html>