document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.loading button');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            if (!btn.disabled) {
                btn.parentElement.classList.add('loading');

                btn.innerHTML = '<div class="spinner"></div>';
            }
        });
    });
});
