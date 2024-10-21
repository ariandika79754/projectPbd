<?php
session_start();
include 'config.php';

// Pastikan admin yang mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit();
}

// Dapatkan id user yang akan dihapus
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Hapus user dari database
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    // Redirect kembali ke daftar user
    header("Location: user_list.php");
    exit();
}
