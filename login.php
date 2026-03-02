<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Qatar Airways</title>
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
            width: 100vw;
            overflow: hidden;
        }

        /* LEFT SIDE: FORM AREA */
        .left-panel {
            width: 40%;
            min-width: 400px;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            box-shadow: 10px 0 30px rgba(0,0,0,0.1);
        }

        .login-box {
            width: 80%;
            max-width: 350px;
        }

        h2 { color: var(--qatar); font-size: 32px; margin-bottom: 10px; }
        p.desc { color: #666; font-size: 14px; margin-bottom: 30px; }

        label { display: block; font-size: 11px; font-weight: bold; color: #888; margin-bottom: 5px; letter-spacing: 1px; }
        
        input { 
            width: 100%; padding: 14px; border: 1px solid #ddd; border-radius: 4px; 
            box-sizing: border-box; background: #fafafa; margin-bottom: 20px; transition: 0.3s;
        }
        
        input:focus { border-color: var(--qatar); outline: none; background: white; }

        .btn { 
            width: 100%; background: var(--qatar); color: white; border: none; 
            padding: 16px; cursor: pointer; font-weight: bold; border-radius: 4px;
            font-size: 16px; transition: 0.3s;
        }
        
        .btn:hover { background: #5a0021; }

        .link { text-align: center; margin-top: 20px; font-size: 14px; }
        a { color: var(--qatar); text-decoration: none; font-weight: bold; }

        /* RIGHT SIDE: SLIDESHOW */
        .right-panel {
            flex: 1;
            position: relative;
            overflow: hidden;
        }

        .slides {
            display: none;
            height: 100%;
            width: 100%;
            background-size: cover;
            background-position: center;
            animation: zoomFade 5s infinite;
        }

        .overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(0,0,0,0.3), transparent);
        }

        .text-overlay {
            position: absolute;
            bottom: 80px;
            left: 50px;
            color: white;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
        }

        .text-overlay h1 { font-size: 60px; margin: 0; text-transform: uppercase; }
        .text-overlay p { font-size: 20px; color: var(--gold); font-weight: bold; }

        @keyframes zoomFade {
            0% { opacity: 0; transform: scale(1.1); }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { opacity: 0; transform: scale(1); }
        }
    </style>
</head>
<body>

    <div class="left-panel">
        <div class="login-box">
            <h2>Welcome Back</h2>
            <p class="desc">Login to your account to explore global destinations.</p>
            
            <form action="login_logic.php" method="POST">
                <label>EMAIL ADDRESS</label>
                <input type="email" name="email" placeholder="john@example.com" required>
                
                <label>PASSWORD</label>
                <input type="password" name="password" placeholder="••••••••" required>
                
                <button type="submit" class="btn">LOGIN</button>
            </form>
            
            <div class="link">New to Qatar Airways? <a href="register.php">Join Now</a></div>
        </div>
    </div>

    <div class="right-panel">
        
        <div class="slides" style="background-image: url('lodon.jpg');">
            <div class="overlay"></div>
            <div class="text-overlay">
                <p>FLY TO EUROPE</p>
                <h1>London</h1>
            </div>
        </div>

        <div class="slides" style="background-image: url('Tokyo.avif');">
            <div class="overlay"></div>
            <div class="text-overlay">
                <p>DISCOVER ASIA</p>
                <h1>Tokyo</h1>
            </div>
        </div>

        <div class="slides" style="background-image: url('Newyork image.webp');">
            <div class="overlay"></div>
            <div class="text-overlay">
                <p>VISIT THE STATES</p>
                <h1>New York</h1>
            </div>
        </div>

    </div>

    <script>
        let slideIndex = 0;
        carousel();

        function carousel() {
            let i;
            let x = document.getElementsByClassName("slides");
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";  
            }
            slideIndex++;
            if (slideIndex > x.length) {slideIndex = 1}    
            x[slideIndex-1].style.display = "block";  
            setTimeout(carousel, 5000); // Change image every 5 seconds
        }
    </script>
</body>
</html>