(function(){
const onReady = () => {
    const form = document.querySelector('.custom-form.user-form');
	if(!form) return;
	const btn = form.querySelector('button.user-button-confirm');
    const getRequiredFields = () => Array.from(form.querySelectorAll('input[required], select[required], textarea[required]'));
	const getErrorSpan = (el) => {
		let sibling = el.nextElementSibling;
		return sibling && sibling.classList.contains('text-danger') ? sibling : null;
	};
	const validateSelect = (el) => {
		let valid = true;
		let message = '';
		if(el.required && (el.value === '' || el.value == null)){
			valid = false; message = 'Vui lòng chọn một mục';
		}
		const span = getErrorSpan(el);
		if(span){ span.textContent = valid ? '' : message; }
		el.classList.toggle('is-invalid', !valid);
		return valid;
	};
    const validateForm = () => {
        let ok = true;
        const fields = getRequiredFields();
        for(const el of fields){
            if(el.tagName.toLowerCase() === 'select'){
                if(!validateSelect(el)) ok = false;
            }
        }
		if(btn){
			btn.disabled = !ok;
			btn.classList.toggle('disabled', !ok);
			btn.classList.toggle('active', ok);
		}
		return ok;
	};
    form.querySelectorAll('select').forEach(sel => {
		sel.addEventListener('change', validateForm);
		sel.addEventListener('blur', validateForm);
	});
	validateForm();
};
if(document.readyState === 'loading'){
	document.addEventListener('DOMContentLoaded', onReady);
}else{
	onReady();
}
})();
