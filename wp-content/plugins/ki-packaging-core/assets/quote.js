/* KI Packaging Core: quotation form submit (progressive enhancement). */
(function () {
	'use strict';

	function setError(field, msg) {
		var wrap = field.closest('.kiq-field');
		if (!wrap) return;
		wrap.classList.toggle('has-error', !!msg);
		var note = wrap.querySelector('.kiq-err');
		if (msg) {
			if (!note) { note = document.createElement('em'); note.className = 'kiq-err'; wrap.appendChild(note); }
			note.textContent = msg;
			field.setAttribute('aria-invalid', 'true');
		} else {
			if (note) note.remove();
			field.removeAttribute('aria-invalid');
		}
	}

	function validate(form) {
		var ok = true;
		form.querySelectorAll('[required]').forEach(function (el) {
			var bad = !el.value.trim();
			if (!bad && el.type === 'email') bad = !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(el.value);
			setError(el, bad ? (el.type === 'email' ? 'Enter a valid email address.' : 'This field is required.') : '');
			if (bad) ok = false;
		});
		return ok;
	}

	function init(form) {
		if (form.__kiq) return;
		form.__kiq = true;
		form.addEventListener('input', function (e) { if (e.target.closest('.kiq-field')) setError(e.target, ''); });
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var status = form.querySelector('.kiq-status');
			var btn = form.querySelector('.kiq-submit');
			status.className = 'kiq-status';
			status.textContent = '';
			if (!validate(form)) {
				status.classList.add('is-error');
				status.textContent = 'Please complete the highlighted fields.';
				var first = form.querySelector('.has-error input, .has-error select');
				if (first) first.focus();
				return;
			}
			btn.disabled = true;
			form.classList.add('is-sending');
			fetch(KIQ.ajax, { method: 'POST', body: new FormData(form), credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function (res) {
					if (res && res.success) {
						var wa = KIQ.whatsapp ? '<a class="btn btn-whatsapp" href="' + KIQ.whatsapp + '" target="_blank" rel="noopener">Chat on WhatsApp</a>' : '';
						var ref = res.data.ref ? '<p class="kiq-ref">Reference <strong>' + res.data.ref + '</strong></p>' : '';
						form.innerHTML = '<div class="kiq-success"><div class="kiq-check"><svg viewBox="0 0 52 52" width="64" height="64" aria-hidden="true"><circle cx="26" cy="26" r="24" fill="none" stroke="currentColor" stroke-width="3"/><path d="M14 27l8 8 16-17" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg></div><h3>Request received</h3><p>' + res.data.message + '</p>' + ref + '<p class="kiq-next">Our team will contact you with a quotation shortly.</p>' + wa + '</div>';
						form.dispatchEvent(new CustomEvent('ki:quote:sent', { bubbles: true }));
					} else {
						var d = (res && res.data) || {};
						if (d.fields) Object.keys(d.fields).forEach(function (k) { var el = form.querySelector('[name="' + k + '"]'); if (el) setError(el, d.fields[k]); });
						status.classList.add('is-error');
						status.textContent = d.message || 'Something went wrong. Please try again.';
						btn.disabled = false;
						form.classList.remove('is-sending');
					}
				})
				.catch(function () {
					status.classList.add('is-error');
					status.textContent = 'Network error. Please check your connection and try again.';
					btn.disabled = false;
					form.classList.remove('is-sending');
				});
		});
	}

	function boot() { document.querySelectorAll('form[data-kiq]').forEach(init); }
	document.addEventListener('DOMContentLoaded', boot);
	window.kiqInit = boot;
})();
