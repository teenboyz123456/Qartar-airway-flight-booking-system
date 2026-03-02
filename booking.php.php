<?php
include('dbconnect.php'); 
session_start();

$current_origin = "Dar Es Salaam DAR"; 
$user_display_name = "Login"; 

if(isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    if ($conn) {
        $user_query = mysqli_query($conn, "SELECT fullname FROM users WHERE id = '$uid'");
        if($user_query && mysqli_num_rows($user_query) > 0){
            $user_data = mysqli_fetch_assoc($user_query);
            $user_display_name = $user_data['fullname'];
        }

        $query = mysqli_query($conn, "SELECT origin, destination FROM bookings WHERE user_id = '$uid' ORDER BY id DESC LIMIT 1");
        if($query && mysqli_num_rows($query) > 0) {
            $row = mysqli_fetch_assoc($query);
            $current_origin = $row['origin'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Qatar Airways | Going Places Together</title>
    <style>
        :root { --qatar: #700029; --gold: #c4a163; --dark-red: #4d001c; }
        html { scroll-behavior: smooth; }
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #f4f4f4; color: #333; }
        
        .navbar { 
            background: white; padding: 15px 5%; display: flex; 
            justify-content: space-between; align-items: center; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 1000;
        }
        .nav-brand { font-size: 24px; font-weight: bold; color: var(--qatar); text-decoration: none; letter-spacing: 1px; }
        .nav-links { display: flex; gap: 30px; align-items: center; }
        .nav-links a { text-decoration: none; color: #333; font-size: 14px; font-weight: 600; text-transform: uppercase; }
        .nav-links a:hover { color: var(--qatar); }
        
        .profile-link { 
            background: #f8f8f8; padding: 10px 20px; border-radius: 25px; 
            border: 1px solid #ddd; color: var(--qatar) !important; font-weight: bold !important;
            display: flex; align-items: center; gap: 8px;
        }

        .hero-header { 
            background: linear-gradient(rgba(77, 0, 28, 0.9), rgba(77, 0, 28, 0.9)), 
                        url('https://images.unsplash.com/photo-1436491865332-7a61a109c0f2?auto=format&fit=crop&q=80&w=1600');
            background-size: cover; background-position: center;
            color: white; padding: 120px 5% 180px; text-align: center; 
        }
        .hero-header h1 { font-size: 55px; margin: 0; font-weight: 300; letter-spacing: 2px; }
        .motto { font-size: 18px; color: var(--gold); letter-spacing: 5px; text-transform: uppercase; margin-top: 10px; font-weight: bold; }
        
        .search-widget { 
            background: white; width: 92%; max-width: 1200px; 
            margin: -100px auto 40px; border-radius: 12px; padding: 40px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.2); 
        }
        .tabs { display: flex; gap: 30px; border-bottom: 1px solid #eee; margin-bottom: 25px; padding-bottom: 15px; }
        .tab-item { color: var(--qatar); font-weight: bold; font-size: 16px; border-bottom: 3px solid var(--qatar); padding-bottom: 12px; }
        
        .input-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; }
        .input-box { border: 1px solid #ddd; padding: 15px; border-radius: 6px; }
        .input-box label { display: block; font-size: 11px; color: #888; margin-bottom: 5px; font-weight: bold; text-transform: uppercase; }
        .input-box input, .input-box select { border: none; width: 100%; font-size: 16px; outline: none; background: transparent; }

        .search-btn { 
            background: var(--qatar); color: white; border: none; padding: 18px 50px; 
            border-radius: 35px; cursor: pointer; float: right; margin-top: 25px; 
            font-weight: bold; font-size: 16px; transition: 0.3s; 
        }

        #search-results {
            display: none; 
            margin-top: 40px; 
            padding-top: 20px; 
            border-top: 1px solid #eee;
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .section-title { padding: 60px 5% 20px; font-size: 32px; font-weight: 300; color: #333; text-align: center; }
        .destination-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; padding: 20px 5%; }
        .dest-card { height: 450px; border-radius: 15px; position: relative; overflow: hidden; color: white; background-size: cover; transition: 0.4s; }
        .dest-card-overlay { position: absolute; bottom: 0; width: 100%; padding: 30px; background: linear-gradient(transparent, rgba(0,0,0,0.85)); box-sizing: border-box; }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="homepage .php" class="nav-brand">QATAR <span style="font-weight:300">AIRWAYS</span></a>
        <div class="nav-links">
            <a href="#booking-section">Book</a>
            <a href="#destinations">Destinations</a>
            <a href="#privilege-section">Privilege Club</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="profile.php" class="profile-link">
                    <span style="font-size:18px">👤</span> <?php echo htmlspecialchars($user_display_name); ?>
                </a>
                <a href="logout.php" style="font-size: 11px; color: #999;">Logout</a>
            <?php else: ?>
                <a href="login.php" class="profile-link">Login / Signup</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="hero-header">
        <h1>Where to next?</h1>
        <div class="motto">Going Places Together</div>
        <p style="text-align:center; opacity:0.8;">Departure: <strong><?php echo $current_origin; ?></strong></p>
    </div>

    <div id="booking-section" class="search-widget">
        <div class="tabs">
            <span class="tab-item">✈ Book a flight</span>
        </div>
        
        <form id="searchForm">
            <div class="input-row">
                <div class="input-box">
                    <label>From</label>
                    <input type="text" id="origin" name="origin" value="<?php echo $current_origin; ?>" required>
                </div>
                <div class="input-box">
                    <label>To</label>
                    <input type="text" id="destination" name="destination" placeholder="Where to?" required>
                </div>
                <div class="input-box">
                    <label>Departure</label>
                    <input type="date" id="dep_date" name="dep_date" required>
                </div>
                <div class="input-box">
                    <label>Class</label>
                    <select id="flight_class" name="flight_class">
                        <option value="economy">Economy</option>
                        <option value="business">Business</option>
                        <option value="vip">First Class</option>
                    </select>
                </div>
            </div>
            <button type="button" onclick="performSearch()" class="search-btn">Search Flights</button>
        </form>

        <div id="search-results"></div>
    </div>

    <h2 id="destinations" class="section-title">Explore Our Popular Destinations</h2>
    <div class="destination-grid">
        <div class="dest-card" style="background-image: url('austrilia.avif')">
            <div class="dest-card-overlay"><h3>Adelaide, Australia</h3></div>
        </div>
        <div class="dest-card" style="background-image: url('newzealand.jpg')">
            <div class="dest-card-overlay"><h3>Auckland, New Zealand</h3></div>
        </div>
        <div class="dest-card" style="background-image: url('kazkstan.jpg')">
            <div class="dest-card-overlay"><h3>Almaty, Kazakhstan</h3></div>
        </div>
    </div>

    <footer style="background:#333; color:#aaa; text-align:center; padding:40px; margin-top:50px;">
        <p>&copy; 2026 Qatar Airways. All rights reserved.</p>
    </footer>

    <script>
        function performSearch() {
            const resultsDiv = document.getElementById('search-results');
            const origin = document.getElementById('origin').value;
            const destination = document.getElementById('destination').value;
            const dep_date = document.getElementById('dep_date').value;
            const flight_class = document.getElementById('flight_class').value;

            if(!destination || !dep_date) {
                alert("Please select a destination and date.");
                return;
            }

            resultsDiv.style.display = "block";
            resultsDiv.innerHTML = "<p style='text-align:center;'>Searching our database...</p>";

            fetch('fetch_flights.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `origin=${origin}&destination=${destination}&dep_date=${dep_date}&flight_class=${flight_class}`
            })
            .then(response => response.text())
            .then(data => {
                resultsDiv.innerHTML = data;
                resultsDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            })
            .catch(error => {
                resultsDiv.innerHTML = "<p style='color:red;'>Error connecting to database.</p>";
            });
        }

        /**
         * UPDATED: Now redirects specifically to your departure seat selection page.
         */
        function confirmBooking(flightId, flightClass) {
            const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
            
            if (!isLoggedIn) {
                alert("Please login to your account to book a flight.");
                window.location.href = "login.php";
                return;
            }

            // REDIRECT to select_departure.php
            window.location.href = `select_departure.php?fid=${flightId}&class=${flightClass}`;
        }
    </script>
</body>
</html>