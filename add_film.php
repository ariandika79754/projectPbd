<?php
session_start();
include 'config.php';

// Cek apakah pengguna memiliki akses
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header('Location: index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul_film = trim($_POST['judul_film']);
    $genre = trim($_POST['genre']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Proses unggah gambar
    $gambar = $_FILES['gambar']['name'];
    $target_dir = "images/"; // Pastikan folder ini ada dan memiliki izin yang sesuai
    $target_file = $target_dir . basename($gambar);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek apakah file gambar valid
    $check = getimagesize($_FILES['gambar']['tmp_name']);
    if ($check === false) {
        echo "File bukan gambar.";
        $uploadOk = 0;
    }

    // Cek ukuran file (misalnya maksimal 2MB)
    if ($_FILES['gambar']['size'] > 2000000) {
        echo "Maaf, ukuran file terlalu besar.";
        $uploadOk = 0;
    }

    // Cek tipe file
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
        $uploadOk = 0;
    }

    // Cek jika $uploadOk bernilai 0 berarti ada kesalahan
    if ($uploadOk == 0) {
        echo "Maaf, file tidak diunggah.";
    } else {
        // Jika semuanya ok, unggah file ke server
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            // Simpan hanya nama file gambar di database
            $stmt = $pdo->prepare("INSERT INTO film (judul_film, genre, harga, stok, gambar) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$judul_film, $genre, $harga, $stok, $gambar]); // Simpan hanya nama file
            header('Location: admin.php'); // Redirect ke halaman admin setelah menambah film
            exit;
        } else {
            echo "Maaf, ada kesalahan saat mengunggah file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Tambah Film</title>
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
            max-width: 600px;
            margin: auto;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
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
        <h1>Tambah Film</h1>
        <form action="add_film.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="judul_film" placeholder="Judul Film" required>
            <input type="text" name="genre" placeholder="Genre" required>
            <input type="number" name="harga" placeholder="Harga" required>
            <input type="number" name="stok" placeholder="Stok" required>
            <input type="file" name="gambar" accept="image/*" required>
            <button type="submit">Tambah Film</button>
        </form>
    </div>
</body>

</html>