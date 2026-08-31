<?php
// delete.php - Hapus data portfolio

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
    header("Location: view.php?status=error&message=" . urlencode("Data tidak ditemukan"));
    exit();
}

// Hapus file gambar jika ada
if ($data['gambar'] && file_exists('uploads/' . $data['gambar'])) {
    unlink('uploads/' . $data['gambar']);
}

$delete_sql = "DELETE FROM portfolio WHERE id = $id";

if (mysqli_query($conn, $delete_sql)) {
    header("Location: view.php?status=deleted");
} else {
    header("Location: view.php?status=error&message=" . urlencode("Gagal menghapus data"));
}

mysqli_close($conn);
exit();
