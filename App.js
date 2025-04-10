import { AlertManager } from './script/AlertManager.js';
import { UbraniaManager } from './script/UbraniaManager.js';
import { UserSuggestions } from './script/UserSuggestions.js';
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
                const alertManager = new AlertManager(alertManagerContainer);

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
            const alertContainer = document.getElementById('alertContainer');
            const alertManager = new AlertManager(alertContainer);
            ModalWydajUbranie.init(alertManager);
        },
        'UserSuggestions': () => {
            const alertManager = new AlertManager(document.getElementById('alertContainer'));
            const usernameInput = document.getElementById('username');
            const suggestions = document.getElementById('suggestions');
            if (usernameInput && suggestions) {
                new UserSuggestions(usernameInput, suggestions, alertManager);
            }

        },
        'UbraniaManager': () => {
            const addUbranieBtn = document.querySelector('.addUbranieBtn');
            const alertManager = new AlertManager(document.getElementById('alertContainer'));

            if (addUbranieBtn) {
                addUbranieBtn.addEventListener('click', function () {
                    UbraniaManager.addUbranie(alertManager);
                });
            }

            const ubraniaContainer = document.getElementById('ubraniaContainer');
            if (ubraniaContainer) {
                ubraniaContainer.addEventListener('click', UbraniaManager.removeUbranie);
                ubraniaContainer.addEventListener('change', UbraniaManager.loadRozmiary);
            }

            UbraniaManager.updateRemoveButtonVisibility();
            UbraniaManager.loadInitialRozmiary();

            const existingRadioButtons = document.querySelectorAll('input[type="radio"]');
            if (existingRadioButtons) {
                UbraniaManager.initializeRadioBehavior(existingRadioButtons);
            }

            const kodInputs = document.querySelectorAll('.kodSection input');

            if (kodInputs) {
                kodInputs.forEach(input => UbraniaKod.initializeKodInput(input, alertManager));
            }

        },
        'ProductSuggestions': () => {
            const initProductSuggestions = (container) => {
                const productNameInputs = container.querySelectorAll('input[name^="ubrania"][name$="[nazwa]"]');
                productNameInputs.forEach(input => {
                    const suggestionsList = input.closest('.ubranieRow').querySelector('#productSuggestions');

                    if (suggestionsList) {
                        input.addEventListener('input', ProductSuggestions.debounce(() => {
                            if (input.value.length >= 2) {
                                ProductSuggestions.fetchSuggestions(input.value, suggestionsList, input, 'fetchProductNames.php');
                            } else {
                                suggestionsList.style.display = 'none';
                            }
                        }, 200));
                        input.addEventListener('focus', () => {
                            if (input.value.length >= 2) {
                                suggestionsList.style.display = 'block';
                            }
                        });
                        input.addEventListener('blur', () => {
                            setTimeout(() => {
                                suggestionsList.style.display = 'none';
                            }, 200);
                        });
                    }
                });

                const sizeInputs = container.querySelectorAll('input[name^="ubrania"][name$="[rozmiar]"]');
                sizeInputs.forEach(input => {
                    const suggestionsList = input.closest('.ubranieRow').querySelector('#sizeSuggestions');
                    if (suggestionsList) {
                        input.addEventListener('input', ProductSuggestions.debounce(() => {
                            if (input.value.length >= 2) {
                                ProductSuggestions.fetchSuggestions(input.value, suggestionsList, input, 'fetchSizesNames.php');
                            } else {
                                suggestionsList.style.display = 'none';
                            }
                        }, 300));

                        input.addEventListener('focus', () => {
                            if (input.value.length >= 2) {
                                suggestionsList.style.display = 'block';
                            }
                        });

                        input.addEventListener('blur', () => {
                            setTimeout(() => {
                                suggestionsList.style.display = 'none';
                            }, 200);
                        });
                    }
                });
            };

            initProductSuggestions(document);

            document.querySelector('.addUbranieBtn').addEventListener('click', () => {
                const alertManager = new AlertManager(document.getElementById('alertContainer'));
                UbraniaManager.addZamowienieUbranie();
                const lastUbranieRow = document.querySelector('.ubranieRow:last-of-type');
                initProductSuggestions(lastUbranieRow);

                const newInputs = lastUbranieRow.querySelectorAll('input[name^="ubrania"][name$="[nazwa]"], input[name^="ubrania"][name$="[rozmiar]"], input[name^="ubrania"][name$="[kod]"]');
                newInputs.forEach(input => {
                    if (input.name.includes('kod')) {
                        CheckUbranie.checkKod(input, alertManager);
                    } else {
                        CheckUbranie.checkNameSize(input, alertManager);
                    }
                });
            });

            document.getElementById('ubraniaContainer').addEventListener('click', (event) => {
                if (event.target.classList.contains('removeUbranieBtn')) {
                    UbraniaManager.removeUbranie(event);
                }
            });
        },
        'CheckUbranie': () => {
            const alertManager = new AlertManager(document.getElementById('alertContainer'));
            const nameInputs = document.querySelectorAll('input[name^="ubrania"][name$="[nazwa]"]');
            const sizeInputs = document.querySelectorAll('input[name^="ubrania"][name$="[rozmiar]"]');
            const kodInputs = document.querySelectorAll('input[name^="ubrania"][name$="[kod]"]');

            nameInputs.forEach(input => CheckUbranie.checkNameSize(input, alertManager));
            sizeInputs.forEach(input => CheckUbranie.checkNameSize(input, alertManager));
            kodInputs.forEach(input => CheckUbranie.checkKod(input, alertManager));
        },
        'EdycjaUbranie': () => {
            const alertManager = new AlertManager(document.getElementById('alertContainer'));
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
