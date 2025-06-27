document.addEventListener('DOMContentLoaded', function() {
    const stockCodeSearchInput = document.getElementById('stockCodeSearch');
    const stockcodeTableBody = document.querySelector('#stockcodeTable tbody');
    const noResultsMessage = document.getElementById('noResults');

    /**
     * Fetches stock codes from the backend and updates the table.
     * @param {string} searchTerm - The term to search for (optional).
     */
    async function fetchAndDisplayStockCodes(searchTerm = '') {
        try {
            // Construct the URL with the search term
            const url = `../api/fetch_stockcode.php?search=${encodeURIComponent(searchTerm)}`;
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            stockcodeTableBody.innerHTML = ''; // Clear existing table rows

            if (result.success && result.data.length > 0) {
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
                    });
                });
            } else {
                noResultsMessage.style.display = 'block'; // Show "No results" message
            }

        } catch (error) {
            console.error('Error fetching stock codes:', error);
            stockcodeTableBody.innerHTML = `<tr><td colspan="3" style="color: red; text-align: center;">Failed to load stock codes. Please try again.</td></tr>`;
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
