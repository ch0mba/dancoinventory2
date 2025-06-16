// Ensure the DOM is fully loaded before running the script
document.addEventListener('DOMContentLoaded', function() {
    // --- Get references to HTML elements ---

    // Add Transaction Modal elements
    const addTransactionModal = document.getElementById('addTransactionModal');
    const addTransactionBtn = document.getElementById('addTransactionBtn');
    const addModalCloseSpan = document.querySelector('#addTransactionModal .close'); // Specific close for add modal
    const transactionForm = document.getElementById('transactionForm');
    const addMessageBox = document.getElementById('addMessageBox');

    // Close Transaction Modal elements
    const closeTransactionModal = document.getElementById('closeTransactionModal');
    const closeTransactionBtn = document.getElementById('closeTransactionBtn');
    const closeModalCloseSpan = document.querySelector('#closeTransactionModal .close'); // Specific close for close modal
    const closeTransactionForm = document.getElementById('closeTransactionForm');
    const trnRefToCloseInput = document.getElementById('trnRefToClose');
    const closeMessageBox = document.getElementById('closeMessageBox');

    // Dropdown elements for the Add Transaction form
    const trnTypeSelect = document.getElementById('trnType');
    const warehouseSelect = document.getElementById('warehouse');
    const stockLocationSelect = document.getElementById('stockLocation');

    // Table elements for displaying transactions
    const itemTableBody = document.querySelector('#itemTable tbody');
    const itemTable = document.getElementById('itemTable'); // Get the table itself
    const tableMessageBox = document.getElementById('tableMessageBox');

    // --- Helper Functions ---

    /**
     * Displays a message in a specified message box.
     * @param {HTMLElement} box The message box element.
     * @param {string} message The message to display.
     * @param {string} type 'success', 'error', or 'info' to determine styling.
     */
    function displayMessage(box, message, type) {
        box.textContent = message;
        box.style.display = 'block';
        box.style.opacity = 0; // Start with 0 opacity for fade-in

        // Reset all background/color styles first
        box.style.backgroundColor = '';
        box.style.color = '';
        box.style.border = '';

        if (type === 'success') {
            box.style.backgroundColor = '#d4edda'; // Light green
            box.style.color = '#155724'; // Dark green text
            box.style.border = '1px solid #c3e6cb';
        } else if (type === 'error') {
            box.style.backgroundColor = '#f8d7da'; // Light red
            box.style.color = '#721c24'; // Dark red text
            box.style.border = '1px solid #f5c6cb';
        } else if (type === 'info') {
            box.style.backgroundColor = '#cfe2ff'; // Light blue
            box.style.color = '#055160'; // Dark blue text
            box.style.border = '1px solid #b6d4fe';
        }

        // Trigger fade-in
        setTimeout(() => {
            box.style.opacity = 1;
        }, 10); // Small delay to ensure display:block applies first
    }

    /**
     * Hides a specified message box with a fade-out effect.
     * @param {HTMLElement} box The message box element.
     */
    function hideMessage(box) {
        box.style.opacity = 0; // Start fade-out
        setTimeout(() => {
            box.style.display = 'none'; // Hide after fade-out
            box.textContent = ''; // Clear text content
            // Reset styles to default or empty string
            box.style.backgroundColor = '';
            box.style.color = '';
            box.style.border = '';
        }, 500); // Match CSS transition duration
    }

    /**
     * Fetches data for the dropdowns (Transaction Type, Warehouse, Stock Location)
     * from the backend and populates the respective <select> elements.
     */
    async function fetchAndPopulateDropdowns() {
        try {
            // Ensure this path is correct relative to transaction.js
            const response = await fetch('../api/fetch_dropdown_data.php');
            const data = await response.json();

            if (data.error) {
                console.error("Error fetching dropdown data:", data.error);
                displayMessage(addMessageBox, "Error loading dropdown options: " + data.error, 'error');
                return;
            }

            // Clear existing options and add a default "Select" option for each dropdown
            trnTypeSelect.innerHTML = '<option value="">Select a Transaction Type</option>';
            warehouseSelect.innerHTML = '<option value="">Select a Warehouse</option>';
            stockLocationSelect.innerHTML = '<option value="">Select a Stock Location</option>';

            // Populate Transaction Type dropdown if data is available
            if (data.transactionTypes && Array.isArray(data.transactionTypes)) {
                data.transactionTypes.forEach(type => {
                    const option = document.createElement('option');
                    option.value = type;
                    option.textContent = type;
                    trnTypeSelect.appendChild(option);
                });
            } else if (data.trntypes_error) {
                 console.error("Error populating transaction types:", data.trntypes_error);
            }

            // Populate Warehouse dropdown if data is available
            if (data.warehouses && Array.isArray(data.warehouses)) {
                data.warehouses.forEach(warehouse => {
                    const option = document.createElement('option');
                    option.value = warehouse;
                    option.textContent = warehouse;
                    warehouseSelect.appendChild(option);
                });
            } else if (data.warehouses_error) {
                console.error("Error populating warehouses:", data.warehouses_error);
            }

            // Populate Stock Location dropdown if data is available
            if (data.stockLocations && Array.isArray(data.stockLocations)) {
                data.stockLocations.forEach(location => {
                    const option = document.createElement('option');
                    option.value = location;
                    option.textContent = location;
                    stockLocationSelect.appendChild(option);
                });
            } else if (data.stocklocations_error) {
                console.error("Error populating stock locations:", data.stocklocations_error);
            }

        } catch (error) {
            console.error("Failed to fetch dropdown data:", error);
            displayMessage(addMessageBox, "Network error: Could not load dropdown options.", 'error');
        }
    }

    /**
     * Fetches transaction data from the backend and populates the itemTable.
     */
    async function fetchAndDisplayTransactions() {
        itemTableBody.innerHTML = ''; // Clear existing table data
        hideMessage(tableMessageBox); // Hide any previous messages

        try {
            // Corrected path to fetch_transactions.php based on your setup.
            // If fetch_transactions.php is in 'backend' and transaction.js in 'scripts',
            // then '../backend/fetch_transactions.php' is correct.
            const response = await fetch('../api/fetch_transaction.php');
            const data = await response.json();

            if (data.error) {
                console.error("Error fetching transactions:", data.error);
                displayMessage(tableMessageBox, "Error loading transactions: " + data.error, 'error');
                itemTable.style.display = 'none'; // Hide table if error
                return;
            }

            if (data.length === 0) {
                displayMessage(tableMessageBox, "No transactions found yet.", 'info');
                itemTable.style.display = 'none'; // Hide table if no data
                return;
            }

            // Iterate over fetched transactions and append them to the table
            data.forEach(transaction => {
                const row = itemTableBody.insertRow();
                row.insertCell().textContent = transaction.id;
                row.insertCell().textContent = transaction.trnType;
                row.insertCell().textContent = transaction.trnRef;
                row.insertCell().textContent = transaction.warehouse;
                // Important: Use 'storeLocation' as returned by PHP's fetch_transactions.php
                row.insertCell().textContent = transaction.storeLocation;
                row.insertCell().textContent = transaction.department;
                row.insertCell().textContent = transaction.truckNo || '-'; // Display '-' if null/empty
                row.insertCell().textContent = transaction.officer;
                row.insertCell().textContent = transaction.time;
                row.insertCell().textContent = transaction.status;

                // Add styling based on status
                if (transaction.status === 'Completed') {
                    row.classList.add('status-completed');
                } else if (transaction.status === 'Ongoing') {
                    row.classList.add('status-ongoing');
                }
            });

            // Ensure table is visible after data is loaded
            itemTable.style.display = 'table';

        } catch (error) {
            console.error("Failed to fetch transactions:", error);
            displayMessage(tableMessageBox, "Network error: Could not load transaction data.", 'error');
            itemTable.style.display = 'none'; // Hide table on network error
        }
    }

    // --- Event Listeners ---

    // When the user clicks the "Add Transaction" button
    addTransactionBtn.onclick = function() {
        addTransactionModal.style.display = 'flex'; // Use flex to center the modal
        fetchAndPopulateDropdowns(); // Populate dropdowns when modal opens
        hideMessage(addMessageBox); // Hide any previous messages
        transactionForm.reset(); // Clear form fields
    }

    // Close Add Transaction Modal (using 'x' button)
    addModalCloseSpan.onclick = function() {
        addTransactionModal.style.display = 'none';
        hideMessage(addMessageBox);
        transactionForm.reset();
    }

    // When the user clicks the "Close Transaction" button
    closeTransactionBtn.onclick = function() {
        closeTransactionModal.style.display = 'flex'; // Use flex to center the modal
        hideMessage(closeMessageBox); // Hide any previous messages
        closeTransactionForm.reset(); // Clear form fields
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
        }
        if (event.target == closeTransactionModal) {
            closeTransactionModal.style.display = 'none';
            hideMessage(closeMessageBox);
            closeTransactionForm.reset();
        }
    }

    // Handle Add Transaction Form submission via AJAX
    transactionForm.addEventListener('submit', async function(event) {
        event.preventDefault(); // Prevent default form submission (page reload)

        const formData = new FormData(transactionForm);

        try {
            const response = await fetch(transactionForm.action, {
                method: 'POST',
                body: formData
            });

            const result = await response.text(); // Get the response as text

            if (response.ok) { // Check if HTTP status is 2xx
                displayMessage(addMessageBox, result, 'success');
                fetchAndDisplayTransactions(); // Refresh the main table after successful submission
                // Close modal and reset form after a short delay
                setTimeout(() => {
                    addTransactionModal.style.display = 'none';
                    hideMessage(addMessageBox);
                    transactionForm.reset();
                }, 2000);
            } else {
                // Handle server-side errors (e.g., database error reported by PHP)
                console.error("Server error:", result);
                displayMessage(addMessageBox, result, 'error');
            }

        } catch (error) {
            // Catch any network errors (e.g., server not reachable)
            console.error("Network error:", error);
            displayMessage(addMessageBox, "An error occurred during submission. Please check your network connection.", 'error');
        }
    });

    // Handle Close Transaction Form submission via AJAX
    closeTransactionForm.addEventListener('submit', async function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(closeTransactionForm);

        try {
            // Send the form data to the close_transaction.php backend
            const response = await fetch('../api/close_transaction.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.text(); // Get the response as text

            if (response.ok) { // Check if HTTP status is 2xx
                displayMessage(closeMessageBox, result, 'success');
                fetchAndDisplayTransactions(); // Refresh the main table after successful status update
                // Close modal and reset form after a short delay
                setTimeout(() => {
                    closeTransactionModal.style.display = 'none';
                    hideMessage(closeMessageBox);
                    closeTransactionForm.reset();
                }, 2000);
            } else {
                // Handle server-side errors
                console.error("Server error:", result);
                displayMessage(closeMessageBox, result, 'error');
            }

        } catch (error) {
            // Catch any network errors
            console.error("Network error:", error);
            displayMessage(closeMessageBox, "An error occurred. Please check your network connection.", 'error');
        }
    });

    // Initial fetch of transactions when the page loads
    fetchAndDisplayTransactions();
});
