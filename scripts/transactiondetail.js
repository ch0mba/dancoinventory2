const items = [];

function addItem() {
    const name = document.getElementById('itemName').value.trim();
    const quantity = document.getElementById('itemQuantity').value.trim();
    const brand = document.getElementById('itemBrand').value.trim();
    const notation = document.getElementById('itemNote').value.trim();


    if(!name || !quantity || !brand || !notation) 
        return alert('Please fill in all fields.');
        
    items.push({ name, quantity, brand, notation });
    renderTable();

    // clear input
    document.getElementById('itemForm').reset();
}
function renderTable() {
    const tbody = document.querySelector('#itemTable tbody');
    tbody.innerHTML = '';

    items.forEach((item, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
        <td>${item.name}</td>
        <td>${item.quantity}</td>
        <td>${item.brand}</td>
        <td>${item.notation}</td>
        <td>
            <button type ="button" onclick="deleteItem(${index})">Delete</button>
        </td>`;
        tbody.appendChild(row);
    });
}

function deleteItem(index) {
    items.splice(index, 1);
    renderTable();
}


function prepareSubmission() {
    if (items.length === 0) {
        alert('No items to submit.');
        return false;
    }
    document.getElementById('itemsInput').value = JSON.stringify(items);
    return true;
}