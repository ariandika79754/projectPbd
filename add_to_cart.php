<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $film_id = $_POST['film_id'] ?? null;
    $jumlah = $_POST['jumlah'] ?? 1;

    // Ambil film dari database
    $stmt = $pdo->prepare("SELECT * FROM film WHERE id = ?");
    $stmt->execute([$film_id]);
    $film = $stmt->fetch();

    if ($film && $film['stok'] > 0) {
        // Tambah ke keranjang
        $stmt = $pdo->prepare("INSERT INTO keranjang (film_id, jumlah) VALUES (?, ?)");
        $stmt->execute([$film_id, $jumlah]);

        // Kurangi stok
        $new_stok = $film['stok'] - $jumlah;
        $stmt = $pdo->prepare("UPDATE film SET stok = ? WHERE id = ?");
        $stmt->execute([$new_stok, $film_id]);

        echo json_encode([
            'success' => true,
            'message' => "Film '{$film['judul_film']}' berhasil ditambahkan ke keranjang."
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => "Stok film '{$film['judul_film']}' tidak tersedia."
        ]);
    }

    exit; // Akhiri eksekusi setelah mengirim respons
}
?>
