<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // KERENTANAN: SQL Injection - No prepared statement
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $log_file = '/var/log/webapp/login_attempts.log';
    $log_msg = date('Y-m-d H:i:s') . " | IP: $ip | User: $username | Status: ";
    
    if(mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        file_put_contents($log_file, $log_msg . "SUCCESS\n", FILE_APPEND);
        header("Location: dashboard.php");
        exit();
    } else {
        file_put_contents($log_file, $log_msg . "FAILED\n", FILE_APPEND);
        $error = "Login gagal! Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureBank - Financial Portal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            width: 400px;
        }
        h1 { color: #333; margin-bottom: 10px; text-align: center; }
        .subtitle { text-align: center; color: #666; margin-bottom: 30px; font-size: 14px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: 500; }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover { background: #5568d3; }
        .error {
            background: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #c33;
        }
        .hint {
            background: #e8f4f8;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 12px;
            color: #555;
        }
        .hint strong { color: #333; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏦 SecureBank</h1>
        <p class="subtitle">Financial Management Portal</p>
        
        <?php if(isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" name="login">Login</button>
        </form>
        
        <div class="hint">
            <strong>Demo Accounts:</strong><br>
            User: admin / admin123<br>
            User: user / user123<br>
            <br>
            <strong>⚠️ VULNERABLE APP FOR TRAINING ONLY!</strong>
        </div>
    </div>
</body>
</html>
