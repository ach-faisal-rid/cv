// JavaScript untuk validasi form dan interaktivitas

document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Validasi form
    const form = document.getElementById('portfolioForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm(e)) {
                e.preventDefault();
            }
        });
    }
    
    // 2. Preview gambar
    const fileInput = document.getElementById('gambar');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            previewImage(e);
        });
    }
    
    // 3. Counter karakter untuk deskripsi
    const deskripsi = document.getElementById('deskripsi');
    if (deskripsi) {
        deskripsi.addEventListener('input', function() {
            const maxLength = 500;
            const currentLength = this.value.length;
            const counter = document.getElementById('charCounter');
            
            if (!counter) {
                const counterDiv = document.createElement('div');
                counterDiv.id = 'charCounter';
                counterDiv.style.cssText = 'text-align: right; font-size: 12px; color: #6c757d; margin-top: 5px;';
                this.parentNode.appendChild(counterDiv);
            }
            
            const counterElement = document.getElementById('charCounter');
            counterElement.textContent = `${currentLength}/${maxLength} karakter`;
            
            if (currentLength > maxLength) {
                counterElement.style.color = '#dc3545';
                this.value = this.value.substring(0, maxLength);
            } else if (currentLength > maxLength * 0.8) {
                counterElement.style.color = '#ffc107';
            } else {
                counterElement.style.color = '#6c757d';
            }
        });
    }
});

// Fungsi validasi form
function validateForm(e) {
    const form = document.getElementById('portfolioForm');
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        // Hapus error sebelumnya
        input.classList.remove('error');
        const errorMsg = input.parentNode.querySelector('.error-message');
        if (errorMsg) {
            errorMsg.remove();
        }
        
        // Validasi
        if (!input.value.trim()) {
            input.classList.add('error');
            showError(input, 'Field ini wajib diisi');
            isValid = false;
        }
    });
    
    // Validasi khusus untuk judul (min 3 karakter)
    const judul = document.getElementById('judul');
    if (judul && judul.value.trim().length < 3) {
        judul.classList.add('error');
        showError(judul, 'Judul minimal 3 karakter');
        isValid = false;
    }
    
    // Validasi khusus untuk deskripsi (min 10 karakter)
    const deskripsi = document.getElementById('deskripsi');
    if (deskripsi && deskripsi.value.trim().length < 10) {
        deskripsi.classList.add('error');
        showError(deskripsi, 'Deskripsi minimal 10 karakter');
        isValid = false;
    }
    
    if (!isValid) {
        // Scroll ke error pertama
        const firstError = form.querySelector('.error');
        if (firstError) {
            firstError.focus();
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
    
    return isValid;
}

// Fungsi menampilkan pesan error di bawah input
function showError(input, message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.cssText = 'color: #dc3545; font-size: 13px; margin-top: 5px;';
    errorDiv.textContent = '❌ ' + message;
    input.parentNode.appendChild(errorDiv);
}

// Fungsi preview gambar
function previewImage(e) {
    const file = e.target.files[0];
    if (!file) return;
    
    // Validasi tipe file
    const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!validTypes.includes(file.type)) {
        showSwalError('Format gambar tidak didukung! Gunakan JPG, PNG, atau GIF.');
        e.target.value = '';
        return;
    }
    
    // Validasi ukuran file (2MB)
    if (file.size > 2 * 1024 * 1024) {
        showSwalError('Ukuran gambar maksimal 2MB!');
        e.target.value = '';
        return;
    }
    
    // Tampilkan preview
    const reader = new FileReader();
    reader.onload = function(e) {
        // Hapus preview lama jika ada
        const existingPreview = document.querySelector('.image-preview');
        if (existingPreview) {
            existingPreview.remove();
        }
        
        // Buat preview baru
        const previewDiv = document.createElement('div');
        previewDiv.className = 'image-preview';
        previewDiv.style.cssText = 'margin-top: 10px;';
        
        const img = document.createElement('img');
        img.src = e.target.result;
        img.style.cssText = 'max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #dee2e6;';
        
        const removeBtn = document.createElement('button');
        removeBtn.textContent = '✕ Hapus';
        removeBtn.style.cssText = 'display: block; margin-top: 5px; padding: 5px 10px; background: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 12px;';
        removeBtn.onclick = function() {
            document.getElementById('gambar').value = '';
            previewDiv.remove();
        };
        
        previewDiv.appendChild(img);
        previewDiv.appendChild(removeBtn);
        document.getElementById('gambar').parentNode.appendChild(previewDiv);
    };
    reader.readAsDataURL(file);
}

// Fungsi untuk reset form
document.addEventListener('DOMContentLoaded', function() {
    const resetBtn = document.querySelector('button[type="reset"]');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            // Hapus preview gambar
            const preview = document.querySelector('.image-preview');
            if (preview) {
                preview.remove();
            }
            
            // Hapus error messages
            document.querySelectorAll('.error-message').forEach(el => el.remove());
            document.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
            
            // Reset counter karakter
            const counter = document.getElementById('charCounter');
            if (counter) {
                counter.textContent = '0/500 karakter';
                counter.style.color = '#6c757d';
            }
        });
    }
});