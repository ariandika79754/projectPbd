<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header('Location: index.php');
    exit;
}

if (isset($_GET['film_id'])) {
    $film_id = $_GET['film_id'];
    $stmt = $pdo->prepare("SELECT * FROM film WHERE id = ?");
    $stmt->execute([$film_id]);
    $film = $stmt->fetch();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul_film = $_POST['judul_film'];
    $genre = $_POST['genre'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $gambar = $_POST['gambar'];

    $stmt = $pdo->prepare("UPDATE film SET judul_film = ?, genre = ?, harga = ?, stok = ?, gambar = ? WHERE id = ?");
    $stmt->execute([$judul_film, $genre, $harga, $stok, $gambar, $film_id]);
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
    <title>Edit Film</title>
</head>
<body>
    <h1>Edit Film</h1>
    <form action="edit_film.php?film_id=<?php echo $film['id']; ?>" method="POST">
        <input type="text" name="judul_film" value="<?php echo $film['judul_film']; ?>" required>
        <input type="text" name="genre" value="<?php echo $film['genre']; ?>" required>
        <input type="number" name="harga" value="<?php echo $film['harga']; ?>" required>
        <input type="number" name="stok" value="<?php echo $film['stok']; ?>" required>
        <input type="text" name="gambar" value="<?php echo $film['gambar']; ?>" required>
        <button type="submit">Update Film</button>
    </form>
</body>
</html>