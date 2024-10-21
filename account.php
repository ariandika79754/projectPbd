<?php
session_start();
include 'config.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil data user dari database berdasarkan user_id
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Jika user tidak ditemukan
if (!$user) {
    echo "User tidak ditemukan.";
    exit();
}

// Update data pengguna jika form dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi password baru
    if (!empty($new_password) && $new_password !== $confirm_password) {
        $error_message = "Password baru dan konfirmasi password tidak cocok.";
    } else {
        // Update data pengguna
        $update_query = "UPDATE users SET username = ?, email = ?";
        $params = [$username, $email];

        // Hanya update password jika ada input
        if (!empty($new_password)) {
            $update_query .= ", password = ?";
            $params[] = password_hash($new_password, PASSWORD_DEFAULT);
        }
        $update_query .= " WHERE id = ?";
        $params[] = $user_id;

        $stmt = $pdo->prepare($update_query);
        $stmt->execute($params);

        header("Location: account.php"); // Redirect setelah update
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil User</title>
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

        .info {
            margin-bottom: 15px;
        }

        #cartPopup .close {
            cursor: pointer;
            color: red;
            float: right;
        }

        .form-group {
            margin-top: 20px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .error {
            color: red;
            margin-top: 10px;
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
</head>

<body>

    <div class="container">
        <div class="navbar">
            <div class="logo">Bioskop Ramayani</div>
            <div class="menu">
                <a href="index.php">Home</a>
                <a href="user.php">Film</a>
                <a href="cart.php">Keranjang</a>
                <a href="account.php">Profil</a>
                <button class="btn" onclick="window.location.href='logout.php'">Logout</button>
            </div>
        </div>
        <h2 align="center">Profil Pengguna</h2>

        <form method="POST">
            <div class="info">
                <strong>Username:</strong>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="info">
                <strong>Email:</strong>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="info">
                <strong>Role:</strong> <?php echo $user['role_id'] == 1 ? 'Admin' : 'User'; ?>
            </div>

            <div class="info">
                <strong>Password:</strong>
                <input type="password" name="new_password" placeholder="Masukkan password baru (opsional)">
            </div>

            <div class="info">
                <strong>Konfirmasi Password:</strong>
                <input type="password" name="confirm_password" placeholder="Konfirmasi password baru (opsional)">
            </div>

            <button type="submit" class="btn">Simpan Perubahan</button>
        </form>

        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
    </div>

</body>

</html>