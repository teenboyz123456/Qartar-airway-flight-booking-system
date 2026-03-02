<?php
session_start();
include('dbconnect.php');

// 1. SECURITY & ROLE CHECK
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php"); 
    exit();
}

// 2. DELETE FLIGHT LOGIC
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    // Clean up bookings first to avoid SQL errors
    mysqli_query($conn, "DELETE FROM bookings WHERE flight_id = '$id'");
    mysqli_query($conn, "DELETE FROM flights WHERE id = '$id'");
    header("Location: dashboard.php");
    exit();
}

// 3. ADD FLIGHT LOGIC
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_flight'])) {
    $fno = mysqli_real_escape_string($conn, $_POST['fno']);
    $org = mysqli_real_escape_string($conn, $_POST['org']);
    $dest = mysqli_real_escape_string($conn, $_POST['dest']);
    $p_vip = (float)$_POST['p_vip'];
    $p_biz = (float)$_POST['p_biz'];
    $p_eco = (float)$_POST['p_eco'];

    $sql = "INSERT INTO flights (flight_no, origin, destination, price_vip, price_business, price_economy) 
            VALUES ('$fno', '$org', '$dest', '$p_vip', '$p_biz', '$p_eco')";
    mysqli_query($conn, $sql);
    header("Location: dashboard.php");
    exit();
}

// 4. STATS FETCH (FIXED REVENUE QUERY)
$total_flights = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM flights"))['c'] ?? 0;
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE role='customer'"))['c'] ?? 0;

// FIX: Changed 'price' to 'amount' to match your booking system
$rev_res = mysqli_query($conn, "SELECT SUM(amount) as t FROM bookings");
$rev_row = mysqli_fetch_assoc($rev_res);
$total_rev = $rev_row['t'] ?? 0;

// 5. SEAT MAP LOGIC
$booked_seats = [];
$flight_details = ['type' => 'Airbus A350-900', 'route' => 'Route Not Found'];

if(isset($_GET['view_map'])) {
    $fid = mysqli_real_escape_string($conn, $_GET['view_map']);
    $f_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT origin, destination FROM flights WHERE id = '$fid'"));
    if($f_info) { $flight_details['route'] = $f_info['origin'] . " ➔ " . $f_info['destination']; }

    // FIX: Selecting 'amount' instead of 'price'
    $res = mysqli_query($conn, "SELECT b.seat_number, u.fullname, b.amount FROM bookings b JOIN users u ON b.user_id = u.id WHERE b.flight_id = '$fid'");
    if($res) { while($row = mysqli_fetch_assoc($res)) { $booked_seats[$row['seat_number']] = $row; } }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Qatar Admin | Flight Ops</title>
    <style>
        :root { --qatar: #700029; --gold: #c4a163; --bg: #f4f7f9; --card: #fff; --text: #333; }
        .dark-theme { --bg: #121212; --card: #1e1e1e; --text: #f1f1f1; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); margin: 0; display: flex; height: 100vh; overflow: hidden; }
        .sidebar { width: 260px; background: var(--qatar); color: white; display: flex; flex-direction: column; }
        .nav-link { padding: 20px; color: rgba(255,255,255,0.7); text-decoration: none; cursor: pointer; border-left: 4px solid transparent; }
        .nav-link.active { background: rgba(255,255,255,0.1); color: white; border-left-color: var(--gold); }
        .main-content { flex: 1; padding: 40px; overflow-y: auto; }
        .section { display: none; }
        .section.active { display: block; }
        .card { background: var(--card); padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; }
        .btn-qatar { background: var(--qatar); color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; text-decoration: none; font-weight: bold; }
        .btn-del { color: #ff4757; text-decoration: none; font-weight: bold; margin-left: 15px; }
        
        /* MODALS */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); display: flex; justify-content: center; align-items: flex-start; z-index: 3000; overflow-y: auto; padding: 40px 0; }
        .fuselage { background: white; border-radius: 180px 180px 50px 50px; padding: 50px; border: 2px solid #ddd; width: 650px; position: relative; color: #333; }
        .form-card { background: var(--card); color: var(--text); padding: 30px; border-radius: 15px; width: 400px; }
        input { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; background: transparent; color: inherit; }

        /* SEAT STYLES */
        .cabin-header { text-align: center; font-size: 11px; font-weight: bold; letter-spacing: 3px; color: #999; margin: 30px 0 10px; border-bottom: 1px solid #eee; }
        .grid-vip-biz { display: grid; grid-template-columns: 1fr 40px 1fr 1fr 40px 1fr; gap: 12px; }
        .grid-economy { display: grid; grid-template-columns: repeat(3, 1fr) 30px repeat(3, 1fr) 30px repeat(3, 1fr); gap: 10px; }
        .seat { height: 35px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 10px; border: 1px solid #ccc; font-weight: bold; background: #eee; position: relative; }
        .seat.is-booked { background: var(--qatar) !important; color: white !important; border-color: var(--gold); }
        .tooltip { display: none; position: absolute; bottom: 110%; background: #333; color: white; padding: 10px; border-radius: 5px; width: 160px; z-index: 100; font-size: 11px; }
        .seat.is-booked:hover .tooltip { display: block; }
    </style>
</head>
<body>

<div class="sidebar">
    <div style="padding:30px; font-weight:bold; font-size:20px;">QATAR ADMIN</div>
    <div class="nav-link active" onclick="showSection('flights', this)">Flight Operations</div>
    <div class="nav-link" onclick="showSection('settings', this)">Settings</div>
</div>

<div class="main-content">
    <div id="flights" class="section active">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h1>Flight Management</h1>
            <button class="btn-qatar" onclick="toggleModal('addModal', true)">+ Add Flight</button>
        </div>
        
        <div class="stats-grid">
            <div class="card"><small>FLIGHTS</small><h2><?php echo $total_flights; ?></h2></div>
            <div class="card"><small>PASSENGERS</small><h2><?php echo $total_users; ?></h2></div>
            <div class="card"><small>REVENUE</small><h2 style="color:#2ecc71;">$<?php echo number_format($total_rev, 2); ?></h2></div>
        </div>

        <div class="card">
            <table>
                <thead><tr><th>Flight No</th><th>Route</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php 
                    $list = mysqli_query($conn, "SELECT * FROM flights ORDER BY id DESC");
                    while($f = mysqli_fetch_assoc($list)): ?>
                    <tr>
                        <td><strong><?php echo $f['flight_no']; ?></strong></td>
                        <td><?php echo $f['origin']; ?> ➔ <?php echo $f['destination']; ?></td>
                        <td>
                            <a href="dashboard.php?view_map=<?php echo $f['id']; ?>" class="btn-qatar">View Map</a>
                            <a href="dashboard.php?delete_id=<?php echo $f['id']; ?>" class="btn-del" onclick="return confirm('Delete Flight?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="settings" class="section">
        <h1>Settings</h1>
        <div class="card" style="max-width:400px;">
            <button class="btn-qatar" onclick="toggleDark()" style="width:100%;">Toggle Dark Mode</button>
            <hr style="margin:20px 0; border:0; border-top:1px solid #eee;">
            <a href="logout.php" style="color:red; text-decoration:none;">Logout System</a>
        </div>
    </div>
</div>

<div class="modal-overlay" id="addModal" style="display:none;">
    <div class="form-card">
        <h2 style="color:var(--qatar); margin-top:0;">New Flight</h2>
        <form method="POST">
            <input type="text" name="fno" placeholder="Flight No (QR-123)" required>
            <input type="text" name="org" placeholder="Origin" required>
            <input type="text" name="dest" placeholder="Destination" required>
            <input type="number" name="p_vip" placeholder="VIP Price" required>
            <input type="number" name="p_biz" placeholder="Business Price" required>
            <input type="number" name="p_eco" placeholder="Economy Price" required>
            <div style="margin-top:15px; display:flex; gap:10px;">
                <button type="button" onclick="toggleModal('addModal', false)" class="btn-qatar" style="background:#888;">Cancel</button>
                <button type="submit" name="save_flight" class="btn-qatar">Save to DB</button>
            </div>
        </form>
    </div>
</div>

<?php if(isset($_GET['view_map'])): ?>
<div class="modal-overlay">
    <div class="fuselage">
        <a href="dashboard.php" style="position:absolute; top:20px; right:25px; font-size:30px; text-decoration:none; color:#bbb;">&times;</a>
        <div style="text-align:center; border-bottom: 2px solid var(--qatar); padding-bottom:15px; margin-bottom:20px;">
            <h1 style="color: var(--qatar); margin:0;"><?php echo $flight_details['type']; ?></h1>
            <p style="color:#666; font-weight:bold; margin:5px 0 0 0;"><?php echo $flight_details['route']; ?></p>
        </div>

        <div class="cabin-header">FIRST CLASS (VIP)</div>
        <div class="grid-vip-biz">
            <?php 
            $vip_seats = ['A','E','F','K'];
            for($r=1; $r<=2; $r++){
                foreach($vip_seats as $idx => $l){
                    $sid = $r.$l; $booked = isset($booked_seats[$sid]); $class = $booked ? "is-booked" : "";
                    echo "<div class='seat $class'>$sid";
                    // TOOLTIP FIXED: Showing amount paid by the specific user
                    if($booked) echo "<div class='tooltip'><strong>".$booked_seats[$sid]['fullname']."</strong><br>Paid: $".number_format($booked_seats[$sid]['amount'], 2)."</div>";
                    echo "</div>"; if($idx == 0 || $idx == 2) echo "<div></div>";
                }
            }
            ?>
        </div>

        <div class="cabin-header">BUSINESS CLASS</div>
        <div class="grid-vip-biz">
            <?php 
            for($r=5; $r<=7; $r++){
                foreach($vip_seats as $idx => $l){
                    $sid = $r.$l; $booked = isset($booked_seats[$sid]); $class = $booked ? "is-booked" : "";
                    echo "<div class='seat $class'>$sid";
                    if($booked) echo "<div class='tooltip'><strong>".$booked_seats[$sid]['fullname']."</strong><br>Paid: $".number_format($booked_seats[$sid]['amount'], 2)."</div>";
                    echo "</div>"; if($idx == 0 || $idx == 2) echo "<div></div>";
                }
            }
            ?>
        </div>

        <div class="cabin-header">ECONOMY CLASS</div>
        <div class="grid-economy">
            <?php 
            $eco_ls = ['A','B','C','D','E','F','G','H','J'];
            for($r=10; $r<=22; $r++){
                foreach($eco_ls as $idx => $l){
                    $sid = $r.$l; $booked = isset($booked_seats[$sid]); $class = $booked ? "is-booked" : "";
                    echo "<div class='seat $class'>$sid";
                    if($booked) echo "<div class='tooltip'><strong>".$booked_seats[$sid]['fullname']."</strong><br>Paid: $".number_format($booked_seats[$sid]['amount'], 2)."</div>";
                    echo "</div>"; if($idx == 2 || $idx == 5) echo "<div></div>";
                }
            }
            ?>
        </div>
    </div>
</div>
<?php endif; ?>



<script>
    function showSection(id, el) {
        document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
        document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
        document.getElementById(id).classList.add('active');
        el.classList.add('active');
    }
    function toggleModal(id, show) { document.getElementById(id).style.display = show ? 'flex' : 'none'; }
    function toggleDark() {
        document.body.classList.toggle('dark-theme');
        localStorage.setItem('theme', document.body.classList.contains('dark-theme') ? 'dark' : 'light');
    }
    if(localStorage.getItem('theme') === 'dark') document.body.classList.add('dark-theme');
</script>
</body>
</html>