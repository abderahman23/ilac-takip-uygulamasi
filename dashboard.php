<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// İlaçları getir
$stmt = $pdo->prepare("SELECT * FROM medicines WHERE user_id = ? ORDER BY time");
$stmt->execute([$user_id]);
$medicines = $stmt->fetchAll();

// İlaç silme
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM medicines WHERE id = ? AND user_id = ?");
    $stmt->execute([$delete_id, $user_id]);
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İlaç Takip Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">İlaç Takip</a>
        <div class="navbar-nav">
            <a href="logout.php" class="nav-link">Çıkış Yap</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>İlaçlarım</h2>
        <a href="add.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Yeni İlaç Ekle
        </a>
    </div>

    <?php if (empty($medicines)): ?>
        <div class="alert alert-info">Henüz ilaç eklemediniz.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>İlaç Adı</th>
                        <th>Alım Saati</th>
                        <th>Notlar</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($medicines as $medicine): ?>
                    <tr>
                        <td><?= htmlspecialchars($medicine['medicine_name']) ?></td>
                        <td><?= date('H:i', strtotime($medicine['time'])) ?></td>
                        <td><?= htmlspecialchars($medicine['notes']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $medicine['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="dashboard.php?delete=<?= $medicine['id'] ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Bu ilacı silmek istediğinize emin misiniz?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
