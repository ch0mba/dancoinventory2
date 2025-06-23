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

    // Main page table filter elements (retained for now, though less critical with separate tables)
    // const filterWarehouseSelect = document.getElementById('filterWarehouse'); // You might reconsider needing this if tables are split by status
    // const resetFilterBtn = document.getElementById('resetFilterBtn'); // Same as above

    // NEW: Elements for the Searchable Warehouse Dropdown in the form
    const warehouseSearchInput = document.getElementById('warehouse_search_input');
    const warehouseDropdown = document.getElementById('warehouse_dropdown');
    const hiddenWarehouseInput = document.getElementById('warehouse'); // This carries the value for form submission
    let allWarehouses = []; // To store the full list of warehouses (e.g., ['Warehouse A', 'Warehouse B'])

    // Table elements for displaying transactions - UPDATED REFERENCES
    const ongoingItemTableBody = document.getElementById('ongoingItemTableBody');
    const ongoingItemTable = document.getElementById('ongoingItemTable');
    const ongoingTableMessageBox = document.getElementById('ongoingTableMessageBox');

    const completedItemTableBody = document.getElementById('completedItemTableBody');
    const completedItemTable = document.getElementById('completedItemTable');
    const completedTableMessageBox = document.getElementById('completedTableMessageBox');

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

                // // Populate the main page filter dropdown (optional, based on your final UI needs)
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
     * Fetches transaction data from the backend and populates the appropriate tables.
     * @param {string} [warehouseFilter=''] Optional: The warehouse name to filter by.
     */
    async function fetchAndDisplayTransactions(warehouseFilter = '') {
        ongoingItemTableBody.innerHTML = ''; // Clear existing ongoing table data
        completedItemTableBody.innerHTML = ''; // Clear existing completed table data
        hideMessage(ongoingTableMessageBox); // Hide any previous messages
        hideMessage(completedTableMessageBox);

        try {
            let url = '../api/fetch_transaction.php';
            if (warehouseFilter) {
                url += `?warehouse=${encodeURIComponent(warehouseFilter)}`;
            }

            const response = await fetch(url);
            const data = await response.json();

            if (data.error) {
                console.error("Error fetching transactions:", data.error);
                displayMessage(ongoingTableMessageBox, "Error loading ongoing transactions: " + data.error, 'error');
                displayMessage(completedTableMessageBox, "Error loading completed transactions: " + data.error, 'error');
                ongoingItemTable.style.display = 'none';
                completedItemTable.style.display = 'none';
                return;
            }

            let ongoingCount = 0;
            let completedCount = 0;

            data.forEach(transaction => {
                if (transaction.status === 'Ongoing') {
                    ongoingCount++;
                    const row = ongoingItemTableBody.insertRow();
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

                    // Add action buttons for ongoing transactions (e.g., Edit, Delete)
                    const actionsCell = row.insertCell();
                    const editButton = document.createElement('button');
                    editButton.textContent = 'Edit';
                    editButton.classList.add('edit-btn'); // Add a class for styling
                    editButton.addEventListener('click', () => editTransaction(transaction.id)); // Implement edit function
                    actionsCell.appendChild(editButton);

                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Delete';
                    deleteButton.classList.add('delete-btn'); // Add a class for styling
                    deleteButton.addEventListener('click', () => deleteTransaction(transaction.id)); // Implement delete function
                    actionsCell.appendChild(deleteButton);

                } else if (transaction.status === 'Completed') {
                    completedCount++;
                    const row = completedItemTableBody.insertRow();
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
                    // No actions for completed transactions as per your request
                }
            });

            // Display messages and tables based on counts
            if (ongoingCount === 0) {
                displayMessage(ongoingTableMessageBox, "No ongoing transactions found." + (warehouseFilter ? ` for '${warehouseFilter}'` : ''), 'info');
                ongoingItemTable.style.display = 'none';
            } else {
                ongoingItemTable.style.display = 'table';
                hideMessage(ongoingTableMessageBox);
            }

            if (completedCount === 0) {
                displayMessage(completedTableMessageBox, "No completed transactions found." + (warehouseFilter ? ` for '${warehouseFilter}'` : ''), 'info');
                completedItemTable.style.display = 'none';
            } else {
                completedItemTable.style.display = 'table';
                hideMessage(completedTableMessageBox);
            }

        } catch (error) {
            console.error("Failed to fetch transactions:", error);
            displayMessage(ongoingTableMessageBox, "Network error: Could not load ongoing transaction data.", 'error');
            displayMessage(completedTableMessageBox, "Network error: Could not load completed transaction data.", 'error');
            ongoingItemTable.style.display = 'none';
            completedItemTable.style.display = 'none';
        }
    }

    // --- New: Implement Edit and Delete Functions (Placeholders) ---
    function editTransaction(transactionId) {
        console.log('Edit transaction with ID:', transactionId);
        // Here you would typically open a modal pre-filled with transaction data
        // and allow the user to modify it, then send an AJAX request to update.
        alert(`Implement edit functionality for transaction ID: ${transactionId}`);
    }

    async function deleteTransaction(transactionId) {
        if (!confirm(`Are you sure you want to delete transaction ID: ${transactionId}?`)) {
            return;
        }

        try {
            const response = await fetch('../api/delete_transaction.php', { // You'll need to create this PHP file
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: transactionId })
            });

            const result = await response.json(); // Assuming your PHP returns JSON

            if (response.ok && result.success) {
                displayMessage(ongoingTableMessageBox, `Transaction ${transactionId} deleted successfully.`, 'success');
                fetchAndDisplayTransactions(); // Refresh tables after deletion
            } else {
                console.error("Server error:", result.error || result);
                displayMessage(ongoingTableMessageBox, `Error deleting transaction ${transactionId}: ${result.error || 'Unknown error'}`, 'error');
            }
        } catch (error) {
            console.error("Network error:", error);
            displayMessage(ongoingTableMessageBox, "Network error: Could not delete transaction.", 'error');
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
            const response = await fetch('../backend/transaction.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.text(); // Assuming your PHP returns plain text success/error

            if (response.ok) {
                displayMessage(addMessageBox, result, 'success');
                fetchAndDisplayTransactions(); // Refresh both tables
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
            const result = await response.text(); // Assuming your PHP returns plain text success/error
            if (response.ok) {
                displayMessage(closeMessageBox, result, 'success');
                fetchAndDisplayTransactions(); // Refresh both tables
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
        fetchAndDisplayTransactions(); // Call without filter to load all
    });
});