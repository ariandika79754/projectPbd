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
    <link rel="stylesheet" href="css/styles.css">
    <title>Daftar Film</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
            width: 100px;
            height: auto;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #0056b3;
        }

        #cartPopup {
            display: none;
            position: fixed;
            top: 20%;
            right: 5%;
            width: 300px;
            max-height: 400px;
            overflow-y: auto;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 10px;
        }

        #cartPopup h2 {
            margin-top: 0;
        }

        #cartPopup .close {
            cursor: pointer;
            color: red;
            float: right;
        }
    </style>
    <script>
        function addToCart(event, form) {
            event.preventDefault(); // Mencegah form dikirim secara default

            const formData = new FormData(form);

            fetch('add_to_cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Tampilkan pesan berdasarkan respons
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
    <h1>Daftar Film</h1>

    <!-- Icon Keranjang -->
    <div style="text-align: right; margin-bottom: 20px;">
        <span onclick="toggleCart()" style="cursor: pointer;">
            🛒 Keranjang (<?php echo count($cart); ?>)
        </span>
    </div>

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

    <a href="index.php">Kembali ke Beranda</a>
</body>

</html>