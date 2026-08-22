<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Portfolio - Struktur Tabel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>📊 Database Portfolio</h1>
        <div id="connection-status">
            <span id="status-text">Menghubungkan ke database...</span>
        </div>
        <div id="table-container">
            <!-- Konten akan diisi oleh PHP -->
            <?php include 'db.php'; ?>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
