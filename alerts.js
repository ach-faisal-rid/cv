// alerts.js - SweetAlert2 untuk semua halaman

const STATUS_MESSAGES = {
    success: { icon: 'success', title: 'Berhasil!', text: 'Data berhasil disimpan!' },
    updated: { icon: 'success', title: 'Berhasil!', text: 'Data berhasil diupdate!' },
    deleted: { icon: 'success', title: 'Berhasil!', text: 'Data berhasil dihapus!' },
    error: { icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan.' }
};

document.addEventListener('DOMContentLoaded', function() {
    showAlertFromURL();
    initDeleteButtons();
});

function showAlertFromURL() {
    if (typeof Swal === 'undefined') return;

    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const message = urlParams.get('message');

    if (!status) return;

    const config = STATUS_MESSAGES[status] || { icon: 'info', title: 'Info', text: 'Selesai.' };
    const text = message || config.text;

    if (status === 'error') {
        Swal.fire({
            icon: 'error',
            title: config.title,
            text: text,
            confirmButtonText: 'OK'
        });
    } else {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: config.icon,
            title: text,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    }

    urlParams.delete('status');
    urlParams.delete('message');
    const newSearch = urlParams.toString();
    const newUrl = window.location.pathname + (newSearch ? '?' + newSearch : '');
    window.history.replaceState({}, document.title, newUrl);
}

function initDeleteButtons() {
    if (typeof Swal === 'undefined') return;

    document.querySelectorAll('.btn-delete').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const deleteUrl = this.href;

            Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });
    });
}

function showSwalError(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Perhatian',
            text: message,
            confirmButtonText: 'OK'
        });
    } else {
        alert(message);
    }
}
