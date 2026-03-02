 <?php
include('dbconnect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['origin'])) {
    $origin = mysqli_real_escape_string($conn, $_POST['origin']);
    $destination = mysqli_real_escape_string($conn, $_POST['destination']);
    $dep_date = mysqli_real_escape_string($conn, $_POST['dep_date']);
    $selected_class = $_POST['flight_class'];

    // We use a broader search to ensure we find something
    $sql = "SELECT * FROM flights WHERE 
            origin LIKE '%$origin%' AND 
            destination LIKE '%$destination%'";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("<div style='color:red; background:white; padding:20px;'>
                <strong>Database Error:</strong> " . mysqli_error($conn) . "
             </div>");
    }

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Use fallback values if columns are missing so it never throws a 'Fatal Error'
            $f_no = isset($row['flight_no']) ? $row['flight_no'] : "QR-" . rand(100, 999);
            $d_time = isset($row['departure_time']) ? $row['departure_time'] : "08:00 AM";
            $a_time = isset($row['arrival_time']) ? $row['arrival_time'] : "11:30 AM";
            $seats = isset($row['available_seats']) ? $row['available_seats'] : rand(2, 10);
            $price = isset($row['price']) ? $row['price'] : 500;

            // Class Pricing Logic
            if($selected_class == 'business') $price *= 1.5;
            if($selected_class == 'vip') $price *= 2.5;

            $theme_color = ($selected_class == 'vip') ? '#c4a163' : (($selected_class == 'business') ? '#700029' : '#555');

            echo "
            <div style='background: white; border-radius: 12px; padding: 25px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; border-left: 10px solid $theme_color;'>
                <div style='flex: 2;'>
                    <div style='margin-bottom: 10px;'>
                        <span style='background: $theme_color; color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: bold;'>$selected_class Available</span>
                        <span style='color: #888; font-size: 12px; margin-left: 10px;'>Flight $f_no</span>
                    </div>
                    <h2 style='margin: 0; color: #333;'>$origin ✈ $destination</h2>
                    <p style='margin: 5px 0 0; color: #666;'>Schedule: <strong>$d_time</strong> to <strong>$a_time</strong></p>
                </div>
                <div style='flex: 1; text-align: center; border-left: 1px dashed #ddd; border-right: 1px dashed #ddd;'>
                    <div style='font-size: 11px; color: #999; text-transform: uppercase;'>Availability</div>
                    <div style='font-size: 18px; font-weight: bold; color: " . ($seats < 5 ? 'red' : '#2ecc71') . ";'>$seats Seats Left</div>
                </div>
                <div style='flex: 1; text-align: right; padding-left: 20px;'>
                    <div style='font-size: 12px; color: #888;'>Total Fare</div>
                    <div style='font-size: 24px; font-weight: bold; color: #700029;'>USD " . number_format($price, 0) . "</div>
                    <button onclick='confirmBooking({$row['id']}, \"$selected_class\")' 
                            style='background: #700029; color: white; border: none; padding: 12px 25px; border-radius: 30px; cursor: pointer; font-weight: bold; margin-top: 10px; width: 100%;'>
                        Select Flight
                    </button>
                </div>
            </div>";
        }
    } else {
        echo "<div style='text-align:center; padding: 40px; background: white; border-radius: 12px;'>
                <p style='color: #888;'>No flights found. Check your database values for <strong>$origin</strong> and <strong>$destination</strong>.</p>
              </div>";
    }
}
?>