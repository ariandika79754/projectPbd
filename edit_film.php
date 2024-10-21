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

    // Check if a new file is uploaded
    if ($_FILES['gambar']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "images/";
        $fileName = basename($_FILES["gambar"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        
        // Allow only certain file formats
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        
        if (in_array(strtolower($fileType), $allowTypes)) {
            // Upload file to server
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFilePath)) {
                $gambar = $fileName;
            } else {
                echo "Sorry, there was an error uploading your file.";
                exit;
            }
        } else {
            echo "Sorry, only JPG, JPEG, PNG, & GIF files are allowed.";
            exit;
        }
    } else {
        // If no new file is uploaded, use the existing file name
        $gambar = $film['gambar'];
    }

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
    <title>Edit Film</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #8e9eab, #eef2f3);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
        }

        input[type="text"],
        input[type="number"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input[type="file"] {
            margin: 10px 0;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .image-preview {
            width: 100%;
            height: auto;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        img {
            max-width: 60px;
        }
    </style>
</head>

<body>
    <form action="edit_film.php?film_id=<?php echo $film['id']; ?>" method="POST" enctype="multipart/form-data">
        <h1>Edit Film</h1>
        <input type="text" name="judul_film" value="<?php echo $film['judul_film']; ?>" required placeholder="Judul Film">
        <input type="text" name="genre" value="<?php echo $film['genre']; ?>" required placeholder="Genre">
        <input type="number" name="harga" value="<?php echo $film['harga']; ?>" required placeholder="Harga">
        <input type="number" name="stok" value="<?php echo $film['stok']; ?>" required placeholder="Stok">
        <img src="images/<?php echo $film['gambar']; ?>" accept="image/*" alt="Current Image" class="image-preview">
        <input type="file" name="gambar">
        <button type="submit">Update Film</button>
    </form>
</body>

</html>