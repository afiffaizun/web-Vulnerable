<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'config.php';

// KERENTANAN: SQL Injection via search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$user_id = $_SESSION['user_id'];

if($search) {
    $query = "SELECT * FROM transactions WHERE user_id='$user_id' AND description LIKE '%$search%'";
} else {
    $query = "SELECT * FROM transactions WHERE user_id='$user_id' ORDER BY date DESC";
}

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi - SecureBank</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar {
            background: #667eea;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h2 { font-size: 24px; }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            padding: 8px 16px;
            border-radius: 5px;
        }
        .navbar a:hover { background: rgba(255,255,255,0.2); }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .search-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .search-box input {
            width: 400px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .search-box button {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }
        table {
            width: 100%;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        th, td { padding: 15px; text-align: left; }
        th { background: #667eea; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>🏦 SecureBank</h2>
        <div>
            <a href="dashboard.php">Dashboard</a>
            <a href="transactions.php">Transaksi</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <h1 style="margin-bottom: 20px; color: #333;">Semua Transaksi</h1>
        
        <div class="search-box">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Cari transaksi..." 
                       value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Cari</button>
            </form>
            <?php if($search): ?>
                <p style="margin-top: 10px;">Hasil pencarian untuk: <strong><?php echo htmlspecialchars($search); ?></strong></p>
            <?php endif; ?>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>Rp <?php echo number_format($row['amount'], 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
