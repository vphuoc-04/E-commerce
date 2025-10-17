// views/customs/js/custom-alert-dialog.js

class CustomAlertDialog {
    static currentDialog = null;
    static confirmCallback = null;

    static show(message, onConfirm, options = {}) {
        const {
            title = 'Xác nhận',
            closeLabel = 'Hủy bỏ',
            confirmLabel = 'Xác nhận'
        } = options;

        // Kiểm tra xem dialog đã tồn tại chưa
        let dialog = document.getElementById('custom-alert-dialog');
        
        if (!dialog) {
            console.error('CustomAlertDialog not found in DOM');
            return;
        }

        // Cập nhật nội dung
        const titleEl = dialog.querySelector('.custom-alert-title');
        const messageEl = dialog.querySelector('.custom-alert-description');
        const cancelBtn = dialog.querySelector('.custom-alert-cancel');
        const confirmBtn = dialog.querySelector('.custom-alert-confirm');

        if (titleEl) titleEl.textContent = title;
        if (messageEl) messageEl.textContent = message;
        if (cancelBtn) cancelBtn.textContent = closeLabel;
        if (confirmBtn) confirmBtn.textContent = confirmLabel;

        // Gắn sự kiện
        this.confirmCallback = onConfirm;

        const overlay = dialog.querySelector('.custom-alert-overlay');
        
        // Xóa sự kiện cũ
        cancelBtn.onclick = null;
        confirmBtn.onclick = null;
        overlay.onclick = null;

        // Gắn sự kiện mới
        cancelBtn.onclick = () => this.close();
        confirmBtn.onclick = () => this.confirm();
        overlay.onclick = () => this.close();

        // Hiển thị dialog
        dialog.style.display = 'block';
        this.currentDialog = dialog;

        // Thêm sự kiện ESC để đóng
        this.escHandler = (e) => {
            if (e.key === 'Escape') this.close();
        };
        document.addEventListener('keydown', this.escHandler);
    }

    static close() {
        if (this.currentDialog) {
            this.currentDialog.style.display = 'none';
            this.currentDialog = null;
            this.confirmCallback = null;
            
            // Xóa sự kiện ESC
            if (this.escHandler) {
                document.removeEventListener('keydown', this.escHandler);
                this.escHandler = null;
            }
        }
    }

    static confirm() {
        if (this.confirmCallback && typeof this.confirmCallback === 'function') {
            this.confirmCallback();
        }
        this.close();
    }
}

// Hàm global để sử dụng
function showCustomAlert(message, onConfirm, options = {}) {
    CustomAlertDialog.show(message, onConfirm, options);
}

// Khởi tạo sự kiện khi trang load
document.addEventListener('DOMContentLoaded', function() {
    const dialog = document.getElementById('custom-alert-dialog');
    if (dialog) {
        // Đảm bảo dialog bắt đầu với display: none
        dialog.style.display = 'none';
    }
});