<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die("Access Denied!");
}

// KERENTANAN: Command Injection
if(isset($_GET['cmd'])) {
    $cmd = $_GET['cmd'];
    echo "<h3>Command Output:</h3>";
    echo "<pre>";
    system($cmd);
    echo "</pre>";
}

// KERENTANAN: Directory Traversal
if(isset($_GET['file'])) {
    $file = $_GET['file'];
    if(file_exists($file)) {
        $content = file_get_contents($file);
        echo "<h3>File Content:</h3>";
        echo "<pre>" . htmlspecialchars($content) . "</pre>";
    } else {
        echo "<p>File not found!</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .panel { background: white; padding: 30px; border-radius: 10px; max-width: 800px; margin: 0 auto; }
        h1 { color: #667eea; }
        .form-group { margin: 20px 0; }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 5px 0;
        }
        button {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        pre {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="panel">
        <h1>⚙️ Admin Panel</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        
        <div class="form-group">
            <h3>Execute Command (VULNERABLE!)</h3>
            <form method="GET">
                <input type="text" name="cmd" placeholder="Enter command..." 
                       value="<?php echo isset($_GET['cmd']) ? htmlspecialchars($_GET['cmd']) : ''; ?>">
                <button type="submit">Execute</button>
            </form>
        </div>
        
        <div class="form-group">
            <h3>Read File (Directory Traversal!)</h3>
            <form method="GET">
                <input type="text" name="file" placeholder="Enter file path..." 
                       value="<?php echo isset($_GET['file']) ? htmlspecialchars($_GET['file']) : ''; ?>">
                <button type="submit">Read</button>
            </form>
        </div>
        
        <a href="dashboard.php" style="display: inline-block; margin-top: 20px; color: #667eea;">← Back to Dashboard</a>
    </div>
</body>
</html>
