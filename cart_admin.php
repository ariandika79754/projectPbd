<?php
session_start();
include 'config.php';

// Cek apakah pengguna sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header('Location: login.php');
    exit;
}

// Ambil semua data keranjang
$stmt = $pdo->prepare("
    SELECT keranjang.id AS cart_id, users.username, film.judul_film, film.harga, keranjang.jumlah
    FROM keranjang
    JOIN users ON keranjang.user_id = users.id
    JOIN film ON keranjang.film_id = film.id
");
$stmt->execute();
$cartItems = $stmt->fetchAll();

// Hitung total harga
$totalHarga = 0;
foreach ($cartItems as $item) {
    $totalHarga += $item['harga'] * $item['jumlah'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Admin - Bioskop Ramayani</title>
    <style>
        body,
        table,
        th,
        td,
        input,
        button {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #74ebd5, #ACB6E5);
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            margin: auto;
        }
        .navbar .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            margin-left: 30px;
            padding-left: 20px;
        }

        .navbar {
            background-color: #A45EE9;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            transition: background-color 0.3s ease;
        }

        .navbar .btn {
            background-color: #74ebd5;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="navbar">
            <div class="logo">Bioskop Ramayani</div>
            <div class="menu">
                <a href="index.php">Home</a>
                <a href="admin.php">Admin Panel</a>
                <a href="cart_admin.php">Keranjang</a>
                <a href="user_list.php">User</a>
                <button class="btn" onclick="window.location.href='logout.php'">Logout</button>
            </div>
        </div>

        <h1>Keranjang Admin</h1>
        <?php if (empty($cartItems)): ?>
            <p>Keranjang kosong.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Judul Film</th>
                        <th>Jumlah Tiket</th>
                        <th>Harga (per tiket)</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['cart_id']); ?></td>
                            <td><?php echo htmlspecialchars($item['username']); ?></td>
                            <td><?php echo htmlspecialchars($item['judul_film']); ?></td>
                            <td><?php echo htmlspecialchars($item['jumlah']); ?></td>
                            <td>Rp<?php echo number_format($item['harga'], 2, ',', '.'); ?></td>
                            <td>Rp<?php echo number_format($item['harga'] * $item['jumlah'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h3>Total Keseluruhan: Rp<?php echo number_format($totalHarga, 2, ',', '.'); ?></h3>
        <?php endif; ?>
    </div>
</body>

</html>
