<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $film_id = $_POST['film_id'];
    $jumlah = $_POST['jumlah'];

    // Cek apakah film sudah ada di keranjang
    $stmt = $pdo->prepare("SELECT * FROM keranjang WHERE user_id = ? AND film_id = ?");
    $stmt->execute([$user_id, $film_id]);
    $existingCart = $stmt->fetch();

    // Ambil stok film yang ada
    $stmt = $pdo->prepare("SELECT stok FROM film WHERE id = ?");
    $stmt->execute([$film_id]);
    $film = $stmt->fetch();

    // Cek apakah ada cukup stok
    if ($film && $film['stok'] >= $jumlah) {
        if ($existingCart) {
            // Jika sudah ada, update jumlah
            $stmt = $pdo->prepare("UPDATE keranjang SET jumlah = jumlah + ? WHERE user_id = ? AND film_id = ?");
            $stmt->execute([$jumlah, $user_id, $film_id]);
        } else {
            // Jika belum ada, tambahkan item baru
            $stmt = $pdo->prepare("INSERT INTO keranjang (user_id, film_id, jumlah) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $film_id, $jumlah]);
        }

        // Kurangi stok film
        $new_stok = $film['stok'] - $jumlah;
        $stmt = $pdo->prepare("UPDATE film SET stok = ? WHERE id = ?");
        $stmt->execute([$new_stok, $film_id]);

        echo json_encode(['message' => 'Film berhasil ditambahkan ke keranjang!']);
    } else {
        echo json_encode(['message' => 'Stok tidak cukup!']);
    }
}
