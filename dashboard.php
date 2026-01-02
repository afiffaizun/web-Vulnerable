<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'config.php';

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM transactions WHERE user_id='$user_id' ORDER BY date DESC LIMIT 5";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - SecureBank</title>
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
            transition: 0.3s;
        }
        .navbar a:hover { background: rgba(255,255,255,0.2); }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .welcome {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .welcome h1 { color: #333; margin-bottom: 10px; }
        .welcome .role {
            display: inline-block;
            padding: 5px 15px;
            background: #667eea;
            color: white;
            border-radius: 20px;
            font-size: 12px;
        }
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card h3 { color: #667eea; margin-bottom: 10px; }
        .card .amount { font-size: 32px; font-weight: bold; color: #333; }
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
            <?php if($role == 'admin'): ?>
                <a href="admin.php">Admin Panel</a>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <div class="welcome">
            <h1>Selamat Datang, <?php echo htmlspecialchars($username); ?>!</h1>
            <span class="role"><?php echo strtoupper($role); ?></span>
        </div>
        
        <div class="cards">
            <div class="card">
                <h3>Total Saldo</h3>
                <div class="amount">Rp 15.750.000</div>
            </div>
            <div class="card">
                <h3>Pengeluaran Bulan Ini</h3>
                <div class="amount">Rp 4.250.000</div>
            </div>
            <div class="card">
                <h3>Pemasukan Bulan Ini</h3>
                <div class="amount">Rp 8.500.000</div>
            </div>
        </div>
        
        <h2 style="margin-bottom: 15px; color: #333;">Transaksi Terakhir</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
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
