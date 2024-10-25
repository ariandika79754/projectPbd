<?php
session_start();
include 'config.php';

// Cek session user
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header('Location: index.php');
    exit;
}

// Ambil kata kunci pencarian jika ada
$search_keyword = '';
if (isset($_POST['search'])) {
    $search_keyword = $_POST['search_keyword'];
    // Siapkan query untuk pencarian
    $stmt = $pdo->prepare("SELECT * FROM film WHERE judul_film LIKE ? OR genre LIKE ?");
    $stmt->execute(["%$search_keyword%", "%$search_keyword%"]);
} else {
    // Ambil semua film jika tidak ada pencarian
    $stmt = $pdo->query("SELECT * FROM film");
}
$films = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $film_id = $_POST['film_id'];
    $stmt = $pdo->prepare("DELETE FROM film WHERE id = ?");
    $stmt->execute([$film_id]);

    // Simpan flash message di session
    $_SESSION['flash_message'] = "Film berhasil dihapus.";

    // Redirect agar flash message bisa ditampilkan
    header('Location: admin.php');
    exit;
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
            background-color: #007bff;
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

        .alert {
            background-color: #4caf50;
            color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
    <script>
        function confirmDelete() {
            return confirm("Apakah Anda yakin ingin menghapus film ini?");
        }
    </script>
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

        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert">
                <?php echo $_SESSION['flash_message'];
                unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <h1>Admin Panel</h1>
        <h2><a href="add_film.php">Tambah Film</a></h2>

        <h2>Daftar Film</h2>

        <!-- Form Pencarian -->
        <form method="POST" action="" style="margin-bottom: 20px;">
            <input type="text" name="search_keyword" value="<?php echo htmlspecialchars($search_keyword); ?>" placeholder="Cari film atau genre..." required style="padding: 10px; width: 300px; border-radius: 5px; border: 1px solid #ddd; margin-bottom: 20px;">
            <button type="submit" name="search">Cari</button>
        </form>

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
                        <form action="admin.php" method="POST" onsubmit="return confirmDelete();" style="display:inline;">
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