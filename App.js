import { AlertManager } from './script/AlertManager.js';
import { UbraniaManager } from './script/UbraniaManager.js';
import { WorkerSuggestions } from './script/WorkerSuggestions.js';
import { ModalEdytujPracownika } from './script/ModalEdytujPracownika.js';
import { ProductSuggestions } from './script/ProductSuggestions.js';
import { UbraniaKod } from './script/UbraniaKod.js';
import { CheckUbranie } from './script/CheckUbranie.js';
import { RedirectStatus } from './script/RedirectStatus.js';
import { ChangeStatus } from './script/ChangeStatus.js';
import { ModalWydajUbranie } from './script/ModalWydajUbranie.js';
import { AnulujWydanie } from './script/AnulujWydanie.js';
import { ZniszczUbranie } from './script/ZniszczUbranie.js';
import { EdycjaUbranie } from './script/EdycjaUbranie.js';
import { HistoriaUbranSzczegoly } from './script/HistoriaUbranSzczegoly.js';

document.addEventListener('DOMContentLoaded', (event) => {
    const modules = document.body.getAttribute('data-modules').split(',');

    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const moduleLoaders = {
        'AlertManager': () => {
            const alertManagerContainer = document.getElementById('alertContainer');
            if (alertManagerContainer) {
                const alertManager = AlertManager.create(alertManagerContainer);

                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();

                        const submitBtn = form.querySelector('.submitBtn');
                        const loadingSpinner = document.getElementById('loadingSpinner');

                        if (submitBtn) submitBtn.disabled = true;
                        if (loadingSpinner) loadingSpinner.style.display = 'block';

                        const formData = new FormData(this);
                        const actionUrl = this.getAttribute('action');

                        fetch(actionUrl, {
                            method: 'POST',
                            body: formData
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network error: ' + response.statusText);
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    alertManager.createAlert(data.message, 'success');

                                    if (window.fromRaport) {
                                        const modalElement = document.getElementById('confirmModal');
                                        if (modalElement) {
                                            const modal = new bootstrap.Modal(modalElement);
                                            modal.show();
                                        }
                                    } else {
                                        setTimeout(() => {
                                            location.reload();
                                        }, 200);
                                    }
                                } else {
                                    alertManager.createAlert(data.message || 'Wystąpił błąd podczas przetwarzania żądania.', 'danger');
                                }
                            })
                            .catch(error => {
                                console.error('fetch error:', error);
                                alertManager.createAlert('Wystąpił błąd podczas przetwarzania żądania.', 'danger');

                                const modalElement = document.getElementById('confirmModal');
                                if (modalElement) {
                                    const modal = new bootstrap.Modal(modalElement);
                                    modal.show();
                                }
                            })
                            .finally(() => {
                                if (submitBtn) submitBtn.disabled = false;
                                if (loadingSpinner) loadingSpinner.style.display = 'none';
                            });
                    });
                });
            } else {
                console.warn("AlertManager: Element alertContainer nie został znaleziony.");
            }

        },
        'ModalWydajUbranie': () => {
            const alertManager = AlertManager.create(document.getElementById('alertContainer'));
            ModalWydajUbranie.init(alertManager);
        },
        'WorkerSuggestions': () => {
            const alertManager = AlertManager.create(document.getElementById('alertContainer'));
            const usernameInput = document.getElementById('username');
            const suggestions = document.getElementById('suggestions');
            if (usernameInput && suggestions) {
                WorkerSuggestions.create(usernameInput, suggestions, alertManager);
            }
        },
        'UbraniaManager': () => {
            const alertManager = AlertManager.create(document.getElementById('alertContainer'));
            const manager = UbraniaManager.create();

            const addUbranieBtn = document.querySelector('.addUbranieBtn');
            if (addUbranieBtn) {
                addUbranieBtn.addEventListener('click', () => {
                    manager.addUbranie(alertManager);
                });
            }

            const ubraniaContainer = document.getElementById('ubraniaContainer');
            if (ubraniaContainer) {
                ubraniaContainer.addEventListener('click', manager.removeUbranie);
                ubraniaContainer.addEventListener('change', manager.loadRozmiary);
            }

            manager.updateRemoveButtonVisibility();
            manager.loadInitialRozmiary();

            const existingRadioButtons = document.querySelectorAll('input[type="radio"]');
            if (existingRadioButtons.length) {
                manager.initializeRadioBehavior(existingRadioButtons);
            }

            const kodInputs = document.querySelectorAll('.kodSection input');
            if (kodInputs.length) {
                kodInputs.forEach(input => UbraniaKod.initializeKodInput(input, alertManager));
            }
        },
        'ProductSuggestions': () => {
            const alertManager = AlertManager.create(document.getElementById('alertContainer'));
            const manager = UbraniaManager.create();

            const initCheckUbranieForRow = (row) => {
                const inputs = row.querySelectorAll('input[name^="ubrania"]');
                inputs.forEach(input => {
                    if (input.name.endsWith('[kod]')) {
                        CheckUbranie.checkKod(input, alertManager);
                    } else if (input.name.endsWith('[nazwa]') || input.name.endsWith('[rozmiar]')) {
                        CheckUbranie.checkNameSize(input, alertManager);
                    }
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
                if (event.target.classList.contains('removeUbranieBtn')) {
                    manager.removeUbranie(event);
                }
            });
        },
        'CheckUbranie': () => {
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
        'EdycjaUbranie': () => {
            const alertManager = AlertManager.create(document.getElementById('alertContainer'));
            EdycjaUbranie.initialize(alertManager);
        },
        'RedirectStatus': () => {
            RedirectStatus.initialize();
        },
        'ChangeStatus': () => {
            ChangeStatus.initialize();
        },
        'AnulujWydanie': () => {
            AnulujWydanie.initialize();
        },
        'ModalEdytujPracownika': () => {
            ModalEdytujPracownika.initialize();
        },
        'ZniszczUbranie': () => {
            ZniszczUbranie.initialize();
        },
        'HistoriaUbranSzczegoly': () => {
            HistoriaUbranSzczegoly.initialize();
        }
    };

    modules.forEach(moduleName => {
        if (moduleLoaders[moduleName]) {
            moduleLoaders[moduleName]();
        } else {
            console.warn(`Module ${moduleName} is not defined in moduleLoaders.`);
        }
    });

});
