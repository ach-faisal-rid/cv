<?php
// edit.php - Form edit portfolio

require_once 'db.php';

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

mysqli_close($conn);

$kategori_list = [
    'Web Development',
    'Mobile Apps',
    'UI/UX Design',
    'Graphic Design',
    'Photography',
    'Other',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Portfolio - <?= htmlspecialchars($data['judul']) ?></title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .current-image {
            margin-top: 10px;
        }

        .current-image img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #dee2e6;
        }

        .current-image p {
            font-size: 13px;
            color: #6c757d;
            margin-top: 6px;
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

        <div class="form-container">
            <h2>Edit Portfolio</h2>

            <form id="portfolioForm" action="update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $data['id'] ?>">

                <div class="form-group">
                    <label for="judul">Judul Portfolio <span class="required">*</span></label>
                    <input type="text" id="judul" name="judul" value="<?= htmlspecialchars($data['judul']) ?>" placeholder="Masukkan judul portfolio" required>
                </div>

                <div class="form-group">
                    <label for="kategori">Kategori <span class="required">*</span></label>
                    <select id="kategori" name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($kategori_list as $kategori): ?>
                            <option value="<?= htmlspecialchars($kategori) ?>" <?= $data['kategori'] === $kategori ? 'selected' : '' ?>>
                                <?= htmlspecialchars($kategori) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi <span class="required">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" placeholder="Tulis deskripsi portfolio..." required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="gambar">Gambar</label>
                    <?php if ($data['gambar'] && file_exists('uploads/' . $data['gambar'])): ?>
                        <div class="current-image">
                            <img src="uploads/<?= htmlspecialchars($data['gambar']) ?>" alt="Gambar saat ini">
                            <p>Gambar saat ini. Upload file baru jika ingin mengganti.</p>
                        </div>
                    <?php else: ?>
                        <p class="helper-text" style="margin-bottom: 8px;">Belum ada gambar.</p>
                    <?php endif; ?>
                    <input type="file" id="gambar" name="gambar" accept="image/*">
                    <small class="helper-text">Format: JPG, PNG, GIF (Max 2MB)</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                    <a href="detail.php?id=<?= $data['id'] ?>" class="btn btn-secondary">↩️ Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script src="alerts.js"></script>
    <script src="script.js"></script>
</body>
</html>
