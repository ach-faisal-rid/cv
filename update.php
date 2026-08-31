<?php
// update.php - Proses update data portfolio

require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: view.php");
    exit();
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id <= 0) {
    header("Location: view.php?status=error&message=" . urlencode("ID tidak valid"));
    exit();
}

// Ambil data lama
$sql = "SELECT * FROM portfolio WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $sql);
$old_data = mysqli_fetch_assoc($result);

if (!$old_data) {
    header("Location: view.php?status=error&message=" . urlencode("Data tidak ditemukan"));
    exit();
}

$judul = mysqli_real_escape_string($conn, $_POST['judul']);
$kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
$deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
$gambar = $old_data['gambar'];

$errors = [];

if (empty($judul)) {
    $errors[] = "Judul harus diisi";
}

if (empty($kategori)) {
    $errors[] = "Kategori harus dipilih";
}

if (empty($deskripsi)) {
    $errors[] = "Deskripsi harus diisi";
}

// Proses upload gambar baru (opsional)
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $filename = $_FILES['gambar']['name'];
    $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $filesize = $_FILES['gambar']['size'];

    if (!in_array($filetype, $allowed)) {
        $errors[] = "Format gambar tidak didukung (hanya JPG, PNG, GIF)";
    }

    if ($filesize > 2 * 1024 * 1024) {
        $errors[] = "Ukuran gambar maksimal 2MB";
    }

    if (empty($errors)) {
        $new_filename = time() . '_' . uniqid() . '.' . $filetype;
        $upload_path = 'uploads/' . $new_filename;

        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
            // Hapus gambar lama jika ada
            if ($gambar && file_exists('uploads/' . $gambar)) {
                unlink('uploads/' . $gambar);
            }
            $gambar = $new_filename;
        } else {
            $errors[] = "Gagal mengupload gambar";
        }
    }
}

if (empty($errors)) {
    $sql = "UPDATE portfolio 
            SET judul = '$judul', 
                kategori = '$kategori', 
                deskripsi = '$deskripsi', 
                gambar = '$gambar' 
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: view.php?status=updated");
        exit();
    } else {
        $errors[] = "Gagal mengupdate data: " . mysqli_error($conn);
    }
}

if (!empty($errors)) {
    $error_message = implode("\\n", $errors);
    header("Location: edit.php?id=$id&status=error&message=" . urlencode($error_message));
    exit();
}

mysqli_close($conn);
?>
