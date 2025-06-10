Document.addEventListener('DOMContentLoaded', function() {
    // Get the model
    const modal = document.getElementById('transactionModel');

    //Get the button that opens the modal
    const addTransactionBtn = document.getElementById('addTransactionBtn');

    //Get the <span> element that closes the modal
    
    const span = document.getElementsByClassName('close')[0];

    //When the user clicks on the button, open the modal
    addTransactionBtn.onclick = function(){
        modal.style.display = 'block';

    }

    //When the user clicks anywhere outside of the modal, close it

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    //Optional: Handle form submission(you can expand this with your actual logic)
    const transactionForm = document.getElementById('transactionForm');
    transactionForm.addEventListener('submit', function(event) {
        event.preventDeefault(); //prevent default form submission

    
    });

    alert("Form submited! (This is placeholder alert)");
    modal.style.display = 'none'; // Close the modal after submission (optional)
    transactionForm.reset(); // clear the form fields

});