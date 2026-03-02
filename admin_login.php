<?php
session_start();
include('dbconnect.php');

if(isset($_POST['login_admin'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' AND role = 'admin'");
    
    if(mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if(password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = 'admin';
            header("Location:dashboard.php");
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Access Denied: Not an Admin account.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Portal | Qatar Airways</title>
    <style>
        :root { --qatar-maroon: #700029; }
        
        body, html { margin: 0; padding: 0; height: 100%; font-family: 'Segoe UI', sans-serif; overflow: hidden; }

        .container { display: flex; height: 100vh; width: 100%; }

        /* LEFT SIDE - CLEAN WHITE AREA */
        .left-panel {
            flex: 1;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
        }

        /* RIGHT SIDE - BG IMAGE + FORM */
        .right-panel {
            flex: 1.5;
            background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), 
                              url('admin register.jpg'); 
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* The actual login box */
        .login-card { 
            background: white; 
            padding: 40px; 
            border-radius: 12px; 
            width: 100%; 
            max-width: 380px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        
        h2 { color: var(--qatar-maroon); margin-bottom: 5px; font-size: 28px; }
        .subtitle { color: #666; margin-bottom: 25px; font-size: 14px; }

        .error-msg { background: #fdecea; color: #d32f2f; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 13px; text-align: center; }

        input {
            width: 100%; padding: 14px; margin: 10px 0;
            border: 1px solid #ddd; border-radius: 8px;
            box-sizing: border-box; font-size: 15px;
        }

        button {
            width: 100%; padding: 14px; background: var(--qatar-maroon);
            color: white; border: none; border-radius: 8px;
            cursor: pointer; font-size: 16px; font-weight: bold;
            margin-top: 15px;
        }

        .create-link { text-align: center; margin-top: 25px; border-top: 1px solid #eee; padding-top: 15px; }
        .create-link a { color: var(--qatar-maroon); text-decoration: none; font-weight: bold; }

        .logo-main { font-size: 50px; font-weight: bold; color: var(--qatar-maroon); letter-spacing: -2px; }
        .logo-sub { font-size: 18px; letter-spacing: 5px; color: #333; }
    </style>
</head>
<body>

    <div class="container">
        
        <div class="left-panel">
            <div class="logo-main">QATAR</div>
            <div class="logo-sub">AIRWAYS</div>
            <div style="margin-top: 30px; color: #888; text-align: center; max-width: 300px; line-height: 1.6;">
                <p>Welcome to the Internal Flight Management System. Please use your authorized credentials.</p>
            </div>
        </div>

        <div class="right-panel">
            <div class="login-card">
                <h2>Admin Login</h2>
                <p class="subtitle">Secure access to the A350-900 dashboard.</p>

                <?php if(isset($error)) echo "<div class='error-msg'>$error</div>"; ?>

                <form method="POST">
                    <input type="email" name="email" placeholder="Staff Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="login_admin">Sign In</button>
                </form>

                <div class="create-link">
                    <span>Need access?</span><br>
                    <a href="admin_register.php">Create Admin Account</a>
                </div>
            </div>
        </div>

    </div>

</body>
</html>