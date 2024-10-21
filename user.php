<?php
session_start();
include 'config.php';

// Jika pengguna belum login, arahkan ke halaman login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Ambil data film
$stmt = $pdo->query("SELECT * FROM film");
$films = $stmt->fetchAll();

// Mengambil data keranjang dari sesi
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Film - Bioskop Ramayani</title>
    <style>
        /* Reset some default styles */
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
        .navbar .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            margin-left: 30px;
            /* Beri jarak dari tepi kiri */
            padding-left: 20px;
            /* Atau gunakan padding */
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

        table {
            width: 80%;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        img {
            max-width: 80px;
            height: auto;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        #cartPopup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1000;
        }

        #cartPopup .close {
            cursor: pointer;
            color: red;
            float: right;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 10px;
            }

            .navbar a {
                padding: 8px 12px;
            }

            table {
                font-size: 14px;
                width: 100%;
            }

            img {
                max-width: 60px;
            }
        }
    </style>
    <script>
        function addToCart(event, form) {
            event.preventDefault();
            const formData = new FormData(form);

            fetch('add_to_cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                });
        }

        function toggleCart() {
            const cartPopup = document.getElementById('cartPopup');
            cartPopup.style.display = cartPopup.style.display === 'block' ? 'none' : 'block';
        }

        function closeCart() {
            document.getElementById('cartPopup').style.display = 'none';
        }
    </script>
</head>

<body>
<div class="container">
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">Bioskop Ramayani</div>
        <div class="menu">
            <a href="index.php">Home</a>
            <a href="user.php">Film</a>
            <a href="cart.php">Keranjang</a>
            <button class="btn" onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </div>

    <h1 align="center">Daftar Film</h1>



    <!-- Popup Keranjang -->
    <div id="cartPopup">
        <span class="close" onclick="closeCart()">✖</span>
        <h2>Isi Keranjang</h2>
        <?php if (empty($cart)): ?>
            <p>Keranjang Anda kosong.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($cart as $item): ?>
                    <li><?php echo $item['judul_film']; ?> - Jumlah: <?php echo $item['jumlah']; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <table>
        <tr>
            <th>Judul Film</th>
            <th>Genre</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Gambar</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($films as $film): ?>
            <tr>
                <td><?php echo $film['judul_film']; ?></td>
                <td><?php echo $film['genre']; ?></td>
                <td>Rp<?php echo number_format($film['harga'], 2, ',', '.'); ?></td>
                <td><?php echo $film['stok']; ?></td>
                <td><img src="images/<?php echo $film['gambar']; ?>" alt="<?php echo $film['judul_film']; ?>"></td>
                <td>
                    <form onsubmit="addToCart(event, this)">
                        <input type="hidden" name="film_id" value="<?php echo $film['id']; ?>">
                        <input type="number" name="jumlah" value="1" min="1" style="width: 50px;">
                        <button type="submit">Tambah ke Keranjang</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
        </div>
</body>

</html>