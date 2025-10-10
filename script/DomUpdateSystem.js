/**
 * Advanced DOM Update System
 * Handles real-time DOM updates without page reloads
 */

export const DomUpdateSystem = (() => {
    let baseUrl = '';

    const initialize = () => {
        baseUrl = getBaseUrl();
    };

    const getBaseUrl = () => {
        const metaBaseUrl = document.querySelector('meta[name="base-url"]');
        return metaBaseUrl ? metaBaseUrl.getAttribute('content') : '';
    };

    // Main function to update DOM after form submission
    const updateDOMAfterFormSubmission = async (form, responseData) => {
        const formId = form.id;
        const actionUrl = form.getAttribute('action');
        
        // Reset form first
        resetFormAndUpdateUI(form, responseData);
        
        // Update specific UI elements based on form type
        if (formId === 'wydajUbranieForm' || actionUrl.includes('wydaj_ubranie')) {
            await updateInventoryDisplay(responseData);
            updateWorkerSuggestions(responseData);
        }
        
        if (formId === 'zamowienieForm' || actionUrl.includes('dodaj_zamowienie')) {
            await updateInventoryDisplay(responseData);
            updateOrderHistory(responseData);
        }
        
        if (formId === 'pracownikForm' || actionUrl.includes('dodaj_pracownika')) {
            updateWorkerSuggestions(responseData);
        }
        
        // Update any counters or status indicators
        updatePageCounters();
        
        // Refresh any data tables if they exist
        refreshDataTables();
    };

    const resetFormAndUpdateUI = (form, responseData) => {
        // Reset form fields
        form.reset();
        
        // Clear any dynamic content that might need updating
        const ubraniaContainer = document.getElementById('ubraniaContainer');
        if (ubraniaContainer && ubraniaContainer.children.length > 1) {
            // Keep only the first row for forms with multiple items
            const firstRow = ubraniaContainer.querySelector('.ubranieRow');
            if (firstRow) {
                ubraniaContainer.innerHTML = '';
                ubraniaContainer.appendChild(firstRow);
            }
        }
        
        // Reset any suggestion lists
        const suggestionLists = document.querySelectorAll('.list-group');
        suggestionLists.forEach(list => {
            list.style.display = 'none';
            list.innerHTML = '';
        });
        
        // Clear any cached data that might be stale
        // Note: WorkerSuggestions cache will be cleared when module is re-initialized
        
        // Focus on first input for better UX
        const firstInput = form.querySelector('input:not([type="hidden"]):not([disabled])');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
    };

    const updateInventoryDisplay = async (responseData) => {
        // Update inventory-related displays without page reload
        const inventoryElements = document.querySelectorAll('[data-inventory-item]');
        
        if (inventoryElements.length > 0) {
            // Fetch updated inventory data
            try {
                const response = await fetch(`${baseUrl}/handlers/getInventoryData.php`);
                const inventoryData = await response.json();
                
                // Update inventory displays
                inventoryElements.forEach(element => {
                    const itemId = element.getAttribute('data-inventory-item');
                    const itemData = inventoryData.find(item => item.id == itemId);
                    if (itemData) {
                        updateInventoryElement(element, itemData);
                    }
                });
            } catch (error) {
                console.warn('Could not update inventory display:', error);
            }
        }
    };

    const updateInventoryElement = (element, itemData) => {
        // Update inventory element with new data
        const quantityElement = element.querySelector('[data-quantity]');
        const statusElement = element.querySelector('[data-status]');
        
        if (quantityElement) {
            quantityElement.textContent = itemData.ilosc;
            quantityElement.className = itemData.ilosc < itemData.iloscMin ? 'text-danger' : 'text-success';
        }
        
        if (statusElement) {
            statusElement.textContent = itemData.ilosc < itemData.iloscMin ? 'Niski stan' : 'OK';
            statusElement.className = itemData.ilosc < itemData.iloscMin ? 'badge bg-danger' : 'badge bg-success';
        }
    };

    const updateWorkerSuggestions = (responseData) => {
        // Clear worker suggestions cache to include new employees
        const workerInput = document.getElementById('username');
        if (workerInput) {
            // Trigger a small change to refresh suggestions
            const currentValue = workerInput.value;
            workerInput.value = currentValue + ' ';
            workerInput.value = currentValue;
            workerInput.dispatchEvent(new Event('input'));
        }
        
        // Add new employee to any employee lists if they exist
        if (responseData.newEmployee) {
            addEmployeeToList(responseData.newEmployee);
        }
    };

    const addEmployeeToList = (employeeData) => {
        // Add new employee to employee lists
        const employeeLists = document.querySelectorAll('[data-employee-list]');
        employeeLists.forEach(list => {
            const employeeElement = document.createElement('div');
            employeeElement.className = 'employee-item border-bottom py-2';
            employeeElement.innerHTML = `
                <div class="row">
                    <div class="col-md-3">${employeeData.imie}</div>
                    <div class="col-md-3">${employeeData.nazwisko}</div>
                    <div class="col-md-3">${employeeData.stanowisko}</div>
                    <div class="col-md-3">${employeeData.timestamp}</div>
                </div>
            `;
            
            // Insert at the top of the list
            const firstChild = list.firstElementChild;
            if (firstChild) {
                list.insertBefore(employeeElement, firstChild);
            } else {
                list.appendChild(employeeElement);
            }
        });
    };

    const updateOrderHistory = (responseData) => {
        // Update order history displays
        const orderHistoryElements = document.querySelectorAll('[data-order-history]');
        orderHistoryElements.forEach(element => {
            // Add new order to the display
            if (responseData.newOrder) {
                addOrderToHistory(element, responseData.newOrder);
            }
        });
    };

    const addOrderToHistory = (container, orderData) => {
        // Create new order element and add to history
        const orderElement = document.createElement('div');
        orderElement.className = 'order-item border-bottom py-2';
        orderElement.innerHTML = `
            <div class="row">
                <div class="col-md-3">${orderData.data}</div>
                <div class="col-md-3">${orderData.ilosc}</div>
                <div class="col-md-3">${orderData.status}</div>
                <div class="col-md-3">${orderData.uwagi}</div>
            </div>
        `;
        
        // Insert at the top of the history
        const firstChild = container.firstElementChild;
        if (firstChild) {
            container.insertBefore(orderElement, firstChild);
        } else {
            container.appendChild(orderElement);
        }
    };

    const refreshDataTables = () => {
        // Refresh DataTables if they exist
        if (typeof $.fn.DataTable !== 'undefined') {
            $('.dataTable').each(function() {
                if ($.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable().ajax.reload(null, false); // false = stay on current page
                }
            });
        }
    };

    const updatePageCounters = () => {
        // Update any counters or status indicators without full reload
        const counters = document.querySelectorAll('[data-counter]');
        counters.forEach(counter => {
            const counterType = counter.getAttribute('data-counter');
            
            // Update specific counter types
            switch (counterType) {
                case 'total-employees':
                    updateEmployeeCounter(counter);
                    break;
                case 'total-inventory':
                    updateInventoryCounter(counter);
                    break;
                case 'low-stock':
                    updateLowStockCounter(counter);
                    break;
                default:
                    console.log(`Updating counter: ${counterType}`);
            }
        });
    };

    const updateEmployeeCounter = async (counterElement) => {
        try {
            const response = await fetch(`${baseUrl}/handlers/getEmployeeCount.php`);
            const data = await response.json();
            if (data.count !== undefined) {
                counterElement.textContent = data.count;
            }
        } catch (error) {
            console.warn('Could not update employee counter:', error);
        }
    };

    const updateInventoryCounter = async (counterElement) => {
        try {
            const response = await fetch(`${baseUrl}/handlers/getInventoryCount.php`);
            const data = await response.json();
            if (data.count !== undefined) {
                counterElement.textContent = data.count;
            }
        } catch (error) {
            console.warn('Could not update inventory counter:', error);
        }
    };

    const updateLowStockCounter = async (counterElement) => {
        try {
            const response = await fetch(`${baseUrl}/handlers/getLowStockCount.php`);
            const data = await response.json();
            if (data.count !== undefined) {
                counterElement.textContent = data.count;
                counterElement.className = data.count > 0 ? 'badge bg-danger' : 'badge bg-success';
            }
        } catch (error) {
            console.warn('Could not update low stock counter:', error);
        }
    };

    return {
        initialize,
        updateDOMAfterFormSubmission,
        updateInventoryDisplay,
        updateWorkerSuggestions,
        updateOrderHistory,
        updatePageCounters,
        refreshDataTables
    };
})();
