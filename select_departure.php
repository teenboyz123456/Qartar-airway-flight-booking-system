<?php
session_start();
include('dbconnect.php');

$flight_id = $_GET['fid'] ?? 1; 
$class_selected = $_GET['class'] ?? 'economy'; 
$traveller_name = $_SESSION['fullname'] ?? "Guest Traveller";

// 1. Fetch Flight Details
$flight_query = mysqli_query($conn, "SELECT * FROM flights WHERE id = '$flight_id'");
$flight_info = mysqli_fetch_assoc($flight_query);

// 2. Fetch Booked Seats AND the names of people who booked them
$booked_data = [];
$check_query = mysqli_query($conn, "SELECT b.seat_number, u.fullname FROM bookings b JOIN users u ON b.user_id = u.id WHERE b.flight_id = '$flight_id'");
while($row = mysqli_fetch_assoc($check_query)) {
    $booked_data[$row['seat_number']] = $row['fullname'];
}

// 3. Price Definitions for the Calculator
$prices = ['economy' => 850, 'business' => 2100, 'vip' => 5000];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seat Selection | Qatar Airways</title>
    <style>
        :root { --qatar: #700029; --vip: #c4a163; --biz: #00447c; --eco: #555; }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; margin: 0; }
        .navbar { background: white; padding: 15px 5%; display: flex; justify-content: space-between; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .content-wrapper { padding: 40px 20px; }
        .main-layout { display: grid; grid-template-columns: 1fr 380px; gap: 40px; max-width: 1250px; margin: 0 auto; }
        .fuselage { background: white; border-radius: 150px 150px 50px 50px; padding: 60px 50px; border: 1px solid #ddd; position: relative; }
        .cabin-header { text-align: center; font-size: 11px; font-weight: bold; letter-spacing: 3px; color: #999; margin: 30px 0 10px; border-bottom: 1px solid #eee; }

        /* SEAT GRIDS */
        .grid-vip-biz { display: grid; grid-template-columns: 1fr 40px 1fr 1fr 40px 1fr; gap: 12px; margin-bottom: 20px; }
        .grid-economy { display: grid; grid-template-columns: repeat(3, 1fr) 30px repeat(3, 1fr) 30px repeat(3, 1fr); gap: 8px; }

        .seat-wrapper input { display: none; }
        .seat { height: 35px; border-radius: 5px; display: flex; align-items: center; justify-content: center; font-size: 10px; cursor: pointer; border: 1px solid #ccc; font-weight: bold; color: white; transition: 0.2s; position: relative; }
        
        .vip-s { background: var(--vip); }
        .biz-s { background: var(--biz); }
        .eco-s { background: var(--eco); }
        
        /* TAKEN SEAT LOGIC (MIS DASHBOARD) */
        .seat.taken { background: #e0e0e0 !important; color: #aaa !important; cursor: not-allowed; border: 1px solid #ddd; }
        .seat.taken:hover::after {
            content: "Booked by: " attr(data-user);
            position: absolute; top: -30px; background: #333; color: white; padding: 5px; border-radius: 4px; font-size: 9px; white-space: nowrap; z-index: 10;
        }

        .seat-wrapper input:checked + .seat { background: #27ae60 !important; transform: scale(1.1); border: 2px solid #1e8449; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }

        .ticket-summary { background: white; padding: 30px; border-radius: 20px; position: sticky; top: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); border-top: 10px solid var(--qatar); }
        .flight-info-box { background: #fdf2f5; padding: 15px; border-radius: 10px; margin-bottom: 20px; }
        .info-label { font-size: 11px; color: #888; text-transform: uppercase; font-weight: bold; }
        .info-data { font-size: 16px; color: #333; font-weight: bold; margin-bottom: 10px; }
        .btn-pay { background: var(--qatar); color: white; width: 100%; padding: 18px; border: none; border-radius: 50px; font-size: 18px; font-weight: bold; cursor: pointer; margin-top: 20px; transition: 0.3s; }
        .btn-pay:hover { background: #50001d; transform: scale(1.02); }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="index.php" style="color:var(--qatar); text-decoration:none; font-weight:bold; font-size:22px;">QATAR AIRWAYS</a>
    <div class="user-info">Passenger: <strong><?php echo htmlspecialchars($traveller_name); ?></strong></div>
</nav>

<div class="content-wrapper">
    <form action="confirm_booking.php" method="POST" id="bookingForm">
        <input type="hidden" name="flight_id" value="<?php echo $flight_id; ?>">
        <input type="hidden" name="amount" id="final-price-input" value="0">

        <div class="main-layout">
            <div class="fuselage">
                <h2 style="text-align:center; color: var(--qatar);">Airbus A350-900</h2>
                
                <div class="cabin-header">FIRST CLASS (VIP)</div>
                <div class="grid-vip-biz">
                    <?php 
                    $vip_seats = ['A','E','F','K'];
                    for($r=1; $r<=2; $r++){
                        foreach($vip_seats as $idx => $l){
                            $id = $r.$l;
                            $is_taken = isset($booked_data[$id]);
                            $user = $is_taken ? $booked_data[$id] : "";
                            echo "<label class='seat-wrapper'>
                                    <input type='radio' name='seat' value='$id' required ".($is_taken ? 'disabled' : '')." onclick='updateCalculator(\"$id\", 5000)'>
                                    <div class='seat vip-s ".($is_taken ? 'taken' : '')."' data-user='$user'>$id</div>
                                  </label>";
                            if($idx == 0 || $idx == 2) echo "<div></div>";
                        }
                    }
                    ?>
                </div>

                <div class="cabin-header">BUSINESS CLASS</div>
                <div class="grid-vip-biz">
                    <?php 
                    for($r=5; $r<=8; $r++){
                        foreach($vip_seats as $idx => $l){
                            $id = $r.$l;
                            $is_taken = isset($booked_data[$id]);
                            $user = $is_taken ? $booked_data[$id] : "";
                            echo "<label class='seat-wrapper'>
                                    <input type='radio' name='seat' value='$id' ".($is_taken ? 'disabled' : '')." onclick='updateCalculator(\"$id\", 2100)'>
                                    <div class='seat biz-s ".($is_taken ? 'taken' : '')."' data-user='$user'>$id</div>
                                  </label>";
                            if($idx == 0 || $idx == 2) echo "<div></div>";
                        }
                    }
                    ?>
                </div>

                <div class="cabin-header">ECONOMY CLASS</div>
                <div class="grid-economy">
                    <?php 
                    $eco_ls = ['A','B','C','D','E','F','G','H','J'];
                    for($r=12; $r<=20; $r++){
                        foreach($eco_ls as $idx => $l){
                            $id = $r.$l;
                            $is_taken = isset($booked_data[$id]);
                            $user = $is_taken ? $booked_data[$id] : "";
                            echo "<label class='seat-wrapper'>
                                    <input type='radio' name='seat' value='$id' ".($is_taken ? 'disabled' : '')." onclick='updateCalculator(\"$id\", 850)'>
                                    <div class='seat eco-s ".($is_taken ? 'taken' : '')."' data-user='$user'>$id</div>
                                  </label>";
                            if($idx == 2 || $idx == 5) echo "<div></div>";
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="ticket-summary">
                <h3>Live Price Calculator</h3>
                <div class="flight-info-box">
                    <div class="info-label">Route</div>
                    <div class="info-data"><?php echo $flight_info['origin']; ?> ➔ <?php echo $flight_info['destination']; ?></div>
                </div>

                <div class="info-label">Selected Seat</div>
                <div class="info-data" id="seat-display" style="color:var(--qatar); font-size:24px;">--</div>

                <div class="info-label">Base Fare</div>
                <div class="info-data" id="base-fare-display">$0.00</div>

                <hr style="border:0; border-top:1px solid #eee; margin:20px 0;">

                <div class="info-label">Total Estimated Price</div>
                <div style="font-size: 36px; font-weight: bold; color: var(--qatar);">$<span id="total-display">0.00</span></div>

                <button type="submit" class="btn-pay">Confirm & Pay</button>
            </div>
        </div>
    </form>
</div>



<script>
    function updateCalculator(seatId, price) {
        // 1. Update the display text for the user
        document.getElementById('seat-display').innerText = seatId;
        document.getElementById('base-fare-display').innerText = "$" + price.toLocaleString();
        
        // 2. Update the big total display
        document.getElementById('total-display').innerText = price.toLocaleString();
        
        // 3. IMPORTANT: Update the hidden input that PHP reads
        document.getElementById('final-price-input').value = price;
    }

    // Optional: Add a simple check to make sure they picked a seat
    document.getElementById('bookingForm').onsubmit = function() {
        if(document.getElementById('final-price-input').value == "0") {
            alert("Goal Refused! Please select a seat before confirming.");
            return false;
        }
    };
</script>

</body>
</html>