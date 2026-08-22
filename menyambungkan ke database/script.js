// JavaScript untuk interaktivitas tambahan
document.addEventListener('DOMContentLoaded', function() {
    console.log('📊 Database Portfolio Viewer loaded');

    // Deteksi status koneksi dari elemen yang ada
    const statusElement = document.querySelector('#connection-status');
    if (statusElement) {
        const statusText = statusElement.textContent || '';
        if (statusText.includes('berhasil')) {
            statusElement.className = 'success';
            statusElement.innerHTML = '✅ <strong>Database Connected</strong>';
        } else if (statusText.includes('gagal') || statusText.includes('Error')) {
            statusElement.className = 'error';
            statusElement.innerHTML = '❌ <strong>Connection Failed</strong>';
        } else {
            statusElement.className = 'info';
        }
    }

    // Tambahkan fitur collapsible untuk tabel (opsional)
    // Buat header tabel bisa diklik untuk menyembunyikan/menampilkan detail
    const tableHeaders = document.querySelectorAll('h4');
    tableHeaders.forEach(header => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', function() {
            // Cari tabel berikutnya setelah header ini
            let nextElement = this.nextElementSibling;
            let isHidden = false;
            
            // Jika tabel langsung setelah header
            if (nextElement && nextElement.tagName === 'TABLE') {
                if (nextElement.style.display === 'none') {
                    nextElement.style.display = '';
                    this.style.opacity = '1';
                    isHidden = false;
                } else {
                    nextElement.style.display = 'none';
                    this.style.opacity = '0.6';
                    isHidden = true;
                }
            }
            
            // Simpan state di localStorage
            localStorage.setItem('table_' + this.textContent.trim(), isHidden);
        });
        
        // Restore state dari localStorage
        const savedState = localStorage.getItem('table_' + header.textContent.trim());
        if (savedState === 'true') {
            const nextElement = header.nextElementSibling;
            if (nextElement && nextElement.tagName === 'TABLE') {
                nextElement.style.display = 'none';
                header.style.opacity = '0.6';
            }
        }
    });

    // Tambahkan fitur search (opsional)
    addSearchFeature();
    
    // Tambahkan fitur export ke CSV (opsional)
    addExportFeature();
});

// Fungsi untuk menambahkan fitur search
function addSearchFeature() {
    const tables = document.querySelectorAll('table');
    if (tables.length === 0) return;

    // Buat input search di atas tabel
    const searchContainer = document.createElement('div');
    searchContainer.style.cssText = `
        margin: 20px 0 20px 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    `;
    searchContainer.innerHTML = `
        <input type="text" id="tableSearch" placeholder="🔍 Cari data di tabel..." 
               style="padding: 10px 15px; border: 2px solid #dee2e6; border-radius: 8px; 
                      flex: 1; max-width: 400px; font-size: 14px;">
        <button id="clearSearch" style="padding: 10px 20px; background: #6c757d; 
                color: white; border: none; border-radius: 8px; cursor: pointer;">
            Clear
        </button>
    `;

    // Insert setelah judul h3
    const h3 = document.querySelector('h3');
    if (h3) {
        h3.parentNode.insertBefore(searchContainer, h3.nextSibling);
    }

    // Event listener untuk search
    const searchInput = document.getElementById('tableSearch');
    const clearBtn = document.getElementById('clearSearch');

    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const allRows = document.querySelectorAll('table tbody tr');
        
        allRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            if (rowText.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        // Tampilkan pesan jika tidak ada hasil
        tables.forEach(table => {
            const visibleRows = table.querySelectorAll('tbody tr[style*="display: none"]');
            const totalRows = table.querySelectorAll('tbody tr');
            const noResultMsg = table.querySelector('.no-result-msg');
            
            if (visibleRows.length === totalRows.length && totalRows.length > 0) {
                if (!noResultMsg) {
                    const msg = document.createElement('tr');
                    msg.className = 'no-result-msg';
                    msg.innerHTML = `<td colspan="100%" style="text-align: center; 
                                    padding: 20px; color: #6c757d;">
                                    <i>🔍 Tidak ada data yang cocok</i></td>`;
                    table.querySelector('tbody').appendChild(msg);
                }
            } else {
                if (noResultMsg) {
                    noResultMsg.remove();
                }
            }
        });
    });

    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('keyup'));
    });
}

// Fungsi untuk menambahkan fitur export CSV
function addExportFeature() {
    const tables = document.querySelectorAll('table');
    if (tables.length === 0) return;

    // Buat tombol export
    const exportContainer = document.createElement('div');
    exportContainer.style.cssText = `
        margin: 15px 0 20px 15px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    `;
    exportContainer.innerHTML = `
        <button id="exportCSV" style="padding: 10px 20px; background: #28a745; 
                color: white; border: none; border-radius: 8px; cursor: pointer; 
                font-size: 14px;">
            📥 Export All to CSV
        </button>
        <button id="exportJSON" style="padding: 10px 20px; background: #007bff; 
                color: white; border: none; border-radius: 8px; cursor: pointer; 
                font-size: 14px;">
            📥 Export All to JSON
        </button>
    `;

    // Insert setelah search container
    const searchContainer = document.querySelector('#tableSearch')?.parentNode;
    if (searchContainer) {
        searchContainer.parentNode.insertBefore(exportContainer, searchContainer.nextSibling);
    } else {
        const h3 = document.querySelector('h3');
        if (h3) {
            h3.parentNode.insertBefore(exportContainer, h3.nextSibling);
        }
    }

    // Event listener untuk export CSV
    document.getElementById('exportCSV').addEventListener('click', function() {
        let csv = '';
        const allTables = document.querySelectorAll('table');
        
        allTables.forEach((table, index) => {
            const tableName = table.previousElementSibling?.textContent || 'Table_' + (index + 1);
            csv += `\n"--- ${tableName.trim()} ---"\n`;
            
            // Header
            const headers = table.querySelectorAll('thead th');
            const headerRow = Array.from(headers).map(th => `"${th.textContent.trim()}"`).join(',');
            csv += headerRow + '\n';
            
            // Data
            const rows = table.querySelectorAll('tbody tr:not(.no-result-msg)');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const rowData = Array.from(cells).map(td => {
                    let text = td.textContent.trim();
                    // Jika berisi koma atau quote, wrap dengan quote
                    if (text.includes(',') || text.includes('"')) {
                        text = text.replace(/"/g, '""');
                        return `"${text}"`;
                    }
                    return text;
                });
                csv += rowData.join(',') + '\n';
            });
            csv += '\n';
        });

        // Download CSV
        downloadFile(csv, 'database_structure.csv', 'text/csv');
    });

    // Event listener untuk export JSON
    document.getElementById('exportJSON').addEventListener('click', function() {
        const data = [];
        const allTables = document.querySelectorAll('table');
        
        allTables.forEach((table, index) => {
            const tableName = table.previousElementSibling?.textContent || 'Table_' + (index + 1);
            const tableData = [];
            
            // Header
            const headers = table.querySelectorAll('thead th');
            const headerNames = Array.from(headers).map(th => th.textContent.trim());
            
            // Data
            const rows = table.querySelectorAll('tbody tr:not(.no-result-msg)');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const rowObj = {};
                cells.forEach((td, idx) => {
                    if (headerNames[idx]) {
                        rowObj[headerNames[idx]] = td.textContent.trim();
                    }
                });
                if (Object.keys(rowObj).length > 0) {
                    tableData.push(rowObj);
                }
            });
            
            data.push({
                table: tableName.replace('Tabel: ', '').trim(),
                structure: tableData
            });
        });

        const json = JSON.stringify(data, null, 2);
        downloadFile(json, 'database_structure.json', 'application/json');
    });
}

// Helper function untuk download file
function downloadFile(content, filename, mimeType) {
    const blob = new Blob([content], { type: mimeType + ';charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', filename);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}