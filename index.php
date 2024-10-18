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
    <title>Film Ticket Booking</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        h1 {
            margin: 20px 0;
            color: #333;
        }

        .film-container {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            /* Menampilkan 6 film dalam satu baris */
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
        }

        .user-info a {
            padding: 12px 20px;
            background-color: #ff5722;
            color: white;
            font-size: 1.1rem;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        .user-info a:hover {
            background-color: #e64a19;
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <h1>Selamat datang di Bioskop Ramayani</h1>
    <h4>Siapkan diri Anda untuk menjelajahi dunia sinema yang penuh petualangan dan emosi.
        Kami menghadirkan film-film terpopuler yang siap membuat Anda terpesona!</h4>

    <div class="user-info">
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Selamat datang, <?php echo $_SESSION['username']; ?></p>
            <div>
                <a href="logout.php">Logout</a>
                <?php if ($_SESSION['role_id'] == 1): // Admin 
                ?>
                    <a href="admin.php">Admin Panel</a>
                <?php elseif ($_SESSION['role_id'] == 2): // User 
                ?>
                    <a href="user.php">Film</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>

    <div class="film-container">
        <?php foreach ($films as $film): ?>
            <div class="film-card">
                <img src="images/<?php echo $film['gambar']; ?>" alt="<?php echo $film['judul_film']; ?>">
                <h2><?php echo $film['judul_film']; ?></h2>
                <p>Genre: <?php echo $film['genre']; ?></p>
                <p>Harga: Rp<?php echo number_format($film['harga'], 2, ',', '.'); ?></p>
                <p>Stok: <?php echo $film['stok']; ?></p>
                <a href="add_to_cart.php?id=<?php echo $film['id']; ?>">Tambah ke Keranjang</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>