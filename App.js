document.addEventListener('DOMContentLoaded', (event) => {
	const modulesAttr = document.body.getAttribute('data-modules') || '';
	const modules = modulesAttr.split(',').map(m => m.trim()).filter(Boolean);

    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

	// Initialize DOM Update System
	let domUpdateSystem = null;

	const moduleLoaders = {
		AlertManager: async () => {
			const { AlertManager } = await import('./script/AlertManager.js');
			const { addCsrfToFormData } = await import('./script/utils.js');
			const { DomUpdateSystem } = await import('./script/DomUpdateSystem.js');
			
			// Initialize DOM Update System
			domUpdateSystem = DomUpdateSystem;
			domUpdateSystem.initialize();
			
			const alertManagerContainer = document.getElementById('alertContainer');
			if (!alertManagerContainer) {
				console.warn('AlertManager: Element alertContainer nie został znaleziony.');
				return;
			}
			const alertManager = AlertManager.create(alertManagerContainer);
			const forms = document.querySelectorAll('form');
			forms.forEach(form => {
				form.addEventListener('submit', async function (e) {
					e.preventDefault();
					const submitBtn = form.querySelector('.submitBtn');
					const loadingSpinner = document.getElementById('loadingSpinner');
					if (submitBtn) submitBtn.disabled = true;
					if (loadingSpinner) loadingSpinner.style.display = 'block';
					const formData = new FormData(this);
					addCsrfToFormData(formData);
					const actionUrl = this.getAttribute('action');
					try {
						const response = await fetch(actionUrl, { method: 'POST', body: formData });
						if (!response.ok) throw new Error('Network error: ' + response.statusText);
						const data = await response.json();
						if (data.success) {
							alertManager.createAlert(data.message, 'success');
							
							if (window.fromRaport) {
								const modalElement = document.getElementById('confirmModal');
								if (modalElement) { new bootstrap.Modal(modalElement).show(); }
							} else {
								await domUpdateSystem.updateDOMAfterFormSubmission(form, data);
							}
						} else {
							alertManager.createAlert(data.message || 'Wystąpił błąd podczas przetwarzania żądania.', 'danger');
						}
					} catch (err) {
						alertManager.createAlert('Wystąpił błąd podczas przetwarzania żądania.', 'danger');
						const modalElement = document.getElementById('confirmModal');
						if (modalElement) { new bootstrap.Modal(modalElement).show(); }
					} finally {
						if (submitBtn) submitBtn.disabled = false;
						if (loadingSpinner) loadingSpinner.style.display = 'none';
					}
				});
			});
		},
		ModalWydajUbranie: async () => {
			const [{ AlertManager }, { ModalWydajUbranie }] = await Promise.all([
				import('./script/AlertManager.js'),
				import('./script/ModalWydajUbranie.js')
			]);
			const alertManager = AlertManager.create(document.getElementById('alertContainer'));
			ModalWydajUbranie.init(alertManager);
		},
		WorkerSuggestions: async () => {
			const [{ AlertManager }, { WorkerSuggestions }] = await Promise.all([
				import('./script/AlertManager.js'),
				import('./script/WorkerSuggestions.js')
			]);
			const alertManager = AlertManager.create(document.getElementById('alertContainer'));
			const usernameInput = document.getElementById('username');
			const suggestions = document.getElementById('suggestions');
			if (usernameInput && suggestions) {
				WorkerSuggestions.create(usernameInput, suggestions, alertManager);
			}
		},
		UbraniaManager: async () => {
			const [{ AlertManager }, { UbraniaManager }, { UbraniaKod }] = await Promise.all([
				import('./script/AlertManager.js'),
				import('./script/UbraniaManager.js'),
				import('./script/UbraniaKod.js')
			]);
			const alertManager = AlertManager.create(document.getElementById('alertContainer'));
			const manager = UbraniaManager.create();
			const addUbranieBtn = document.querySelector('.addUbranieBtn');
			if (addUbranieBtn) {
				addUbranieBtn.addEventListener('click', () => { manager.addUbranie(alertManager); });
			}
			const ubraniaContainer = document.getElementById('ubraniaContainer');
			if (ubraniaContainer) {
				ubraniaContainer.addEventListener('click', manager.removeUbranie);
				ubraniaContainer.addEventListener('change', manager.loadRozmiary);
			}
			manager.updateRemoveButtonVisibility();
			manager.loadInitialRozmiary();
			const existingRadioButtons = document.querySelectorAll('input[type="radio"]');
			if (existingRadioButtons.length) { manager.initializeRadioBehavior(existingRadioButtons); }
			const kodInputs = document.querySelectorAll('.kodSection input');
			if (kodInputs.length) { kodInputs.forEach(input => UbraniaKod.initializeKodInput(input, alertManager)); }
		},
		ProductSuggestions: async () => {
			const [{ AlertManager }, { UbraniaManager }, { CheckUbranie }, { ProductSuggestions }] = await Promise.all([
				import('./script/AlertManager.js'),
				import('./script/UbraniaManager.js'),
				import('./script/CheckUbranie.js'),
				import('./script/ProductSuggestions.js')
			]);
			const alertManager = AlertManager.create(document.getElementById('alertContainer'));
			const manager = UbraniaManager.create();
			const initCheckUbranieForRow = (row) => {
				const inputs = row.querySelectorAll('input[name^="ubrania"]');
				inputs.forEach(input => {
					if (input.name.endsWith('[kod]')) { CheckUbranie.checkKod(input, alertManager); }
					else if (input.name.endsWith('[nazwa]') || input.name.endsWith('[rozmiar]')) { CheckUbranie.checkNameSize(input, alertManager); }
				});
			};
			ProductSuggestions.init(document);
			document.querySelector('.addUbranieBtn').addEventListener('click', () => {
				manager.addZamowienieUbranie();
				const lastUbranieRow = document.querySelector('.ubranieRow:last-of-type');
				ProductSuggestions.init(lastUbranieRow);
				initCheckUbranieForRow(lastUbranieRow);
			});
			document.getElementById('ubraniaContainer').addEventListener('click', (event) => {
				if (event.target.classList.contains('removeUbranieBtn')) { manager.removeUbranie(event); }
			});
		},
		CheckUbranie: async () => {
			const [{ AlertManager }, { CheckUbranie }] = await Promise.all([
				import('./script/AlertManager.js'),
				import('./script/CheckUbranie.js')
			]);
			const alertManager = AlertManager.create(document.getElementById('alertContainer'));
			const rows = document.querySelectorAll('.ubranieRow');
			rows.forEach(row => {
				const nameInput = row.querySelector('input[name$="[nazwa]"]');
				const sizeInput = row.querySelector('input[name$="[rozmiar]"]');
				const kodInput = row.querySelector('input[name$="[kod]"]');
				if (nameInput) CheckUbranie.checkNameSize(nameInput, alertManager);
				if (sizeInput) CheckUbranie.checkNameSize(sizeInput, alertManager);
				if (kodInput) CheckUbranie.checkKod(kodInput, alertManager);
			});
		},
		EdycjaUbranie: async () => {
			const [{ AlertManager }, { EdycjaUbranie }] = await Promise.all([
				import('./script/AlertManager.js'),
				import('./script/EdycjaUbranie.js')
			]);
			const alertManager = AlertManager.create(document.getElementById('alertContainer'));
			EdycjaUbranie.initialize(alertManager);
		},
		RedirectStatus: async () => {
			const { RedirectStatus } = await import('./script/RedirectStatus.js');
			RedirectStatus.initialize();
		},
		ChangeStatus: async () => {
			const { ChangeStatus } = await import('./script/ChangeStatus.js');
			ChangeStatus.initialize();
		},
		AnulujWydanie: async () => {
			const { AnulujWydanie } = await import('./script/AnulujWydanie.js');
			AnulujWydanie.initialize();
		},
		ModalEdytujPracownika: async () => {
			const { ModalEdytujPracownika } = await import('./script/ModalEdytujPracownika.js');
			ModalEdytujPracownika.initialize();
		},
		ZniszczUbranie: async () => {
			const { ZniszczUbranie } = await import('./script/ZniszczUbranie.js');
			ZniszczUbranie.initialize();
		},
		HistoriaUbranSzczegoly: async () => {
			const { HistoriaUbranSzczegoly } = await import('./script/HistoriaUbranSzczegoly.js');
			HistoriaUbranSzczegoly.initialize();
		}
	};

	modules.forEach(moduleName => {
		if (moduleLoaders[moduleName]) {
			moduleLoaders[moduleName]().catch(console.error);
		} else {
			console.warn(`Module ${moduleName} is not defined in moduleLoaders.`);
		}
	});

});
