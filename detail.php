<?php
// detail.php - Menampilkan detail satu portfolio

require_once 'db.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: view.php");
    exit();
}

$sql = "SELECT * FROM portfolio WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: view.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['judul']) ?> - Detail Portfolio</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .detail-container {
            margin-top: 10px;
        }

        .detail-header {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 20px;
        }

        .detail-gambar {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            background: #f1f3f5;
            flex-shrink: 0;
        }

        .no-image-small {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f3f5;
            border-radius: 8px;
            color: #6c757d;
            font-size: 11px;
            text-align: center;
            flex-shrink: 0;
        }

        .detail-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 8px 0 0;
            color: #6c757d;
            font-size: 0.9em;
        }

        .detail-deskripsi {
            line-height: 1.8;
            color: #222;
            font-size: 1.1em;
            white-space: pre-wrap;
            margin: 10px 0 30px;
            padding: 18px 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #4a6fa5;
        }

        .detail-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .link-judul {
            color: #4a6fa5;
            text-decoration: none;
        }

        .link-judul:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📂 Portfolio Management</h1>

        <div class="nav-menu">
            <a href="index.html">📝 Tambah Data</a>
            <a href="view.php">👁️ Lihat Data</a>
        </div>

        <div class="detail-container">
            <a href="view.php" style="display:inline-block; margin-bottom:15px; color:#4a6fa5; text-decoration:none;">← Kembali ke daftar</a>

            <div class="detail-header">
                <?php if ($data['gambar'] && file_exists('uploads/' . $data['gambar'])): ?>
                    <img src="uploads/<?= htmlspecialchars($data['gambar']) ?>" class="detail-gambar" alt="<?= htmlspecialchars($data['judul']) ?>">
                <?php else: ?>
                    <div class="no-image-small">No img</div>
                <?php endif; ?>

                <div>
                    <h2 style="margin: 0;"><?= htmlspecialchars($data['judul']) ?></h2>
                    <div class="detail-meta">
                        <span class="badge"><?= htmlspecialchars($data['kategori']) ?></span>
                        <span>📅 <?= date('d/m/Y H:i', strtotime($data['created_at'])) ?></span>
                        <span>ID: <?= $data['id'] ?></span>
                    </div>
                </div>
            </div>

            <h3 style="margin-bottom: 10px; color: #333;">Deskripsi</h3>
            <div class="detail-deskripsi"><?= nl2br(htmlspecialchars($data['deskripsi'])) ?></div>

            <div class="detail-actions">
                <a href="edit.php?id=<?= $data['id'] ?>" class="btn btn-success">✏️ Edit</a>
                <a href="delete.php?id=<?= $data['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">🗑️ Hapus</a>
                <a href="view.php" class="btn btn-secondary">📋 Kembali</a>
            </div>
        </div>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>
