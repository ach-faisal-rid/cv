<?php
// create.php - Proses menyimpan data

require_once 'db.php';

// Cek apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data dari form
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    // Validasi sederhana
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
    
    // Proses upload gambar
    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['gambar']['name'];
        $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $filesize = $_FILES['gambar']['size'];
        
        // Validasi file
        if (!in_array($filetype, $allowed)) {
            $errors[] = "Format gambar tidak didukung (hanya JPG, PNG, GIF)";
        }
        
        if ($filesize > 2 * 1024 * 1024) { // 2MB
            $errors[] = "Ukuran gambar maksimal 2MB";
        }
        
        // Jika tidak ada error, upload file
        if (empty($errors)) {
            $new_filename = time() . '_' . uniqid() . '.' . $filetype;
            $upload_path = 'uploads/' . $new_filename;
            
            // Buat folder uploads jika belum ada
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                $gambar = $new_filename;
            } else {
                $errors[] = "Gagal mengupload gambar";
            }
        }
    }
    
    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {
        $sql = "INSERT INTO portfolio (judul, kategori, deskripsi, gambar) 
                VALUES ('$judul', '$kategori', '$deskripsi', '$gambar')";
        
        if (mysqli_query($conn, $sql)) {
            // Redirect dengan pesan sukses
            header("Location: index.html?status=success&message=" . urlencode("Data berhasil disimpan!"));
            exit();
        } else {
            $errors[] = "Gagal menyimpan data: " . mysqli_error($conn);
        }
    }
    
    // Jika ada error, redirect kembali dengan pesan error
    if (!empty($errors)) {
        $error_message = implode("\\n", $errors);
        header("Location: index.html?status=error&message=" . urlencode($error_message));
        exit();
    }
}

mysqli_close($conn);
?>