document.addEventListener('DOMContentLoaded', function () {
    const popup = document.getElementById('court-resolve-popup');
    const cancelBtn = document.querySelector('.court-btn-cancel');
    const resolveForm = document.getElementById('court-resolve-form');
    const confirmbutton =document.getElementById('court-btn-confirm')

    // Open popup when resolve button is clicked
    document.querySelectorAll('.btn-resolve').forEach(btn => {
        btn.addEventListener('click', function () {
            const caseId = this.getAttribute('data-case-id');
            document.getElementById('court-case-id').value = caseId;
            popup.style.display = 'flex';
        });
    });

    // Close popup when cancel is clicked
    cancelBtn.addEventListener('click', function () {
        popup.style.display = 'none';
    });

    // Handle form submission
    resolveForm.addEventListener('submit', function (e) {
        e.preventDefault();
    
        const formData = new FormData(this);
        const caseId = formData.get('case_id');
    
        if (!caseId) {
            console.error('Case ID is missing in form data!');
            return;
        }
    
        fetch('resolve-case.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const btn = document.querySelector(`.btn-resolve[data-case-id="${caseId}"]`);
                    btn.className = "btn btn-remove";
                    btn.textContent = 'Resolved';
                    btn.disabled = true;
    
                    popup.style.display = 'none';
                    alert('Case resolved successfully!');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(() => {
                alert('An error occurred');
            });
    });
    
});


/* Get DOM elements
resolveForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        const caseId = formData.get('case_id');
        if (!caseId) {
            console.error('Case ID is missing in form data!');
            // Check the input element
            const caseIdInput = document.getElementById('court-case-id');
            console.log('Input element:', caseIdInput);
            console.log('Input value:', caseIdInput ? caseIdInput.value : 'not found');
            return;
        }

        fetch('resolve-case.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const btn = document.querySelector(`.btn-resolve[data-case-id="${caseId}"]`);
                    btn.className="btn btn-remove"
                    btn.textContent = 'Resolved';
                    btn.disabled = true;

                    popup.style.display = 'none';
                    alert('Case resolved successfully!');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
    });
    */
