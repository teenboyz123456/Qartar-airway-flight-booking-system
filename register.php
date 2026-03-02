<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Join Qatar Airways | Privilege Club</title>
    <style>
        :root { 
            --qatar: #700029; 
            --gold: #8a704c;
        }
        
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: #f4f4f4; 
            margin: 0; 
            display: flex; 
            height: 100vh; 
            overflow: hidden;
        }

        /* LEFT SIDE: FORM */
        .left-panel {
            width: 40%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: white;
            z-index: 2;
        }

        .card { 
            width: 380px; 
            padding: 20px;
        }

        h2 { color: var(--qatar); margin-bottom: 10px; font-size: 28px; }
        p.subtitle { color: #666; margin-bottom: 30px; font-size: 14px; }

        .field { margin-bottom: 20px; }
        label { display: block; font-size: 11px; font-weight: bold; color: #888; margin-bottom: 8px; letter-spacing: 1px; }
        
        input { 
            width: 100%; padding: 14px; border: 1px solid #ddd; border-radius: 6px; 
            box-sizing: border-box; background: #fafafa; transition: 0.3s;
        }
        input:focus { border-color: var(--qatar); outline: none; background: white; }

        .btn { 
            width: 100%; background: var(--qatar); color: white; border: none; 
            padding: 16px; cursor: pointer; font-weight: bold; border-radius: 6px;
            font-size: 16px; transition: 0.3s;
        }
        .btn:hover { background: #5a0021; transform: translateY(-2px); }

        .link { text-align: center; margin-top: 25px; font-size: 14px; color: #666; }
        a { color: var(--qatar); text-decoration: none; font-weight: bold; }

        /* RIGHT SIDE: SLIDESHOW */
        .right-panel {
            width: 60%;
            position: relative;
            background: #222;
            color: white;
        }

        .slideshow-container { width: 100%; height: 100%; position: relative; }
        
        .mySlides {
            display: none;
            height: 100%;
            width: 100%;
            background-size: cover;
            background-position: center;
            animation: fadeEffect 1.5s;
        }

        /* CONTENT OVERLAY ON SLIDER */
        .slide-content {
            position: absolute;
            bottom: 100px;
            left: 50px;
            background: rgba(112, 0, 41, 0.85);
            padding: 30px;
            max-width: 400px;
            border-left: 5px solid var(--gold);
        }

        @keyframes fadeEffect {
            from {opacity: 0.4;} 
            to {opacity: 1;}
        }
    </style>
</head>
<body>

    <div class="left-panel">
        <div class="card">
            <h2>Join the Journey</h2>
            <p class="subtitle">Enroll in Privilege Club to experience a world of exclusive rewards and luxury travel.</p>
            
            <form action="signup_logic.php" method="POST">
                <div class="field">
                    <label>FULL NAME</label>
                    <input type="text" name="fullname" placeholder="Isack thomas" required>
                </div>
                <div class="field">
                    <label>EMAIL ADDRESS</label>
                    <input type="email" name="email" placeholder="isack@example.com" required>
                </div>
                <div class="field">
                    <label>CREATE PASSWORD</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn">Sign up</button>
            </form>
            
            <div class="link">Already a member? <a href="login.php">Login to Account</a></div>
        </div>
    </div>

    <div class="right-panel">
        <div class="slideshow-container">
            <div class="mySlides" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('privilage club.jpg');">
                <div class="slide-content">
                    <h3 style="color:var(--gold); margin:0;">PRIVILEGE CLUB</h3>
                    <h1 style="margin:10px 0;">Earn Avios on Every Flight</h1>
                    <p>Unlock lounge access, extra baggage, and award flights across the globe.</p>
                </div>
            </div>

            <div class="mySlides" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('doha image.jpg');">
                <div class="slide-content">
                    <h3 style="color:var(--gold); margin:0;">DESTINATIONS</h3>
                    <h1 style="margin:10px 0;">Fly to Over 170 Destinations</h1>
                    <p>Experience the World's Best Airline from Doha to London, New York, and beyond.</p>
                </div>
            </div>

            <div class="mySlides" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('bussiness class.avif');">
                <div class="slide-content">
                    <h3 style="color:var(--gold); margin:0;">Q-SUITE</h3>
                    <h1 style="margin:10px 0;">First in Business</h1>
                    <p>The first business class seat with a double bed and privacy doors.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Automatic Slideshow logic
        let slideIndex = 0;
        showSlides();

        function showSlides() {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";  
            }
            slideIndex++;
            if (slideIndex > slides.length) {slideIndex = 1}    
            slides[slideIndex-1].style.display = "block";  
            setTimeout(showSlides, 5000); // Change image every 5 seconds
        }
    </script>
</body>
</html>