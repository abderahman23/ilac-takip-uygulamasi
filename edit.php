<?php
session_start();
require_once 'db.php';

// Giriş kontrolü
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// İlacın ID'si yoksa dashboard'a dön
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$medicine_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// İlaç verisini çek
$stmt = $pdo->prepare("SELECT * FROM medicines WHERE id = ? AND user_id = ?");
$stmt->execute([$medicine_id, $user_id]);
$medicine = $stmt->fetch();

// İlaç bulunamadıysa yönlendir
if (!$medicine) {
    header("Location: dashboard.php");
    exit();
}

// Form gönderildiyse güncelle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['medicine_name'];
    $time = $_POST['time'];
    $notes = $_POST['notes'];

    $stmt = $pdo->prepare("UPDATE medicines SET medicine_name = ?, time = ?, notes = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$name, $time, $notes, $medicine_id, $user_id]);

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İlaç Güncelle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>📝 İlaç Bilgisini Güncelle</h2>
    <form method="post" class="bg-white p-4 shadow-sm rounded mt-3">
        <div class="mb-3">
            <label for="medicine_name" class="form-label">İlaç Adı</label>
            <input type="text" name="medicine_name" id="medicine_name" class="form-control" value="<?= htmlspecialchars($medicine['medicine_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="time" class="form-label">Alım Saati</label>
            <input type="time" name="time" id="time" class="form-control" value="<?= htmlspecialchars($medicine['time']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notlar</label>
            <textarea name="notes" id="notes" rows="3" class="form-control"><?= htmlspecialchars($medicine['notes']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">Kaydet</button>
        <a href="dashboard.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
</body>
</html>
