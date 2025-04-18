import { UbraniaKod } from './UbraniaKod.js';
import { GetBaseUrl } from './GetBaseUrl.js';

export const UbraniaManager = (function () {
    let ubraniaIndex = 1;

    const addUbranie = function (alertManager) {
        let newUbranieRow = document.querySelector('.ubranieRow').cloneNode(true);
        newUbranieRow.querySelectorAll('input, select').forEach((element) => {
            element.name = element.name.replace(/\[\d+\]/, `[${ubraniaIndex}]`);
            element.id = `${element.id}_${ubraniaIndex}`;

            if (element.tagName.toLowerCase() === 'input') {
                element.value = '';
                if (element.type === 'radio') {
                    element.id = `${element.id}_${ubraniaIndex}`;
                    element.name = `ubrania[${ubraniaIndex}][inlineRadioOptions]`;

                    if (element.id.includes('inlineRadio1')) {
                        element.value = 'option1';
                    } else if (element.id.includes('inlineRadio2')) {
                        element.value = 'option2';
                    }
                }
            }
        });

        newUbranieRow.querySelectorAll('label').forEach((label) => {
            const forAttribute = label.getAttribute('for');
            if (forAttribute) {
                label.setAttribute('for', `${forAttribute}_${ubraniaIndex}_${ubraniaIndex}`);
            }
        });

        const addButton = newUbranieRow.querySelector('.addUbranieBtn');
        if (addButton) addButton.style.display = 'none';

        const iloscInput = newUbranieRow.querySelector('input[type="number"]');
        if (iloscInput) iloscInput.value = '1';

        document.getElementById('ubraniaContainer').appendChild(newUbranieRow);
        ubraniaIndex++;

        UbraniaManager.updateRemoveButtonVisibility();

        const radioButtons = newUbranieRow.querySelectorAll('input[type="radio"]');
        initializeRadioBehavior(radioButtons);

        UbraniaKod.initializeKodInput(newUbranieRow.querySelector('.kodSection input'), alertManager);
    };

    const initializeRadioBehavior = function (radioButtons) {
        radioButtons.forEach(function (radio) {
            radio.addEventListener('change', function () {
                const currentRow = radio.closest('.ubranieRow');
                const nazwaSection = currentRow.querySelector('.nazwaSection');
                const rozmiarSection = currentRow.querySelector('.rozmiarSection');
                const kodSection = currentRow.querySelector('.kodSection');
                const ubranieIdInput = currentRow.querySelector('input[name*="[id_ubrania]"][type="hidden"]');
                const rozmiarIdInput = currentRow.querySelector('input[name*="[id_rozmiar]"][type="hidden"]');
                const ubranieSelect = nazwaSection.querySelector('select');
                const rozmiarSelect = rozmiarSection.querySelector('select');

                const formCheckDivs = currentRow.querySelectorAll('.form-check');
                formCheckDivs.forEach(function (div) {
                    div.classList.remove('border-primary');
                });

                const selectedDiv = radio.closest('.form-check');
                if (selectedDiv) {
                    selectedDiv.classList.add('border-primary');
                }

                if (radio.value === 'option1') {
                    nazwaSection.style.display = 'block';
                    rozmiarSection.style.display = 'block';
                    ubranieSelect.disabled = false;
                    kodSection.style.display = 'none';
                    kodSection.querySelector('input').value = '';
                    ubranieIdInput.disabled = true;
                    rozmiarIdInput.disabled = true;

                    if (!ubranieSelect.value) {
                        rozmiarSelect.disabled = true;
                    }
                    ubranieSelect.addEventListener('change', function () {
                        if (ubranieSelect.value) {
                            rozmiarSelect.disabled = false;
                        } else {
                            rozmiarSelect.disabled = true;
                        }
                    });
                } else if (radio.value === 'option2') {
                    nazwaSection.style.display = 'none';
                    rozmiarSection.style.display = 'none';
                    kodSection.style.display = 'block';
                    ubranieIdInput.disabled = false;
                    rozmiarIdInput.disabled = false;
                    nazwaSection.querySelector('select').value = '';
                    rozmiarSection.querySelector('select').value = '';
                    ubranieSelect.value = '';
                    rozmiarSelect.value = '';
                    ubranieSelect.disabled = true;
                    rozmiarSelect.disabled = true;
                }
            });

            const label = radio.closest('.ubranieRow').querySelector(`label[for="${radio.id}"]`);
        if (label) {
            label.addEventListener('click', function () {
                radio.checked = true;  
                radio.dispatchEvent(new Event('change')); 
            });
            }
        });
    };

    const addZamowienieUbranie = function () {
        let newUbranieRow = document.querySelector('.ubranieRow').cloneNode(true);

        newUbranieRow.querySelectorAll('input, select').forEach((element) => {
            element.name = element.name.replace(/\[\d+\]/, `[${ubraniaIndex}]`);
            element.id = `${element.id}_${ubraniaIndex}`;

            if (element.tagName.toLowerCase() === 'input') {
                element.value = '';
            }
        });

        const addButton = newUbranieRow.querySelector('.addUbranieBtn');
        if (addButton) addButton.style.display = 'none';
        const iloscInput = newUbranieRow.querySelector('input[name^="ubrania"][name$="[ilosc]"]');
        const iloscMinInput = newUbranieRow.querySelector('input[name^="ubrania"][name$="[iloscMin]"]');
        if (iloscInput) iloscInput.value = '1';
        if (iloscMinInput) iloscMinInput.value = '1';

        document.getElementById('ubraniaContainer').appendChild(newUbranieRow);
        ubraniaIndex++;

        UbraniaManager.updateRemoveButtonVisibility();
    };

    const removeUbranie = function (event) {
        if (event.target.classList.contains('removeUbranieBtn')) {
            event.target.closest('.ubranieRow').remove();
            UbraniaManager.updateRemoveButtonVisibility();
        }
    };

    const updateRemoveButtonVisibility = function () {
        const ubranieRows = document.querySelectorAll('.ubranieRow');
        ubranieRows.forEach((row, index) => {
            const removeButton = row.querySelector('.removeUbranieBtn');
            const addButton = row.querySelector('.addUbranieBtn');
            if (ubranieRows.length === 1) {
                removeButton.style.display = 'none';
                if (addButton) addButton.style.display = 'inline-block';
            } else {
                removeButton.style.display = index === 0 ? 'none' : 'inline-block';
                if (addButton) addButton.style.display = index === 0 ? 'inline-block' : 'none';
            }
        });
    };

    const loadRozmiary = function (event) {
        const start = performance.now();
        if (event.target.classList.contains('ubranie-select')) {
            const selectedUbranieId = event.target.value;
            const rozmiarSelect = event.target.closest('.ubranieRow').querySelector('.rozmiar-select');
            const baseUrl = GetBaseUrl();
            
            if (selectedUbranieId) {
                rozmiarSelect.disabled = false;
                fetch(`${baseUrl}/handlers/getRozmiary.php?ubranie_id=${selectedUbranieId}`)
                    .then(response => response.json())
                    .then(data => {
                        rozmiarSelect.innerHTML = '<option value="">Wybierz rozmiar</option>';
                        data.forEach(rozmiar => {
                            const option = document.createElement('option');
                            option.value = rozmiar.id;
                            option.textContent = `${rozmiar.rozmiar} (${rozmiar.ilosc})`;
                            if (rozmiar.ilosc === 0) {
                                option.disabled = true;
                            }
                            rozmiarSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error loading sizes:', error));
            } else {
                rozmiarSelect.innerHTML = '<option value="">Wybierz rozmiar</option>';
                rozmiarSelect.disabled = true;
            }
        }
        const end = performance.now();
        console.log(`UbraniaManager loadRozmiary: ${(end - start)} ms`);
    };

    const loadInitialRozmiary = function () {
        const initialUbranieSelects = document.querySelectorAll('.ubranie-select');
        initialUbranieSelects.forEach(ubranieSelect => {
            const rozmiarSelect = ubranieSelect.closest('.ubranieRow').querySelector('.rozmiar-select');
            rozmiarSelect.disabled = true;

            const event = new Event('change');
            ubranieSelect.dispatchEvent(event);
        });
    };

    return {
        addUbranie,
        addZamowienieUbranie,
        removeUbranie,
        updateRemoveButtonVisibility,
        loadRozmiary,
        loadInitialRozmiary,
        initializeRadioBehavior
    };
})();