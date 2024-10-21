<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM film");
$films = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        $film_id = $_POST['film_id'];
        $stmt = $pdo->prepare("DELETE FROM film WHERE id = ?");
        $stmt->execute([$film_id]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Admin Panel</title>
    <style>
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
        h1 {
            text-align: center;
            color: #333;
        }

        h2 {
            margin-top: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        button {
            background-color: #74ebd5;
            border: none;
            color: white;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #58c0a5;
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
            <a href="manage_films.php">Kelola Film</a>
            <button class="btn" onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </div>
        <h1>Admin Panel</h1>
        <h2><a href="add_film.php">Tambah Film</a></h2>

        <h2>Daftar Film</h2>
        <table>
            <tr>
                <th>Judul</th>
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
                    <td><img src="images/<?php echo $film['gambar']; ?>" alt="<?php echo $film['judul_film']; ?>" style="max-width: 100px; max-height: 100px;"></td>
                    <td>
                        <form action="edit_film.php" method="GET" style="display:inline;">
                            <input type="hidden" name="film_id" value="<?php echo $film['id']; ?>">
                            <button type="submit">Edit</button>
                        </form>
                        <form action="admin.php" method="POST" style="display:inline;">
                            <input type="hidden" name="film_id" value="<?php echo $film['id']; ?>">
                            <button type="submit" name="delete">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>