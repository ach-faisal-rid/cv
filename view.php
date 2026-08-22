<?php
// view.php - Menampilkan semua data portfolio

require_once 'db.php';

// Ambil semua data portfolio
$sql = "SELECT * FROM portfolio ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio - Lihat Data</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9em;
        }
        
        table th {
            background: #4a6fa5;
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e9ecef;
        }
        
        table tr:hover {
            background: #f8f9fa;
        }
        
        .gambar-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .no-image {
            color: #6c757d;
            font-style: italic;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .btn-sm {
            padding: 5px 12px;
            font-size: 12px;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        .empty-state .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .link-judul {
            color: #4a6fa5;
            text-decoration: none;
        }

        .link-judul:hover {
            text-decoration: underline;
        }

        a.thumb-link {
            display: inline-block;
            line-height: 0;
        }

        a.thumb-link:hover .gambar-thumb {
            opacity: 0.85;
            outline: 2px solid #4a6fa5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📂 Portfolio Management</h1>
        
        <!-- Navigation -->
        <div class="nav-menu">
            <a href="index.html">📝 Tambah Data</a>
            <a href="view.php" class="active">👁️ Lihat Data</a>
        </div>

        <h2>Daftar Portfolio</h2>
        
        <?php if (isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
            <div class="alert success">✅ Data berhasil dihapus!</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['status']) && $_GET['status'] == 'updated'): ?>
            <div class="alert success">✅ Data berhasil diupdate!</div>
        <?php endif; ?>

        <div class="table-container">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td>
                                    <?php if ($row['gambar'] && file_exists('uploads/' . $row['gambar'])): ?>
                                        <a href="detail.php?id=<?= $row['id'] ?>" class="thumb-link" title="Lihat detail">
                                            <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" class="gambar-thumb" alt="<?= htmlspecialchars($row['judul']) ?>">
                                        </a>
                                    <?php else: ?>
                                        <a href="detail.php?id=<?= $row['id'] ?>" class="no-image">Tidak ada</a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="detail.php?id=<?= $row['id'] ?>" class="link-judul">
                                        <strong><?= htmlspecialchars($row['judul']) ?></strong>
                                    </a>
                                </td>
                                <td><span class="badge"><?= htmlspecialchars($row['kategori']) ?></span></td>
                                <td><?= htmlspecialchars(substr($row['deskripsi'], 0, 100)) ?>...</td>
                                <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">✏️ Edit</a>
                                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">🗑️ Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="icon">📭</div>
                    <h3>Belum ada data portfolio</h3>
                    <p>Klik "Tambah Data" untuk menambahkan portfolio pertama Anda.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>