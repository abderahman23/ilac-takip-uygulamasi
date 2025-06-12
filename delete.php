<?php
session_start();
require_once 'db.php';

// Giriş kontrolü
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ID kontrolü
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$medicine_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// İlacı sil
$stmt = $pdo->prepare("DELETE FROM medicines WHERE id = ? AND user_id = ?");
$stmt->execute([$medicine_id, $user_id]);

// Dashboard'a geri dön
header("Location: dashboard.php");
exit();
?>