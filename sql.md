```sql
CREATE TABLE portfolio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255),
    kategori VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## project ini nanti hasilnya seperti apa ?
### bagian 1 cuma create dan view
```
portfolio-project/
├── index.html          # Form tambah data
├── create.php          # Proses simpan data
├── view.php           # Tampilkan semua data
├── edit.php           # (Bisa ditambahkan untuk update)
├── delete.php         # (Bisa ditambahkan untuk hapus)
├── style.css          # Styling
├── script.js          # JavaScript
└── uploads/           # Folder untuk menyimpan gambar
```