(function(){
function showDialog({ title = 'Thành công', message = '', duration = 2000 } = {}){
	let overlay = document.querySelector('.custom-alert-overlay');
	if(!overlay){
		overlay = document.createElement('div');
		overlay.className = 'custom-alert-overlay';
		document.body.appendChild(overlay);
	}
	const dialog = document.createElement('div');
	dialog.className = 'custom-alert-dialog';
	dialog.innerHTML = `
		<div class="checkmark"><div class="tick"></div></div>
		<div class="dialog-title">${title}</div>
		<div class="dialog-message">${message}</div>
	`;
	overlay.innerHTML = '';
	overlay.appendChild(dialog);
	overlay.classList.add('show');
	setTimeout(() => { dialog.classList.add('show'); }, 10);
	const close = () => {
		dialog.classList.remove('show');
		setTimeout(() => { overlay.classList.remove('show'); overlay.innerHTML=''; }, 200);
	};
	if(duration > 0){ setTimeout(close, duration); }
	return { close };
}

window.CustomAlert = {
	show: function({ type = 'success', title = 'Thành công', message = '', duration = 2000 } = {}){
		// type kept for future styling variations
		return showDialog({ title, message, duration });
	}
};
})();


