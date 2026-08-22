<?php
// db.php - Koneksi database (dipakai bersama)

$host = "localhost";
$user = "root";
$pass = "root";
$db   = "portfolio";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
