document.addEventListener('DOMContentLoaded', function () {
    const popup = document.getElementById('court-resolve-popup');
    const cancelBtn = document.querySelector('.court-btn-cancel');
    const resolveForm = document.getElementById('court-resolve-form');
    const confirmbutton =document.getElementById('court-btn-confirm')


    document.querySelectorAll('.btn-resolve').forEach(btn => {
        btn.addEventListener('click', function () {
            const caseId = this.getAttribute('data-case-id');
            document.getElementById('court-case-id').value = caseId;
            popup.style.display = 'flex';
        });
    });


    cancelBtn.addEventListener('click', function () {
        popup.style.display = 'none';
    });


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

