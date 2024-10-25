<?php
session_start();
include 'config.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Ambil data keranjang pengguna berdasarkan user_id dari session
$user_id = $_SESSION['user_id'];

// Initialize search query variable
$searchQuery = '';

// Check if a search has been performed
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
}

// Ambil data keranjang pengguna berdasarkan user_id dan pencarian
$stmt = $pdo->prepare("
    SELECT users.username, film.judul_film, film.harga, keranjang.jumlah
    FROM keranjang
    JOIN users ON keranjang.user_id = users.id
    JOIN film ON keranjang.film_id = film.id
    WHERE keranjang.user_id = ? AND (film.judul_film LIKE ? OR users.username LIKE ?)
");
$searchWildcard = "%$searchQuery%"; // Use wildcards for searching
$stmt->execute([$user_id, $searchWildcard, $searchWildcard]);
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
    <title>Keranjang - Bioskop Ramayani</title>
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
            font-family: 'Arial', sans-serif;
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

        /* Gaya untuk tombol pencarian */
        .search-btn {
            background-color: #007BFF;
            /* Biru */
            color: white;
            /* Warna teks putih */
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-btn:hover {
            background-color: #0056b3;
            /* Biru gelap saat hover */
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

        .navbar a:hover {
            background-color: #444;
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

        .navbar .btn:hover {
            background-color: #58c0a5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
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
                <a href="user.php">Film</a>
                <a href="cart.php">Keranjang</a>
                <a href="account.php">Profil</a>
                <button class="btn" onclick="window.location.href='logout.php'">Logout</button>
            </div>
        </div>

        <h1>Keranjang Anda</h1>
        <!-- Form Pencarian -->
        <form method="GET" action="" style="margin-bottom: 20px;">
            <input type="text" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Cari film atau pengguna..." required style="padding: 10px; width: 300px; border-radius: 5px; border: 1px solid #ddd; margin-bottom: 20px;">
            <button type="submit" class="search-btn">Cari</button>
        </form>
        <?php if (empty($cartItems)): ?>
            <p>Keranjang Anda kosong.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Judul Film</th>
                        <th>Jumlah Tiket</th>
                        <th>Harga (per tiket)</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
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