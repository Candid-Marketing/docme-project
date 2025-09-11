document.addEventListener("DOMContentLoaded", function () {
    // Your existing JavaScript code

    // Menu Hover effect
    let list = document.querySelectorAll(".navigation li");

    function activeLink() {
        list.forEach((item) => {
            item.classList.remove("hovered");
        });
        this.classList.add("hovered");
    }

    list.forEach((item) => item.addEventListener("mouseover", activeLink));

    // Menu Toggle functionality
    let toggle = document.querySelector(".toggle");
    let navigation = document.querySelector(".navigation");
    let main = document.querySelector(".main");

    toggle.onclick = function () {
        navigation.classList.toggle("active");
        main.classList.toggle("active");
    };
});

//Add user

document.getElementById('addUserForm').addEventListener('submit', function (e) {

    // Clear previous errors
    document.querySelectorAll('.error').forEach(el => el.textContent = '');

    const formData = new FormData(this);

    $.ajax({
        url: '/superadmin/add-user', // Correct URL for the POST request
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.success) {
                e.preventDefault();
                document.getElementById('addUserForm').reset();
                const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
                modal.hide();

            } else if (data.errors) {
                console.log(data);
                // Display validation errors in the modal
                for (const [field, messages] of Object.entries(data.errors)) {

                    const errorElement = document.getElementById(`${field}_error`);
                    if (errorElement) {
                        errorElement.textContent = messages[0];  // Display first error message for the field
                    }
                }
            }
        },
        error: function (xhr, status, error) {
            // Handle any errors
            console.error('AJAX Error:', error);
        }
    });
});

