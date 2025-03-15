// Fungsi untuk menampilkan konfirmasi sebelum menghapus
function confirmDelete() {
    return confirm("Are you sure you want to delete this?");
}

// Fungsi untuk menampilkan notifikasi
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Contoh penggunaan notifikasi
document.addEventListener('DOMContentLoaded', () => {
    const successMessage = document.querySelector('.success-message');
    if (successMessage) {
        showNotification(successMessage.textContent, 'success');
    }

    const errorMessage = document.querySelector('.error-message');
    if (errorMessage) {
        showNotification(errorMessage.textContent, 'error');
    }
});
