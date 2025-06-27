document.addEventListener('DOMContentLoaded', function() {
    console.log('stockcode_table.js loaded and DOM fully loaded.');

    const stockCodeSearchInput = document.getElementById('stockCodeSearch');
    const stockcodeTableBody = document.querySelector('#stockcodeTable tbody');
    const noResultsMessage = document.getElementById('noResults');

    if (!stockCodeSearchInput) {
        console.error('Error: #stockCodeSearch input not found!');
        return;
    }
    if (!stockcodeTableBody) {
        console.error('Error: #stockcodeTable tbody not found!');
        return;
    }
    if (!noResultsMessage) {
        console.error('Error: #noResults paragraph not found!');
        return;
    }

    /**
     * Fetches stock codes from the backend and updates the table.
     * @param {string} searchTerm - The term to search for (optional).
     */
    async function fetchAndDisplayStockCodes(searchTerm = '') {
        console.log(`Fetching stock codes with search term: "${searchTerm}"`);
        try {
            // Construct the URL with the search term
            const url = `../api/fetch_stockcode.php?search=${encodeURIComponent(searchTerm)}`;
            console.log(`Fetch URL: ${url}`);
            const response = await fetch(url);

            console.log('Fetch response received:', response);

            if (!response.ok) {
                const errorText = await response.text(); // Get raw error text
                throw new Error(`HTTP error! Status: ${response.status}. Response: ${errorText}`);
            }

            const result = await response.json();
            console.log('Parsed JSON result:', result);

            stockcodeTableBody.innerHTML = ''; // Clear existing table rows

            if (result.success) {
                if (result.data && result.data.length > 0) {
                    noResultsMessage.style.display = 'none'; // Hide "No results" message
                    result.data.forEach(stockcode => {
                        const row = stockcodeTableBody.insertRow();
                        row.innerHTML = `
                            <td>${stockcode.stockcode}</td>
                            <td>${stockcode.description}</td>
                            <td class="action-buttons">
                                <button class="btn btn-danger btn-sm" data-id="${stockcode.id}">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </td>
                        `;
                        // Attach event listener for delete button (optional, but good practice)
                        row.querySelector('.btn-danger').addEventListener('click', function() {
                            // Implement delete functionality here
                            // For example: confirmAndDelete(stockcode.id);
                            alert(`Delete stockcode with ID: ${stockcode.id}`); // Placeholder alert
                            console.log(`Delete button clicked for ID: ${stockcode.id}`);
                        });
                    });
                } else {
                    console.log('No data found in the response or data array is empty.');
                    noResultsMessage.style.display = 'block'; // Show "No results" message
                }
            } else {
                console.error('Backend reported an error:', result.message || 'Unknown error');
                stockcodeTableBody.innerHTML = `<tr><td colspan="3" style="color: red; text-align: center;">Error: ${result.message || 'Failed to load stock codes.'}</td></tr>`;
                noResultsMessage.style.display = 'none';
            }

        } catch (error) {
            console.error('Error fetching stock codes:', error);
            stockcodeTableBody.innerHTML = `<tr><td colspan="3" style="color: red; text-align: center;">Failed to load stock codes. Please check console for details.</td></tr>`;
            noResultsMessage.style.display = 'none';
        }
    }

    // Event listener for the search input
    stockCodeSearchInput.addEventListener('input', function() {
        const searchTerm = this.value;
        fetchAndDisplayStockCodes(searchTerm);
    });

    // Initial load of stock codes when the page loads
    fetchAndDisplayStockCodes();
});
