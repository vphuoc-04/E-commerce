(function(){
    const onReady = () => {
        const form = document.querySelector('.custom-form.user-form');
        if(!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = form.querySelector('button.user-button-confirm');
            
            // Set loading state
            btn?.setAttribute('disabled', 'true');
            btn?.classList.replace('active', 'disabled');

            try {
                const formData = new FormData(form);
                const resp = await fetch(form.action, { 
                    method: form.method || 'POST', 
                    body: formData 
                });
                
                const payload = resp.headers.get('content-type')?.includes('application/json') 
                    ? await resp.json() 
                    : { status: resp.ok ? 'success' : 'error' };

                if(resp.ok || payload?.status === 'success' || payload?.code === 200) {
                    // Sử dụng message từ backend, fallback message mặc định
                    const successMessage = payload?.message || 'Thao tác thành công';
                    
                    window.CustomAlert?.show({ 
                        type: 'success', 
                        title: 'Thành công', 
                        message: successMessage,
						duration: 4000,  // 3 giây cho thành công
                        animationIn: 300, // 0.3s xuất hiện
                        animationOut: 300 // 0.3s biến mất
                    });
                    
                    document.querySelector('.custom-sheet.user-sheet')?.close();
                    window.TableReloader?.reload?.();
                    form.reset();
                } else {
                    // Error case - có thể có message từ backend
                    const errorMessage = payload?.message || 'Có lỗi xảy ra';
                    handleFormErrors(form, payload?.errors || {});
                    
                    // Hiển thị message lỗi tổng quan nếu có
                    if(payload?.message) {
                        window.CustomAlert?.show({
                            type: 'error',
                            title: 'Lỗi',
                            message: errorMessage
                        });
                    }
                }
            } catch(err) {
                console.error('Submit error:', err);
                window.CustomAlert?.show({
                    type: 'error',
                    title: 'Lỗi',
                    message: 'Lỗi kết nối đến server'
                });
            } finally {
                // Reset button state
                btn?.removeAttribute('disabled');
                btn?.classList.replace('disabled', 'active');
                window.CustomButton?.stopLoading?.(btn);
            }
        });
    };

    const handleFormErrors = (form, errors) => {
        const setFieldError = (name, message) => {
            const el = form.querySelector(`[name="${name}"]`);
            if(!el) return;
            
            const span = el.nextElementSibling?.classList?.contains('text-danger') 
                ? el.nextElementSibling 
                : null;
            
            if(span) span.textContent = message;
            el.classList.add('is-invalid');
        };

        // Reset all errors first
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.text-danger').forEach(el => el.textContent = '');

        // Apply new errors
        Object.entries(errors).forEach(([field, message]) => {
            setFieldError(field, message);
        });
    };

    document.readyState === 'loading' 
        ? document.addEventListener('DOMContentLoaded', onReady)
        : onReady();
})();