// Form validation script for driver signup
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const fields = {
        fname: document.getElementById('fname'),
        lname: document.getElementById('lname'),
        email: document.getElementById('email'),
        nic: document.getElementById('nic'),
        userid: document.getElementById('userid'),
        phoneno: document.getElementById('phoneno'),
        password: document.getElementById('password'),
        cpassword: document.getElementById('cpassword')
    };

    // Create error message elements for each field
    for (const key in fields) {
        const errorElement = document.createElement('span');
        errorElement.className = 'error-message';
        errorElement.id = `${key}-error`;
        errorElement.style.color = 'red';
        errorElement.style.fontSize = '12px';
        errorElement.style.display = 'none';
        if (fields[key].parentNode) {
            fields[key].parentNode.appendChild(errorElement);
        }
    }

    // Validation functions
    const validation = {
        fname: (value) => {
            if (!value) return 'First name is required';
            if (/[0-9]/.test(value)) return 'First name cannot contain numbers';
            if (/[^a-zA-Z\s]/.test(value)) return 'First name can only contain letters';
            return '';
        },
        
        lname: (value) => {
            if (!value) return 'Last name is required';
            if (/[0-9]/.test(value)) return 'Last name cannot contain numbers';
            if (/[^a-zA-Z\s]/.test(value)) return 'Last name can only contain letters';
            return '';
        },
        
        email: (value) => {
            if (!value) return 'Email is required';
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) return 'Please enter a valid email address';
            return '';
        },
        
        nic: (value) => {
            if (!value) return 'NIC is required';
            // For old format: 9 digits followed by V or X
            // For new format: 12 digits
            const oldNicRegex = /^[0-9]{9}[VvXx]$/;
            const newNicRegex = /^[0-9]{12}$/;
            if (!oldNicRegex.test(value) && !newNicRegex.test(value)) {
                return 'NIC should be in valid format (9 digits + V/X or 12 digits)';
            }
            return '';
        },
        
        userid: (value) => {
            if (!value) return 'Driver ID is required';
            const licenseRegex = /^([B][0-9]{7}|[0-9]{12})$/;
            if (!licenseRegex.test(value)) {
                return 'Driver ID should be in format B1234567 or 12 digits';
            }
            return '';
        },
        
        phoneno: (value) => {
            if (!value) return 'Phone number is required';
            if (!/^\d{10}$/.test(value)) return 'Phone number must be 10 digits';
            return '';
        },
        
        password: (value) => {
            if (!value) return 'Password is required';
            if (value.length < 6) return 'Password must be at least 6 characters';
            // Strength check (optional)
            if (!/(?=.*\d)(?=.*[a-zA-Z])/.test(value)) {
                return 'Password should contain both letters and numbers';
            }
            return '';
        },
        
        cpassword: (value) => {
            if (!value) return 'Please confirm your password';
            if (value !== fields.password.value) return 'Passwords do not match';
            return '';
        }
    };

    // Show error message for a field
    function showError(field, message) {
        const errorElement = document.getElementById(`${field}-error`);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
            fields[field].classList.add('input-error');
        }
    }

    // Clear error message for a field
    function clearError(field) {
        const errorElement = document.getElementById(`${field}-error`);
        if (errorElement) {
            errorElement.textContent = '';
            errorElement.style.display = 'none';
            fields[field].classList.remove('input-error');
        }
    }

    // Validate a single field
    function validateField(field) {
        const value = fields[field].value.trim();
        const error = validation[field](value);
        
        if (error) {
            showError(field, error);
            return false;
        } else {
            clearError(field);
            return true;
        }
    }

    // Add input event listeners to all fields
    for (const field in fields) {
        fields[field].addEventListener('input', function() {
            validateField(field);
        });
        
        fields[field].addEventListener('blur', function() {
            validateField(field);
        });
    }

    // Form submission validation
    form.addEventListener('submit', function(event) {
        let isValid = true;
        
        // Validate all fields
        for (const field in fields) {
            if (!validateField(field)) {
                isValid = false;
            }
        }
        
        if (!isValid) {
            event.preventDefault();
        }
    });

    // Optional: Handle AJAX validation for checking existing email
    fields.email.addEventListener('blur', function() {
        const email = fields.email.value.trim();
        if (email && !validation.email(email)) {
            // Only check if email format is valid
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'check_email.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    const response = JSON.parse(this.responseText);
                    if (response.exists) {
                        showError('email', 'This email is already registered');
                    }
                }
            };
            xhr.send('email=' + encodeURIComponent(email));
        }
    });

    // Optional: Handle AJAX validation for checking existing NIC
    fields.nic.addEventListener('blur', function() {
        const nic = fields.nic.value.trim();
        if (nic && !validation.nic(nic)) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'check_nic.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    const response = JSON.parse(this.responseText);
                    if (response.exists) {
                        showError('nic', 'This NIC is already registered');
                    }
                }
            };
            xhr.send('nic=' + encodeURIComponent(nic));
        }
    });
});