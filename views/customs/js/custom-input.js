(function(){
const onReady = () => {
    const form = document.querySelector('.custom-form.user-form');
	if(!form) return;
	const btn = form.querySelector('button.user-button-confirm');
	if(!btn) return;
    const getRequiredFields = () => Array.from(form.querySelectorAll('input[required], select[required], textarea[required]'));
	const getErrorSpan = (el) => {
		// span is rendered right after the input/select when required
		let sibling = el.nextElementSibling;
		return sibling && sibling.classList.contains('text-danger') ? sibling : null;
	};
	const parseRegexFromAttr = (patternAttr) => {
		if(!patternAttr) return null;
		// Accept either HTML pattern (no slashes) or JS-like \/...\/
		if(patternAttr.startsWith('/') && patternAttr.lastIndexOf('/') > 0){
			const last = patternAttr.lastIndexOf('/');
			const body = patternAttr.slice(1, last);
			const flags = patternAttr.slice(last+1);
			try { return new RegExp(body, flags); } catch(e){ return null; }
		}
		try { return new RegExp(`^(?:${patternAttr})$`); } catch(e){ return null; }
	};
	const validateInputField = (el) => {
		let valid = true;
		let message = '';
		const value = (el.value ?? '').trim();
		if(el.required && value === ''){
			valid = false;
			message = 'Vui lòng nhập trường này';
		}
		if(valid && el.getAttribute('minlength')){
			const min = parseInt(el.getAttribute('minlength'), 10);
			if(!Number.isNaN(min) && value.length < min){
				valid = false; message = `Tối thiểu ${min} ký tự`;
			}
		}
		if(valid && el.getAttribute('maxlength')){
			const max = parseInt(el.getAttribute('maxlength'), 10);
			if(!Number.isNaN(max) && value.length > max){
				valid = false; message = `Tối đa ${max} ký tự`;
			}
		}
		if(valid && el.getAttribute('pattern')){
			const rx = parseRegexFromAttr(el.getAttribute('pattern'));
			if(rx && !rx.test(value)){
				valid = false; message = el.getAttribute('title') || 'Giá trị không hợp lệ';
			}
		}
		return { valid, message };
	};
	const validateSelectField = (el) => {
		let valid = true;
		let message = '';
		if(el.required && (el.value === '' || el.value == null)){
			valid = false;
			message = 'Vui lòng chọn một mục';
		}
		return { valid, message };
	};
	const validateFieldAndRender = (el) => {
		let res;
		if(el.tagName.toLowerCase() === 'select') res = validateSelectField(el); else res = validateInputField(el);
		const span = getErrorSpan(el);
		if(span){ span.textContent = res.valid ? '' : res.message; }
		el.classList.toggle('is-invalid', !res.valid);
		return res.valid;
	};
    const validateForm = () => {
        let ok = true;
        const fields = getRequiredFields();
        for(const el of fields){
            if(!validateFieldAndRender(el)) { ok = false; }
        }
		btn.disabled = !ok;
		btn.classList.toggle('disabled', !ok);
		btn.classList.toggle('active', ok);
		return ok;
	};
	// wire events
    getRequiredFields().forEach(el => {
        const handler = () => validateForm();
        ['input','change','blur','keyup'].forEach(evt => el.addEventListener(evt, handler));
    });
	// initial state
	validateForm();
};
if(document.readyState === 'loading'){
	document.addEventListener('DOMContentLoaded', onReady);
}else{
	onReady();
}
})();
