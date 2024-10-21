<?php
session_start();
include 'config.php';
$stmt = $pdo->query("SELECT * FROM film");
$films = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Home - Bioskop Ramayani</title>
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

        h1 {
            margin: 20px 0;
            color: #333;
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
        .alert {
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .alert.error {
            background-color: #f44336;
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

        /* Content styles */
        .film-container {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 15px;
            width: 90%;
            max-width: 1200px;
            margin-bottom: 40px;
        }

        .film-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 10px;
        }

        .film-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .film-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .film-card h2 {
            font-size: 1rem;
            color: #333;
            margin: 5px 0;
        }

        .film-card p {
            font-size: 0.8rem;
            color: #666;
            margin: 3px 0;
        }

        .film-card a {
            display: inline-block;
            margin: 10px 0;
            padding: 6px 10px;
            background-color: #74ebd5;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .film-card a:hover {
            background-color: #58c0a5;
        }

        .user-info {
            text-align: center;
            margin: 20px 0;
        }

        .user-info p {
            font-size: 1.2rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Navbar -->
        <div class="navbar">
            <div class="logo">Bioskop Ramayani</div>
            <div class="menu">

                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role_id'] == 1): // Admin 
                    ?> <a href="#">Home</a>
                        <a href="admin.php">Admin Panel</a>
                        <a href="manage_films.php">Kelola Film</a>
                    <?php elseif ($_SESSION['role_id'] == 2): // User 
                    ?> <a href="#">Home</a>
                        <a href="user.php">Film</a>
                        <a href="cart.php">Keranjang</a>
                    <?php endif; ?>
                    <button class="btn" onclick="window.location.href='logout.php'">Logout</button>
                <?php else: ?>
                    <button class="btn" onclick="window.location.href='login.php'">Login</button>
                <?php endif; ?>
            </div>
        </div>

        <h1 align="center">Selamat datang di Bioskop Ramayani</h1>
        <h4>Siapkan diri Anda untuk menjelajahi dunia sinema yang penuh petualangan dan emosi.
            Kami menghadirkan film-film terpopuler yang siap membuat Anda terpesona!</h4>

        <div class="film-container">
            <?php if (!empty($films)): ?>
                <?php foreach ($films as $film): ?>
                    <div class="film-card">
                        <img src="images/<?php echo $film['gambar']; ?>" alt="<?php echo $film['judul_film']; ?>">
                        <h2><?php echo $film['judul_film']; ?></h2>
                        <p>Genre: <?php echo $film['genre']; ?></p>
                        <p>Harga: Rp<?php echo number_format($film['harga'], 2, ',', '.'); ?></p>
                        <p>Stok: <?php echo $film['stok']; ?></p>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="" class="btn">Tambah ke Keranjang</a>
                        <?php else: ?>
                            <a href="login.php" class="btn">Login untuk Tambah ke Keranjang</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Tidak ada film yang tersedia saat ini.</p>
            <?php endif; ?>
        </div>

    </div>
</body>

</html>