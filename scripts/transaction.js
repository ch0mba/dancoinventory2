// Ensure the DOM is fully loaded before running the script
document.addEventListener('DOMContentLoaded', function() {
    // --- Get references to HTML elements ---

    // Add Transaction Modal elements
    const addTransactionModal = document.getElementById('addTransactionModal');
    const addTransactionBtn = document.getElementById('addTransactionBtn');
    const addModalCloseSpan = document.querySelector('#addTransactionModal .close');
    const transactionForm = document.getElementById('transactionForm');
    const addMessageBox = document.getElementById('addMessageBox');

    // Close Transaction Modal elements
    const closeTransactionModal = document.getElementById('closeTransactionModal');
    const closeTransactionBtn = document.getElementById('closeTransactionBtn');
    const closeModalCloseSpan = document.querySelector('#closeTransactionModal .close');
    const closeTransactionForm = document.getElementById('closeTransactionForm');
    const trnRefToCloseInput = document.getElementById('trnRefToClose');
    const closeMessageBox = document.getElementById('closeMessageBox');

    // Dropdown elements for the Add Transaction form (trnType, stockLocation remain standard selects)
    const trnTypeSelect = document.getElementById('trnType');
    const stockLocationSelect = document.getElementById('stockLocation');

    // Main page table filter elements
    const filterWarehouseSelect = document.getElementById('filterWarehouse'); // Still needed for the main table filter
    const resetFilterBtn = document.getElementById('resetFilterBtn');

    // NEW: Elements for the Searchable Warehouse Dropdown in the form
    const warehouseSearchInput = document.getElementById('warehouse_search_input');
    const warehouseDropdown = document.getElementById('warehouse_dropdown');
    const hiddenWarehouseInput = document.getElementById('warehouse'); // This carries the value for form submission
    let allWarehouses = []; // To store the full list of warehouses (e.g., ['Warehouse A', 'Warehouse B'])

    // Table elements for displaying transactions
    const itemTableBody = document.querySelector('#itemTable tbody');
    const itemTable = document.getElementById('itemTable');
    const tableMessageBox = document.getElementById('tableMessageBox');

    // --- Helper Functions ---

    function displayMessage(box, message, type) {
        box.textContent = message;
        box.style.display = 'block';
        box.style.opacity = 0;
        box.style.backgroundColor = '';
        box.style.color = '';
        box.style.border = '';
        if (type === 'success') { box.style.backgroundColor = '#d4edda'; box.style.color = '#155724'; box.style.border = '1px solid #c3e6cb'; }
        else if (type === 'error') { box.style.backgroundColor = '#f8d7da'; box.style.color = '#721c24'; box.style.border = '1px solid #f5c6cb'; }
        else if (type === 'info') { box.style.backgroundColor = '#cfe2ff'; box.style.color = '#055160'; box.style.border = '1px solid #b6d4fe'; }
        setTimeout(() => { box.style.opacity = 1; }, 10);
    }

    function hideMessage(box) {
        box.style.opacity = 0;
        setTimeout(() => {
            box.style.display = 'none';
            box.textContent = '';
            box.style.backgroundColor = '';
            box.style.color = '';
            box.style.border = '';
        }, 500);
    }

    /**
     * Populates the custom searchable warehouse dropdown.
     * @param {Array<string>} warehousesToDisplay The list of warehouse names to show.
     */
    function populateSearchableWarehouseDropdown(warehousesToDisplay) {
        warehouseDropdown.innerHTML = ''; // Clear previous items

        if (warehousesToDisplay.length === 0 && warehouseSearchInput.value.trim() !== '') {
            const noResultsItem = document.createElement('div');
            noResultsItem.classList.add('searchable-dropdown-item');
            noResultsItem.textContent = 'No matching warehouses found';
            noResultsItem.style.cursor = 'default';
            warehouseDropdown.appendChild(noResultsItem);
        } else {
            warehousesToDisplay.forEach(warehouse => {
                const item = document.createElement('div');
                item.classList.add('searchable-dropdown-item');
                item.textContent = warehouse;
                item.setAttribute('data-value', warehouse); // Store the actual value

                item.addEventListener('click', function() {
                    warehouseSearchInput.value = warehouse; // Set text input to selected warehouse
                    hiddenWarehouseInput.value = warehouse; // Set hidden input for form submission
                    warehouseDropdown.style.display = 'none'; // Hide dropdown
                });
                warehouseDropdown.appendChild(item);
            });
        }
        // Only show dropdown if there are items or a search query is active
        warehouseDropdown.style.display = (warehousesToDisplay.length > 0 || warehouseSearchInput.value.trim() !== '') ? 'block' : 'none';
    }


    /**
     * Fetches data for the dropdowns (Transaction Type, Warehouse, Stock Location)
     * from the backend and populates the respective <select> elements.
     * This function now also populates the main page filter and stores all warehouses.
     */
    async function fetchAndPopulateDropdowns() {
        try {
            const response = await fetch('../api/fetch_dropdown_data.php');
            const data = await response.json();

            if (data.error) {
                console.error("Error fetching dropdown data:", data.error);
                displayMessage(addMessageBox, "Error loading dropdown options: " + data.error, 'error');
                return;
            }

            // Populate Transaction Type dropdown
            trnTypeSelect.innerHTML = '<option value="">Select a Transaction Type</option>';
            if (data.transactionTypes && Array.isArray(data.transactionTypes)) {
                data.transactionTypes.forEach(type => {
                    const option = document.createElement('option');
                    option.value = type;
                    option.textContent = type;
                    trnTypeSelect.appendChild(option);
                });
            }

            // Store and populate Warehouse dropdowns
            if (data.warehouses && Array.isArray(data.warehouses)) {
                allWarehouses = data.warehouses; // Store the full list
                populateSearchableWarehouseDropdown(allWarehouses); // Populate the custom searchable dropdown

                // // Populate the main page filter dropdown
                // filterWarehouseSelect.innerHTML = '<option value="">All Warehouses</option>';
                // data.warehouses.forEach(warehouse => {
                //     const filterOption = document.createElement('option');
                //     filterOption.value = warehouse;
                //     filterOption.textContent = warehouse;
                //     filterWarehouseSelect.appendChild(filterOption);
                // });
            }

            // Populate Stock Location dropdown
            stockLocationSelect.innerHTML = '<option value="">Select a Stock Location</option>';
            if (data.stockLocations && Array.isArray(data.stockLocations)) {
                data.stockLocations.forEach(location => {
                    const option = document.createElement('option');
                    option.value = location;
                    option.textContent = location;
                    stockLocationSelect.appendChild(option);
                });
            }

        } catch (error) {
            console.error("Failed to fetch dropdown data:", error);
            displayMessage(addMessageBox, "Network error: Could not load dropdown options.", 'error');
        }
    }

    /**
     * Fetches transaction data from the backend and populates the itemTable.
     * @param {string} [warehouseFilter=''] Optional: The warehouse name to filter by.
     */
    async function fetchAndDisplayTransactions(warehouseFilter = '') {
        itemTableBody.innerHTML = ''; // Clear existing table data
        hideMessage(tableMessageBox); // Hide any previous messages

        try {
            let url = '../api/fetch_transaction.php';
            if (warehouseFilter) {
                url += `?warehouse=${encodeURIComponent(warehouseFilter)}`;
            }

            const response = await fetch(url);
            const data = await response.json();

            if (data.error) {
                console.error("Error fetching transactions:", data.error);
                displayMessage(tableMessageBox, "Error loading transactions: " + data.error, 'error');
                itemTable.style.display = 'none';
                return;
            }

            if (data.length === 0) {
                displayMessage(tableMessageBox, "No transactions found yet." + (warehouseFilter ? ` for '${warehouseFilter}'` : ''), 'info');
                itemTable.style.display = 'none';
                return;
            }

            data.forEach(transaction => {
                const row = itemTableBody.insertRow();
                row.insertCell().textContent = transaction.id;
                row.insertCell().textContent = transaction.trnType;
                row.insertCell().textContent = transaction.trnRef;
                row.insertCell().textContent = transaction.warehouse;
                row.insertCell().textContent = transaction.storeLocation;
                row.insertCell().textContent = transaction.department;
                row.insertCell().textContent = transaction.truckNo || '-';
                row.insertCell().textContent = transaction.officer;
                row.insertCell().textContent = transaction.time;
                row.insertCell().textContent = transaction.status;

                if (transaction.status === 'Completed') {
                    row.classList.add('status-completed');
                } else if (transaction.status === 'Ongoing') {
                    row.classList.add('status-ongoing');
                }
            });
            itemTable.style.display = 'table';

        } catch (error) {
            console.error("Failed to fetch transactions:", error);
            displayMessage(tableMessageBox, "Network error: Could not load transaction data.", 'error');
            itemTable.style.display = 'none';
        }
    }

    // --- Event Listeners ---

    // When the user clicks the "Add Transaction" button
    addTransactionBtn.onclick = function() {
        addTransactionModal.style.display = 'flex';
        fetchAndPopulateDropdowns(); // Re-populate dropdowns
        hideMessage(addMessageBox);
        transactionForm.reset();
        // Clear and reset searchable warehouse field on open
        warehouseSearchInput.value = '';
        hiddenWarehouseInput.value = '';
        populateSearchableWarehouseDropdown(allWarehouses); // Show all warehouses initially
        warehouseDropdown.style.display = 'none'; // Ensure it's hidden until user types
    }

    // Close Add Transaction Modal (using 'x' button)
    addModalCloseSpan.onclick = function() {
        addTransactionModal.style.display = 'none';
        hideMessage(addMessageBox);
        transactionForm.reset();
        warehouseSearchInput.value = '';
        hiddenWarehouseInput.value = '';
        warehouseDropdown.style.display = 'none';
    }

    // When the user clicks the "Close Transaction" button
    closeTransactionBtn.onclick = function() {
        closeTransactionModal.style.display = 'flex';
        hideMessage(closeMessageBox);
        closeTransactionForm.reset();
    }

    // Close Close Transaction Modal (using 'x' button)
    closeModalCloseSpan.onclick = function() {
        closeTransactionModal.style.display = 'none';
        hideMessage(closeMessageBox);
        closeTransactionForm.reset();
    }

    // When the user clicks anywhere outside of any modal, close it
    window.onclick = function(event) {
        if (event.target == addTransactionModal) {
            addTransactionModal.style.display = 'none';
            hideMessage(addMessageBox);
            transactionForm.reset();
            warehouseSearchInput.value = '';
            hiddenWarehouseInput.value = '';
            warehouseDropdown.style.display = 'none';
        }
        if (event.target == closeTransactionModal) {
            closeTransactionModal.style.display = 'none';
            hideMessage(closeMessageBox);
            closeTransactionForm.reset();
        }
        // NEW: Hide warehouse dropdown if clicking outside of it or its input
        if (!event.target.closest('.searchable-container') && warehouseDropdown.style.display === 'block') {
            warehouseDropdown.style.display = 'none';
            // Optional: If the input isn't empty but no selection was made, you might want to clear the hidden field
            // if (hiddenWarehouseInput.value === '' && warehouseSearchInput.value !== '') {
            //     warehouseSearchInput.value = ''; // Or warn the user
            // }
        }
    }

    // Handle Add Transaction Form submission via AJAX
    transactionForm.addEventListener('submit', async function(event) {
        event.preventDefault();

        // Validate that a warehouse has been selected (hidden input has a value)
        if (!hiddenWarehouseInput.value) {
            displayMessage(addMessageBox, "Please select a warehouse from the dropdown list.", 'error');
            return; // Stop submission
        }

        const formData = new FormData(transactionForm);
        // Ensure the hidden input's value is correctly picked up by FormData
        // (If its 'name' attribute matches, FormData will pick it up automatically)

        try {
            const response = await fetch('.../backend/tranaction.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.text();

            if (response.ok) {
                displayMessage(addMessageBox, result, 'success');
                fetchAndDisplayTransactions(filterWarehouseSelect.value);
                setTimeout(() => {
                    addTransactionModal.style.display = 'none';
                    hideMessage(addMessageBox);
                    transactionForm.reset();
                    warehouseSearchInput.value = ''; // Clear search input
                    hiddenWarehouseInput.value = ''; // Clear hidden input
                    warehouseDropdown.style.display = 'none'; // Hide dropdown
                }, 2000);
            } else {
                console.error("Server error:", result);
                displayMessage(addMessageBox, result, 'error');
            }

        } catch (error) {
            console.error("Network error:", error);
            displayMessage(addMessageBox, "An error occurred during submission. Please check your network connection.", 'error');
        }
    });

    // Handle Close Transaction Form submission via AJAX (no changes needed for this request)
    closeTransactionForm.addEventListener('submit', async function(event) {
        event.preventDefault();
        const formData = new FormData(closeTransactionForm);
        try {
            const response = await fetch('../api/close_transaction.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.text();
            if (response.ok) {
                displayMessage(closeMessageBox, result, 'success');
                fetchAndDisplayTransactions(filterWarehouseSelect.value);
                setTimeout(() => {
                    closeTransactionModal.style.display = 'none';
                    hideMessage(closeMessageBox);
                    closeTransactionForm.reset();
                }, 2000);
            } else {
                console.error("Server error:", result);
                displayMessage(closeMessageBox, result, 'error');
            }
        } catch (error) {
            console.error("Network error:", error);
            displayMessage(closeMessageBox, "An error occurred. Please check your network connection.", 'error');
        }
    });


    // NEW: Event Listener for the Warehouse search input within the Add Transaction form
    warehouseSearchInput.addEventListener('input', function() {
        const searchText = this.value.toLowerCase();
        // Clear hidden input if user types, forcing them to re-select
        hiddenWarehouseInput.value = '';

        const filteredWarehouses = allWarehouses.filter(warehouse =>
            warehouse.toLowerCase().includes(searchText)
        );
        populateSearchableWarehouseDropdown(filteredWarehouses);
    });

    // NEW: Handle focus/blur for the custom dropdown
    warehouseSearchInput.addEventListener('focus', function() {
        // Show all warehouses when input is focused, unless there's existing text
        if (this.value === '') {
            populateSearchableWarehouseDropdown(allWarehouses);
        } else {
            // Re-filter if there's text but dropdown was hidden
            const searchText = this.value.toLowerCase();
            const filteredWarehouses = allWarehouses.filter(warehouse =>
                warehouse.toLowerCase().includes(searchText)
            );
            populateSearchableWarehouseDropdown(filteredWarehouses);
        }
        warehouseDropdown.style.display = 'block'; // Ensure dropdown is visible on focus
    });

    // Initial fetch of transactions and dropdowns when the page loads
    fetchAndPopulateDropdowns().then(() => {
        fetchAndDisplayTransactions();
    });
});