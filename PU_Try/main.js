document.addEventListener('DOMContentLoaded', function() {
    // Handle form submissions
    const bookingForm = document.querySelector('#booking-form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitBookingForm();
        });
    }

    // Dynamic updates for booking form
    const roomSelect = document.querySelector('#room-select');
    if (roomSelect) {
        roomSelect.addEventListener('change', function() {
            fetchRoomDetails(roomSelect.value);
        });
    }

    // Validate no overlap in booking slots
    function validateSlotOverlap(startTime, endTime) {
        // Placeholder function to validate slot overlap
        // You will need to implement actual validation logic
        return true;
    }

    // Fetch room details dynamically
    function fetchRoomDetails(roomId) {
        fetch('ajax_handler.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=getRoomDetails&roomId=${roomId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector('#room-details').innerHTML = data.details;
            }
        })
        .catch(error => console.error('Error fetching room details:', error));
    }

    // Submit booking form
    function submitBookingForm() {
        const formData = new FormData(bookingForm);
        fetch('booking_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Booking successful!');
                bookingForm.reset();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error submitting booking form:', error));
    }
});
window.onload = function () {
    console.log('Page loaded');

    // Navigation Links Event Listeners
    document.getElementById('seminar-link').addEventListener('click', function () {
        console.log('Seminar Hall clicked');
        toggleFilters(true); // Show filters for seminar halls
        fetchRoomData('seminar'); // Fetch all seminar rooms by default
    });

    document.getElementById('auditorium-link').addEventListener('click', function () {
        console.log('Auditorium clicked');
        toggleFilters(false); // Hide filters for auditoriums
        fetchRoomData('auditorium');
    });

    document.getElementById('lecture-hall-link').addEventListener('click', function () {
        console.log('Lecture Hall Complex clicked');
        toggleFilters(false); // Hide filters for lecture halls
        fetchRoomData('lecture');
    });

    // Handle Filters (for Seminar Halls)
    const checkboxes = document.querySelectorAll('input[name="features"]');
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            console.log('Filter changed');
            fetchFilteredRooms('seminar'); // Fetch rooms based on selected filters
        });
    });

    // Show/Hide Filter Sidebar
    function toggleFilters(show) {
        const filterSidebar = document.getElementById('filter-sidebar');
        filterSidebar.style.display = show ? 'block' : 'none';
    }

    // Fetch Rooms Based on Selected Filters
    function fetchFilteredRooms(roomType) {
        let selectedFilters = [];
        checkboxes.forEach(function (checkbox) {
            if (checkbox.checked) {
                selectedFilters.push(checkbox.value);
            }
        });
        console.log('Selected filters:', selectedFilters);
        fetchRoomData(roomType, selectedFilters); // Fetch rooms based on selected filters
    }

    // Fetch Room Data (Seminar, Auditorium, Lecture)
    function fetchRoomData(roomType, filters = []) {
        console.log('Fetching data for', roomType, 'with filters', filters);
        let url = `fetch_rooms.php?type=${roomType}`;
        if (filters.length > 0) {
            url += `&filters=${filters.join(',')}`;
        }

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error fetching room data');
                }
                return response.text();
            })
            .then(data => {
                document.getElementById('room-list').innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
    }

    // Load Auditoriums by default on page load
    toggleFilters(false); // Hide filters on load
    fetchRoomData('auditorium');
};
