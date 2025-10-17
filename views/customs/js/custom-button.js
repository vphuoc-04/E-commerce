document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.loading button');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            if (!btn.disabled) {
                btn.parentElement.classList.add('loading');
                if(!btn.dataset.originalText){
                    btn.dataset.originalText = btn.textContent.trim();
                }
                btn.innerHTML = '<div class="spinner"></div>';
            }
        });
    });

    window.CustomButton = {
        stopLoading: function(button){
            if(!button) return;
            const wrapper = button.parentElement;
            if(wrapper && wrapper.classList.contains('loading')){
                wrapper.classList.remove('loading');
            }
            const original = button.dataset.originalText || button.textContent || '';
            if(original){ button.textContent = original; }
        }
    };
});
