(function () {
	'use strict';

	var activateBtn = document.getElementById('cb-ppg-activate-btn');
	var installBtn = document.getElementById('cb-ppg-install-btn');

	function setLoading(btn, loadingText) {
		btn.disabled = true;
		btn.innerHTML = '';

		var spinner = document.createElement('span');
		spinner.className = 'cb-ppg__spinner';
		btn.appendChild(spinner);
		btn.appendChild(document.createTextNode(loadingText));
	}

	function resetButton(btn, text) {
		btn.disabled = false;
		btn.innerHTML = '';
		btn.textContent = text;
	}

	function ajaxCall(btn, action, loadingText, defaultText, onSuccess) {
		setLoading(btn, loadingText);

		var data = new FormData();
		data.append('action', action);
		data.append('nonce', cookiebot_ppg.nonce);

		fetch(cookiebot_ppg.ajax_url, { method: 'POST', body: data, credentials: 'same-origin' })
			.then(function (response) {
				return response.json();
			})
			.then(function (result) {
				if (result.success) {
					onSuccess();
					return;
				}
				resetButton(btn, defaultText);
			})
			.catch(function () {
				resetButton(btn, defaultText);
			});
	}

	function bindActivate(btn) {
		btn.addEventListener('click', function () {
			ajaxCall(
				btn,
				'cookiebot_activate_ppg',
				cookiebot_ppg.i18n.activating,
				cookiebot_ppg.i18n.activate,
				function () {
					window.location.href = cookiebot_ppg.redirect_url;
				}
			);
		});
	}

	if (activateBtn) {
		bindActivate(activateBtn);
	}

	if (installBtn) {
		installBtn.addEventListener('click', function () {
			ajaxCall(
				installBtn,
				'cookiebot_install_ppg',
				cookiebot_ppg.i18n.installing,
				cookiebot_ppg.i18n.install,
				function () {
					var newBtn = document.createElement('button');
					newBtn.type = 'button';
					newBtn.id = 'cb-ppg-activate-btn';
					newBtn.className = 'cb-btn cb-main-btn';
					newBtn.textContent = cookiebot_ppg.i18n.activate;
					installBtn.parentNode.replaceChild(newBtn, installBtn);
					bindActivate(newBtn);
				}
			);
		});
	}
})();
