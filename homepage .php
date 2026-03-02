<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Qatar Airways</title>
    <style>
        /* CSS VARIABLES FOR EASY UPDATES */
        :root {
            --qatar-burgundy: #700029;
            --qatar-gold: #c4a163;
            --white: #ffffff;
            --bg-light: #f4f4f4;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Arial', sans-serif; line-height: 1.6; background-color: var(--bg-light); color: #333; }

        /* --- Custom Navbar --- */
        .navbar {
            position: fixed;
            top: 0; width: 100%;
            padding: 15px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--white);
            z-index: 1000;
            border-bottom: 2px solid var(--qatar-burgundy);
        }
        .logo { color: var(--qatar-burgundy); font-size: 1.8rem; font-weight: bold; text-decoration: none; }
        .logo span { font-weight: 100; color: #555; }
        
        .nav-links { display: flex; list-style: none; }
        .nav-links li { margin-left: 30px; }
        .nav-links a { text-decoration: none; color: #333; font-weight: 600; transition: 0.3s; }
        .nav-links a:hover { color: var(--qatar-burgundy); }

        /* --- Welcome Hero Section --- */
        .welcome-hero {
            height: 100vh;
            background-color: var(--qatar-burgundy);
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--white);
            text-align: center;
            flex-direction: column;
        }
        .welcome-content { animation: fadeIn 2s ease-in; }
        .logo-large { font-size: 4rem; letter-spacing: 8px; font-weight: bold; margin-bottom: 15px; }
        .tagline { font-size: 1.3rem; margin-bottom: 40px; letter-spacing: 3px; font-weight: 300; opacity: 0.9; }
        
        .enter-btn {
            padding: 18px 50px;
            background: transparent;
            border: 2px solid var(--white);
            color: var(--white);
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: 0.4s;
        }
        .enter-btn:hover { background: var(--white); color: var(--qatar-burgundy); }

        /* --- About Section --- */
        .about { padding: 100px 15%; text-align: center; background: var(--white); }
        .about h2 { color: var(--qatar-burgundy); font-size: 2.8rem; margin-bottom: 25px; }
        .about p { color: #666; font-size: 1.2rem; max-width: 900px; margin: 0 auto; line-height: 1.8; }

        /* --- Destinations Section --- */
        .destinations { padding: 80px 8%; background: var(--bg-light); }
        .destinations h2 { text-align: center; color: var(--qatar-burgundy); margin-bottom: 50px; font-size: 2.5rem; }
        
        .dest-grid { display: flex; justify-content: space-around; flex-wrap: wrap; gap: 20px; }
        .dest-card { background: var(--white); width: 30%; min-width: 300px; border-radius: 5px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .dest-img { height: 200px; background-color: #ccc; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #555; }
        .dest-card-content { padding: 25px; }
        .dest-card-content h3 { color: var(--qatar-burgundy); margin-bottom: 12px; }

        /* --- Footer --- */
        footer { background: #1a1a1a; color: var(--white); padding: 80px 8% 30px; }
        .footer-container { display: flex; justify-content: space-between; flex-wrap: wrap; margin-bottom: 40px; }
        .footer-col { flex: 1; min-width: 200px; margin-bottom: 20px; }
        .footer-col h4 { color: var(--qatar-gold); margin-bottom: 25px; font-size: 1.2rem; }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 12px; opacity: 0.8; font-size: 0.9rem; cursor: pointer; }
        .footer-bottom { border-top: 1px solid #333; padding-top: 30px; text-align: center; font-size: 0.8rem; opacity: 0.5; }

        /* --- Animations --- */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="#" class="logo">QATAR <span>AIRWAYS</span></a>
        <ul class="nav-links">
            <li><a href="#about">About</a></li>
            <li><a href="#destinations">Destinations</a></li>
            <li><a href="login.php" style="color: var(--qatar-burgundy);">Book Flight</a></li>
        </ul>
    </nav>

    <header class="welcome-hero">
        <div class="welcome-content">
            <div class="logo-large">QATAR</div>
            <p class="tagline">WORLD'S BEST AIRLINE 2026</p>
            <a href="#about" class="enter-btn">Discover More</a>
        </div>
    </header>

    <section id="about" class="about">
        <h2>Your Journey Begins Here</h2>
        <p>Experience the peak of luxury travel with our world-class cabin crew and state-of-the-art fleet. From the moment you book until you reach your destination, we ensure every detail is handled with precision and care.</p>
    </section>

    <section id="destinations" class="destinations">
        <h2>Popular Destinations</h2>
        <div class="dest-grid">
            <div class="dest-card">
                <div class="dest-img" style="background: url('dubai.jpg') center/cover;"></div>
                <div class="dest-card-content">
                    <h3>Dubai</h3>
                    <p>Explore the golden city of dreams and architectural wonders.</p>
                </div>
            </div>
            <div class="dest-card">
                <div class="dest-img" style="background: url('lodon.jpg') center/cover;"></div>
                <div class="dest-card-content">
                    <h3>London</h3>
                    <p>A perfect blend of royal history and modern urban life.</p>
                </div>
            </div>
            <div class="dest-card">
                <div class="dest-img" style="background: url('paris.avif') center/cover;"></div>
                <div class="dest-card-content">
                    <h3>Paris</h3>
                    <p>The global center of art, fashion, and culinary excellence.</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-container">
            <div class="footer-col">
                <h4>Qatar Airways</h4>
                <ul>
                    <li>About Us</li>
                    <li>Careers</li>
                    <li>Media Center</li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Quick Help</h4>
                <ul>
                    <li>Help Center</li>
                    <li>Baggage Policy</li>
                    <li>Flight Status</li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Legal</h4>
                <ul>
                    <li>Privacy Policy</li>
                    <li>Terms & Conditions</li>
                    <li>Cookie Policy</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2026 Qatar Airways  | Developed by Isack Thomas
        </div>
    </footer>

</body>
</html>