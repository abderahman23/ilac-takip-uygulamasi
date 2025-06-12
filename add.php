<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $medicine_name = trim($_POST["medicine_name"]);
    $time = $_POST["time"];
    $notes = trim($_POST["notes"]);
    
    if (empty($medicine_name)) {
        $error = "İlaç adı boş olamaz.";
    } elseif (empty($time)) {
        $error = "Alım saati boş olamaz.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO medicines (user_id, medicine_name, time, notes) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $medicine_name, $time, $notes]);
            $success = true;
            
            header("refresh:2;url=dashboard.php");
        } catch (PDOException $e) {
            $error = "Veritabanı hatası: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni İlaç Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">💊 Yeni İlaç Ekle</h4>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <strong>Başarılı!</strong> İlaç başarıyla eklendi. Dashboard'a yönlendiriliyorsunuz...
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <strong>Hata:</strong> <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!$success): ?>
                    <form method="POST" action="add.php">
                        <div class="mb-3">
                            <label for="medicine_name" class="form-label">İlaç Adı *</label>
                            <input type="text" class="form-control" id="medicine_name" name="medicine_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="time" class="form-label">Alım Saati *</label>
                            <input type="time" class="form-control" id="time" name="time" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notlar</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">İlaç Ekle</button>
                            <a href="dashboard.php" class="btn btn-secondary">İptal</a>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>