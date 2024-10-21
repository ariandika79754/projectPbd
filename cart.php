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

$stmt = $pdo->prepare("
    SELECT users.username, film.judul_film, keranjang.jumlah 
    FROM keranjang
    JOIN users ON keranjang.user_id = users.id
    JOIN film ON keranjang.film_id = film.id
    WHERE keranjang.user_id = ?
");
$stmt->execute([$user_id]);
$cartItems = $stmt->fetchAll();
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
                <button class="btn" onclick="window.location.href='logout.php'">Logout</button>
            </div>
        </div>

        <h1>Keranjang Anda</h1>
        <?php if (empty($cartItems)): ?>
            <p>Keranjang Anda kosong.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Judul Film</th>
                        <th>Jumlah Tiket</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['username']); ?></td>
                            <td><?php echo htmlspecialchars($item['judul_film']); ?></td>
                            <td><?php echo htmlspecialchars($item['jumlah']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>