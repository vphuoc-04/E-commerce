document.addEventListener("DOMContentLoaded", function () {
    const sheets = document.querySelectorAll(".custom-sheet");

    sheets.forEach((sheet) => {
        const closeBtn = sheet.querySelector(".close");

        // Tạo overlay nếu chưa có
        let overlay = document.querySelector(".sheet-overlay");
            if (!overlay) {
            overlay = document.createElement("div");
            overlay.className = "sheet-overlay";
            document.body.appendChild(overlay);
        }

        // Đóng sheet
        const closeSheet = () => {
            sheet.classList.remove("active");
            overlay.classList.remove("active");
            document.body.style.overflow = ""; // khôi phục cuộn trang
        };

        // Mở sheet
        const openSheet = () => {
            sheet.classList.add("active");
            overlay.classList.add("active");
            document.body.style.overflow = "hidden"; // khóa cuộn khi sheet bật
        };

        // Khi click nút đóng hoặc overlay thì đóng đóng
        closeBtn?.addEventListener("click", closeSheet);
        // overlay.addEventListener("click", closeSheet);

        // Cho phép mở bằng JS từ bên ngoài
        sheet.open = openSheet;
        sheet.close = closeSheet;
    });
});
