<?php
// Konfigurasi database
$host = "localhost";
$user = "root";
$pass = "root";
$db   = "portfolio";

// Koneksi ke database
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    $error_message = "Koneksi gagal: " . mysqli_connect_error();
    echo "<div class='error-message'><strong>❌ Error:</strong> " . $error_message . "</div>";
    return;
}

// Tampilkan pesan sukses
echo "<div id='connection-status' class='success'>✅ Koneksi berhasil!</div>";

// Query untuk mendapatkan daftar tabel
$result = mysqli_query($conn, "SHOW TABLES");

// Jika tidak ada tabel
if (mysqli_num_rows($result) == 0) {
    echo "<p style='color: #6c757d; margin: 20px 0;'>Tidak ada tabel dalam database.</p>";
    mysqli_close($conn);
    return;
}

// Tampilkan judul
echo "<h3>📚 Daftar Tabel dan Strukturnya</h3>";

// Loop melalui setiap tabel
while ($row = mysqli_fetch_array($result)) {
    $nama_tabel = $row[0];
    
    // Tampilkan nama tabel
    echo "<h4>Tabel: " . htmlspecialchars($nama_tabel) . "</h4>";
    
    // Ambil struktur tabel
    $data_result = mysqli_query($conn, "DESCRIBE " . mysqli_real_escape_string($conn, $nama_tabel));
    
    if ($data_result && mysqli_num_rows($data_result) > 0) {
        // Mulai tabel HTML
        echo "<table>
                <thead>
                    <tr>";
        
        // Buat header kolom
        $fields = mysqli_fetch_fields($data_result);
        foreach ($fields as $field) {
            echo "<th>" . ucfirst($field->name) . "</th>";
        }
        echo "   </tr>
                </thead>
                <tbody>";
        
        // Tampilkan data struktur
        while ($baris = mysqli_fetch_assoc($data_result)) {
            echo "<tr>";
            foreach ($baris as $isi_kolom) {
                // Jika nilai NULL atau kosong, tampilkan 'None' dengan style
                if ($isi_kolom === null || $isi_kolom === '') {
                    echo "<td><i>None</i></td>";
                } else {
                    echo "<td>" . htmlspecialchars($isi_kolom) . "</td>";
                }
            }
            echo "</tr>";
        }
        
        echo "  </tbody>
            </table>";
    } else {
        echo "<p style='color: gray; margin-left: 15px;'>⚠️ Gagal membaca struktur tabel ini.</p>";
    }
}

// Tutup koneksi
mysqli_close($conn);
?>