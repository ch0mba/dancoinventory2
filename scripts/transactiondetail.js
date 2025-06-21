// scripts/transactiondetail.js
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM Content Loaded'); // Confirm script starts

    const transactionSearchInput = document.getElementById('transaction_search_input');
    const transactionDropdown = document.getElementById('transaction_dropdown');
    const hiddenTransactionInput = document.getElementById('transaction');

    const stockcodeSearchInput = document.getElementById('stockcode_search_input');
    const stockcodedropdown = document.getElementById('stockcode_dropdown');
    const hiddenStockcodeInput = document.getElementById('stockcode');

    const addItemBtn = document.getElementById('addItemBtn');
    const itemTableBody = document.querySelector('#itemTable tbody');
    const itemForm = document.getElementById('itemForm');
    const hiddenItemsInput = document.getElementById('items');
    const submitForm = document.getElementById('submitForm');

    let addedItems = [];

    function setupSearchableDropdown(searchInput, dropdownDiv, hiddenInput, apiUrl) {
        let timeout = null;

        searchInput.addEventListener('input', () => {
            clearTimeout(timeout);
            const query = searchInput.value.trim();
            console.log(`Search input changed: "${query}" for API: ${apiUrl}`); // Log input

            if (query.length < 2) {
                dropdownDiv.innerHTML = '';
                dropdownDiv.style.display = 'none';
                console.log('Query too short, hiding dropdown.');
                return;
            }

            timeout = setTimeout(() => {
                console.log(`Fetching data for "${query}" from ${apiUrl}`); // Log fetch start
                fetch(`${apiUrl}?search=${encodeURIComponent(query)}`)
                    .then(response => {
                        console.log('Fetch response received:', response); // Log raw response object
                        if (!response.ok) {
                            return response.text().then(text => {
                                console.error(`HTTP error! status: ${response.status} - Response Text: ${text}`);
                                throw new Error(`HTTP error! status: ${response.status} - ${text}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Parsed JSON data:', data); // Log parsed data
                        dropdownDiv.innerHTML = '';
                        if (data && Array.isArray(data) && data.length > 0) { // Added Array.isArray check
                            data.forEach(item => {
                                const div = document.createElement('div');
                                div.classList.add('dropdown-item');
                                div.textContent = item.name;
                                div.dataset.id = item.id;
                                div.addEventListener('click', () => {
                                    searchInput.value = item.name;
                                    hiddenInput.value = item.id;
                                    dropdownDiv.innerHTML = '';
                                    dropdownDiv.style.display = 'none';
                                    console.log(`Selected: ${item.name}, ID: ${item.id}`); // Log selection
                                });
                                dropdownDiv.appendChild(div);
                            });
                            dropdownDiv.style.display = 'block';
                            console.log('Dropdown displayed with results.');
                        } else {
                            dropdownDiv.innerHTML = '<div class="dropdown-item">No result found</div>';
                            dropdownDiv.style.display = 'block';
                            console.log('No results found, displaying "No result" message.');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching search results:', error); // Log fetch errors
                        dropdownDiv.innerHTML = `<div class="dropdown-item">Error fetching data: ${error.message}</div>`;
                        dropdownDiv.style.display = 'block';
                    });
            }, 300);
        });

        document.addEventListener('click', (event) => {
            if (!searchInput.contains(event.target) && !dropdownDiv.contains(event.target)) {
                dropdownDiv.style.display = 'none';
                // console.log('Clicked outside, hiding dropdown.'); // Uncomment if too chatty
            }
        });
    }

    setupSearchableDropdown(transactionSearchInput, transactionDropdown, hiddenTransactionInput, '../api/fetch_transaction_dropdown_data.php');
    setupSearchableDropdown(stockcodeSearchInput, stockcodedropdown, hiddenStockcodeInput, '../api/fetch_stockcode_dropdown_data.php');

    // ... rest of your addItem, renderItemTable, editItem, deleteItem, submitForm functions
    // (Ensure these are exactly as in the last corrected code block)


        // --- Add Item to Table Functionality ---
        addItemBtn.addEventListener('click', addItem);

        function addItem() {
            const transactionId = hiddenTransactionInput.value;
            const stockcodeId = hiddenStockcodeInput.value;
            const stockcodeName = stockcodeSearchInput.value; // Get the displayed name
            const quantity = document.getElementById('quantity').value;
            const brand = document.getElementById('brand').value;
            const note = document.getElementById('note').value;

            // Basic validation
            if (!transactionId || !stockcodeName || !quantity || !brand || !note){
                alert('Please find in all item details and select a transaction and stockcode form the dropdowns.');
                return;
            }

            const newItem = {
                transactionId:transactionId,
                stockcode_id: stockcodeId,
                stockcode_name: stockcodeName,
                quantity: parseInt(quantity),
                brand: brand,
                note: note
            };

            addedItems.push(newItem);
            renderItemTable();
            itemForm.reset();// Clear the form
            hiddenStockcodeInput.value = ''; // Clear hidden stockcode ID as well
            stockcodeSearchInput.value = '';// Clear stockcode search input


        }

        function renderItemTable(){
            itemTableBody.innerHTML =''; // Clear existing rows

            addedItems.forEach((item, index) => {
                const row = itemTableBody.insertRow();
                row.insertCell(0).textContent = item.stockcode_name;
                row.insertCell(1).textContent = item.quantity;
                row.insertCell(2).textContent = item.brand;
                row.insertCell(3).textContent = item.note;

                const actionsCell = row.insertCell(4);
                const updateBtn = document.createElement('button');
                updateBtn.classList.add('btn', 'btn-info', 'btn-sm');
                updateBtn.innerHTML = '<i class="bi bi-pencil-square"></i>';
                updateBtn.title = 'Edit Item';
                updateBtn.addEventListener('click', () => editItem(index));

                const deleteBtn = document.createElement('button');
                deleteBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'ms-2'); // ms-2 for margin-left
                deleteBtn.innerHTML = '<i class="bi bi-trash"></i>';
                deleteBtn.title = 'Delete Item';
                deleteBtn.addEventListener('click', () => deleteItem(index));

                actionsCell.appendChild(updateBtn);
                actionsCell.appendChild(deleteBtn);

            });
        }

        function editItem(index){
            const itemToEdit = addedItems[index];

            // Poppulate the from within item data
            hiddenStockcodeInput.value = itemToEdit.stockcode_id;
            stockcodeSearchInput.value = itemToEdit.stockcode_name;
            document.getElementById('quantity').value = itemToEdit.quantity;
            document.getElementById('brand').value = itemToEdit.brand;
            document.getElementById('note').value = itemToEdit.note;

            //Remove the item form the array (it will be re-added if they click 'Add item' again)
            addedItems.splice(index, 1);
            renderItemTable(); // Re -render the table without the item being edited

        }

        function deleteItem(index){
            if(confirm('Are you sure you want to delete this item?')) {
                addedItems.splice(index, 1); //Remove item from array
                renderItemTable(); // Re-render the table
            }
        }
        
        // --- Prepare Submission Functionality ---
        submitForm.addEventListener('submit', (event) => {
            //Ensure a tranaction is selected befor submission
            if (!hiddenTransactionInput.value){
                alert('Please select a  tranaction before submitting.')
                event.preventDefault(); //Stop form submission
                return;
            }

            // Esnure there are items to submit
            if (addedItems.length === 0){
                alert('Please add at least on item before submitting.');
                event.preventDefault(); //Stop form submission
                return;
            }

            // Add the transaction ID to each item before stringiFying
            const itemsToSubmit = addedItems.map(item => ({
                ...item,
                transaction_id: hiddenTransactionInput.value // Emsure teransction ID is explicitly linked
            }));

            hiddenItemsInput.value =JSON.stringify(itemsToSubmit);
            // The form will now submit to the transactiondetail.php
        });

});