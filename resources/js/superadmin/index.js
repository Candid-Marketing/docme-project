
// Your custom JavaScript for the dashboard
document.addEventListener('DOMContentLoaded', function () {
    // Example: jQuery code for the date picker
    $('#dateFilter').change(function () {
        var selectedValue = $(this).val();
        if (selectedValue == 'custom-date') {
            $('#customDateWrapper').show();
        } else {
            $('#customDateWrapper').hide();
        }
    });
});


document.addEventListener("DOMContentLoaded", function() {
    var ctx = document.getElementById('userRegistrationChart').getContext('2d');
    var userRegistrationChart = new Chart(ctx, {
        type: 'line', // Line chart for the registration data
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'], // Labels for the weeks of the month
            datasets: [
                {
                    label: 'Series 1 - User Registrations',
                    data: [50, 60, 70, 55], // Static data for Series 1 (number of registrations per week)
                    borderColor: 'rgba(75, 192, 192, 1)',  // Line color for Series 1
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',  // Area color for Series 1
                    fill: true,
                },
                {
                    label: 'Series 2 - User Registrations',
                    data: [40, 50, 60, 65], // Static data for Series 2
                    borderColor: 'rgba(153, 102, 255, 1)',  // Line color for Series 2
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',  // Area color for Series 2
                    fill: true,
                },
                {
                    label: 'Series 3 - User Registrations',
                    data: [30, 40, 50, 45], // Static data for Series 3
                    borderColor: 'rgba(255, 159, 64, 1)',  // Line color for Series 3
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',  // Area color for Series 3
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true  // Ensure the y-axis starts at 0
                }
            }
        }
    });
});


// Get references to the user profile and popup menu
const userProfile = document.getElementById('userProfile');
const popupMenu = document.getElementById('popupMenu');

// Toggle the popup menu on click
userProfile.addEventListener('click', function () {
    const isVisible = popupMenu.style.display === 'block';
    popupMenu.style.display = isVisible ? 'none' : 'block';
});

// Close the popup if clicking outside of it
document.addEventListener('click', function (event) {
    if (!userProfile.contains(event.target) && !popupMenu.contains(event.target)) {
        popupMenu.style.display = 'none';
    }
});
