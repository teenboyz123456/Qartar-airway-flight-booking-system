<?php
session_start();
include('dbconnect.php');

if(isset($_POST['register_admin'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    $sql = "INSERT INTO users (fullname, email, password, role) VALUES ('$fullname', '$email', '$password', 'admin')";
    
    if(mysqli_query($conn, $sql)) {
        echo "<script>alert('Admin Account Created Successfully!'); window.location='admin_login.php';</script>";
    } else {
        $error = "Registration failed. Email might already exist.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Registration | Qatar Airways</title>
    <style>
        :root { --qatar-maroon: #700029; }
        
        body, html { margin: 0; padding: 0; height: 100%; font-family: 'Segoe UI', sans-serif; overflow: hidden; }

        .container { display: flex; height: 100vh; width: 100%; }

        /* LEFT SIDE - FORM SECTION */
        .left-panel {
            flex: 1;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        /* RIGHT SIDE - BACKGROUND IMAGE */
        .right-panel {
            flex: 1.5;
            background-image: linear-gradient(rgba(112, 0, 41, 0.3), rgba(112, 0, 41, 0.3)), 
                              url('login image.jpg'); 
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
        }

        /* FORM STYLING */
        .reg-card { 
            width: 100%; 
            max-width: 380px; 
        }
        
        h2 { color: var(--qatar-maroon); font-size: 32px; margin-bottom: 5px; }
        .subtitle { color: #666; margin-bottom: 30px; font-size: 14px; }

        .error-msg { background: #fee2e2; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 13px; text-align: center; }

        input {
            width: 100%; padding: 15px; margin: 10px 0;
            border: 1px solid #ddd; border-radius: 8px;
            box-sizing: border-box; font-size: 16px;
            transition: 0.3s;
        }
        input:focus { border-color: var(--qatar-maroon); outline: none; box-shadow: 0 0 5px rgba(112, 0, 41, 0.2); }

        button {
            width: 100%; padding: 15px; background: var(--qatar-maroon);
            color: white; border: none; border-radius: 8px;
            cursor: pointer; font-size: 18px; font-weight: bold;
            margin-top: 20px; transition: 0.3s;
        }
        button:hover { background: #50001d; }

        .login-link { text-align: center; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; font-size: 14px; }
        .login-link a { color: var(--qatar-maroon); text-decoration: none; font-weight: bold; }

        .brand-text { font-size: 50px; font-weight: bold; letter-spacing: 5px; }
        .tagline { font-size: 18px; font-weight: 300; letter-spacing: 3px; opacity: 0.9; }
    </style>
</head>
<body>

    <div class="container">
        
        <div class="left-panel">
            <div class="reg-card">
                <h2>Admin Enrolment</h2>
                <p class="subtitle">Create an account to manage the A350-900 fleet.</p>

                <?php if(isset($error)) echo "<div class='error-msg'>$error</div>"; ?>

                <form method="POST">
                    <input type="text" name="fullname" placeholder="Full Name" required>
                    <input type="email" name="email" placeholder="Staff Email Address" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="register_admin">Register Account</button>
                </form>

                <div class="login-link">
                    <span>Already an Administrator?</span><br>
                    <a href="admin_login.php">Back to Login Portal</a>
                </div>
            </div>
        </div>

        <div class="right-panel">
            <div class="brand-text">QATAR</div>
            <div class="tagline">GOING PLACES TOGETHER</div>
            <p style="max-width: 400px; text-align: center; margin-top: 20px; line-height: 1.6;">
                Authorized personnel only. Access flight manifests, seat layouts, and real-time departure analytics.
            </p>
        </div>

    </div>

</body>
</html>