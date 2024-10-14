<?php
session_start();
include 'db_connection.php'; // Ensure this file contains your database connection

// Check if user is logged in and retrieve user ID and role
$loggedIn = isset($_SESSION['user_id']) && isset($_SESSION['role']);
$user_id = $loggedIn ? $_SESSION['user_id'] : null;
$user_role = $loggedIn ? $_SESSION['role'] : null; // Store role from session

// Get distinct room types for any dropdowns or filters
$typeQuery = "SELECT DISTINCT hall_type FROM venue";
$typeResult = $db->query($typeQuery);
$roomTypes = $typeResult->fetch_all(MYSQLI_ASSOC);

// Modify the query to fetch only the bookings for the logged-in user
$query = "
  SELECT 
    b.*, 
    r.*,
    d.department_name,
    u.username
FROM 
    bookings_pu b
JOIN 
    venue r ON b.hall_id = r.hall_id
LEFT JOIN 
    departments d ON r.department_id = d.department_id
LEFT JOIN 
    users u ON b.user_id = u.user_id
WHERE 
    b.status != 'cancelled' 
    AND b.user_id = ?"; // Filter by user_id

// Prepare the statement
$stmt = $db->prepare($query);
if ($stmt === false) {
    die("Error preparing the statement: " . $db->error); // Show the error message
}

// Bind parameters
$stmt->bind_param("i", $user_id); // Bind the user_id parameter for both queries

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Close connection
$stmt->close();
$db->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondicherry University - Hall Booking System</title>
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f0f0;
}

.header {
    background-color: #0071c1;
    color: white;
    padding: 10px;
    display: flex;
    align-items: center;
    position: relative;
}

.header img {
    height: 60px;
}

.header h2 {
    margin: 0;
    flex-grow: 1;
    text-align: center;
}

.user-info {
    background-color: #7dbcd3;
    padding: 5px;
    margin: 0;
    display: flex;
    justify-content: flex-end;
}

.user-info li {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
}

.user-info .nav-item {
    margin-left: 20px;
}

.nav-link {
    text-align: right;
}

.nav-buttons {
    display: flex;
    justify-content: space-around;
    background-color: #e0e0e0;
    padding: 10px;
}

.nav-button {
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    background-color: #0071c0;
    color: white;
    border-radius: 20px;
}

.nav-button.active {
    background-color: #ff6600;
}

.main-content {
    display: flex;
    margin: 20px;
}

.filters label {
    display: flex;
    align-items: center;
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    padding: 5px 5px;
    cursor: pointer;
    transition: box-shadow 0.3s;
}

.filters label:hover {
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
}

.filters input[type="checkbox"] {
    width: 16px;
    height: 16px;
    border-radius: 3px;
    border: 2px solid #00a2e8;
    margin-right: 10px;
    cursor: pointer;
    transition: background-color 0.3s, border-color 0.3s;
}

.filters input[type="checkbox"]:checked {
    background-color: #00a2e8;
    border-color: #007bbf;
}

.booking-area {
    flex-grow: 1;
    margin-left: 20px;
}

.booking-form {
    background-color: #ffffff;
    padding: 20px;
    margin-bottom: 20px;
}

.booking-form input, .booking-form select {
    margin-bottom: 10px;
}

.halls-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

.hall-card {
    background-color: #ffefd5;
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

.nav-container {
    background-color: #016d77;
    padding: 10px 20px;
    display: flex;
    justify-content: center;
}

.nav-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 100px;
}

.nav-list li a {
    color: white;
    font-weight: 100px;
    text-decoration: none;
    font-size: 18px;
    transition: color 0.3s;
}

.nav-list li a:hover {
    color: #ffcc00;
}

.nav-list li a.active {
    color: #ffcc00;
}
button, .nav-list li a {
    font-size: 16px;  /* Same font size for both */
    font-weight: 500; /* Slightly bold */
    color: white;     /* White text */
    text-decoration: none; /* Remove underline from links */
    background: none; /* No background for list items */
    border: none; /* Remove borders from buttons */
    cursor: pointer; /* Pointer on hover for both */
    transition: color 0.3s; /* Smooth hover effect */
}
    </style>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Show booking details in a modal
            $('.edit-booking').on('click', function() {
                const bookingId = $(this).data('id');
                const roomName = $(this).data('room');
                const startDate = $(this).data('start');
                const endDate = $(this).data('end');
                const status = $(this).data('status');
                const departmentName = $(this).data('department');

                // Populate the modal form with current booking details
                $('#bookingId').val(bookingId);
                $('#roomName').val(roomName);
                $('#startDate').val(startDate);
                $('#endDate').val(endDate);
                $('#status').val(status);
                $('#departmentName').val(departmentName);

                // Show modal
                $('#bookingDetailsModal').show();
            });

            // Hide modal
            $('.close-modal').on('click', function() {
                $('#bookingDetailsModal').hide();
            });

            // Handle form submission for updating booking
            $('#editBookingForm').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                
                $.post('edit_booking.php', formData, function(response) {
                    alert('Booking updated successfully!');
                    location.reload(); // Refresh the page to see changes
                }).fail(function() {
                    alert('Error updating booking. Please try again.');
                });
            });
        });
    </script>
    <style>
        /* Simple CSS for modal */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4); 
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
        }
        .close-modal {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close-modal:hover,
        .close-modal:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    table {
        margin: 0 auto;
        border-collapse: collapse;
        width: 80%;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
        text-align: center;
    }

    /* Alternating row colors */
   

    /* Align the "Actions" column for icons */
    td.actions {
        text-align: center;
        width: 80px;
    }

    
    .cancel-icon {
        color: red;
        text-decoration:none;
    }

        .edit-icon {
            margin-right: 10px;

            cursor: pointer;
            font-size: 18px;
            color: blue;
        }
      /* Form Group Styles */
/* Modal Background */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1000; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0, 0, 0); /* Fallback color */
    background-color: rgba(0, 0, 0, 0.5); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: 10% auto; /* 10% from the top and centered */
    padding: 20px; /* Padding around content */
    border: 1px solid #888;
    width: 300px; /* Set a smaller width for the modal */
    border-radius: 5px; /* Rounded corners */
}

/* Close button */
.close-modal {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}
.btn-modify{
    padding:5px;
    border-radius:10px;
}
/* Form Group Styles */
.form-group {
    display: flex; /* Use Flexbox for alignment */
    align-items: center; /* Align items vertically centered */
    margin-bottom: 10px; /* Reduced spacing */
}

.form-group label {
    flex: 1; /* Allow label to take equal space */
    margin-right: 10px; /* Space between label and input */
    font-weight: bold; /* Bold labels */
}

.form-group input,
.form-group select {
    flex: 2; /* Input and select take more space */
    padding: 5px; /* Reduced padding for a compact look */
    border: 1px solid #ccc; /* Border for inputs */
    border-radius: 4px; /* Rounded corners */
}

/* Button Styles */
.btn-update {
    background-color: #4CAF50; /* Green background */
    color: white; /* White text */
    padding: 10px; /* Padding */
    border: none; /* No border */
    border-radius: 5px; /* Rounded corners */
    cursor: pointer; /* Pointer on hover */
}

.btn-update:hover {
    background-color: #45a049; /* Darker green on hover */
}
.cancel-button {
    display: inline-block;
    margin-top:2px;
    margin-left:-5px;
    padding: 5px; /* Add padding for a button-like appearance */
    background-color: #ff4d4d; /* Light red background */
    color: white; /* White text */
    border: none; /* Remove borders */
    border-radius: 10px; /* Rounded corners */
    text-decoration: none; /* Remove underline */
    cursor: pointer; /* Pointer cursor */
    transition: background-color 0.3s; /* Smooth transition for hover effect */
}

.cancel-button:hover {
    background-color: #e60000; /* Darker red on hover */
}

    </style>
<script>
        $(document).ready(function() {
            // Show booking details in a modal
            $('.edit-booking').on('click', function() {
                const hall_type = $(this).data('hall_type');
                const bookingId = $(this).data('id');
                const roomName = $(this).data('room');
                const department_name = $(this).data('department_name');
                const startDate = $(this).data('start');
                const endDate = $(this).data('end');
                const status = $(this).data('status');
                const purpose = $(this).data('purpose');
                const purpose_name = $(this).data('purpose_name');
                const organiser_name = $(this).data('organiser_name');
                const organiser_mobile = $(this).data('organiser_mobile');
                const organiser_email = $(this).data('organiser_email');
                const organiser_department = $(this).data('organiser_department');

                // Populate the modal form with current booking details
                $('#hall_type').val(hall_type);
                $('#bookingId').val(bookingId);
                $('#roomName').val(roomName);
                $('#department_name').val(department_name);
                $('#startDate').val(startDate);
                $('#endDate').val(endDate);
                $('#status').val(status);
                $('#purpose').val(purpose);
                $('#purpose_name').val(purpose_name);
                $('#organiser_name').val(organiser_name);
                $('#organiser_mobile').val(organiser_mobile);
                $('#organiser_email').val(organiser_email);
                $('#organiser_department').val(organiser_department);

                // Show modal
                $('#bookingDetailsModal').show();
            });

            // Hide modal
            $('.close-modal').on('click', function() {
                $('#bookingDetailsModal').hide();
            });

            // Handle form submission for updating booking
            $('#editBookingForm').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                
                $.post('edit_booking.php', formData, function(response) {
                    alert('Booking updated successfully!');
                    location.reload(); // Refresh the page to see changes
                }).fail(function() {
                    alert('Error updating booking. Please try again.');
                });
            });
        });
    </script>
</head>
<body>
<?php include 'header.php'?>

<div class="main-content">

<table border="1" width="80%">
<tr>
<th>Booked Date</th>
<th>Hall Type & Name of The Hall</th>
    <th>Purpose & Name</th>
    <th>Date & Slot/Session</th>
    <th>Booked By</th>
    <th>Status</th>
    <th>Actions</th>


</tr>

<?php while ($row = $result->fetch_assoc()): ?>
    <tr>
    <td>
            <?php 
                  echo date("d-m-Y", strtotime($row['booking_date'])) . "  <br>";
            ?>
        </td>
        
        <td>   
        <b> <?php echo ucwords($row['hall_type']);?> </b> (<?php echo strtoupper($row['hall_name']);?>)
       <h4 style="line-height:0px;font-weight: normal;"><?php echo $row['department_name'];?></h4>    
        </td>
        <td><b><?php echo ucwords($row['purpose']);?></b><br>
        <?php echo $row['purpose_name'];?></td>
        <td style="color:#08005f;"><center>
            <?php 
            $booking_id = $row['booking_id'];
                if ($row['start_date'] == $row['end_date']) {
                    echo date("d-m-Y", strtotime($row['start_date'])) . "<br>";
                } else {
                    echo date("d-m-Y", strtotime($row['start_date'])) . "<br> to <br>" . date("d-m-Y", strtotime($row['end_date'])) . "<br>";
                }
                $booked_slots_string = $row['slot_or_session']; 
$booked_slots = array_map('intval', explode(',', $booked_slots_string)); // Convert to an array of integers
sort($booked_slots); 


$forenoon_slots = [1, 2, 3, 4]; // Forenoon slots
$afternoon_slots = [5, 6, 7, 8]; // Afternoon slots
$full_day_slots = [1, 2, 3, 4, 5, 6, 7, 8]; // Full Day slots

// Map of slot numbers to time strings
$slot_timings = [
1 => '9:30 AM - 10:30 AM',
2 => '10:30 AM - 11:30 AM',
3 => '11:30 AM - 12:30 PM',
4 => '12:30 PM - 1:30 PM',
5 => '1:30 PM - 2:30 PM',
6 => '2:30 PM - 3:30 PM',
7 => '3:30 PM - 4:30 PM',
8 => '4:30 PM - 5:30 PM'
];


$booking_type = '';
$booked_timings = '';


if ($booked_slots === $full_day_slots) {
$booking_type = "FN & AN";
} elseif ($booked_slots === $forenoon_slots) {
$booking_type = "Forenoon";
} elseif ($booked_slots === $afternoon_slots) {
$booking_type = "Afternoon";
} else {
// If it's a custom slot, show only the booked timings
$booked_timings = implode("<br>", array_map(function($slot) use ($slot_timings) {
    return $slot_timings[$slot];
}, $booked_slots));

// Modify the booking type to include booked timings on the next line
$booking_type = "<br>" . $booked_timings;
}

// Output the booking type
echo $booking_type;

            ?>
            
        </td></center>
 </td>
        <td><b><span style="color:#5f000a;"><?php echo $row['organiser_department'];?></span></b><br>
       <span style="color:#08005f;"><?php echo $row['organiser_name'];?><br></span> 
        <?php echo $row['organiser_mobile'];?><br>
        <?php echo $row['organiser_email'];?>
        </td>
        <td><?php echo htmlspecialchars($row['status']); ?></td>
        <td class="actions">
            <span class="edit-icon edit-booking" 
                  data-hall_type="<?php echo $row['hall_type']; ?>" 
                  data-id="<?php echo $row['booking_id']; ?>" 
                  data-room="<?php echo htmlspecialchars($row['hall_name']); ?>" 
                  data-start="<?php echo htmlspecialchars($row['start_date']); ?>" 
                  data-end="<?php echo htmlspecialchars($row['end_date']); ?>" 
                  data-status="<?php echo htmlspecialchars($row['status']); ?>" 
                  data-purpose="<?php echo htmlspecialchars($row['purpose']); ?>" 
                  data-purpose_name="<?php echo htmlspecialchars($row['purpose_name']); ?>" 
                  data-organiser_name="<?php echo htmlspecialchars($row['organiser_name']); ?>" 
                  data-organiser_mobile="<?php echo htmlspecialchars($row['organiser_mobile']); ?>" 
                  data-organiser_email="<?php echo htmlspecialchars($row['organiser_email']); ?>" 
                  data-organiser_department="<?php echo htmlspecialchars($row['organiser_department']); ?>">
                  
                  <?php if ($row['status'] == 'pending') : ?>
                    <center><button style="background-color:#228512;padding:5px" class="btn-modify" onclick="openEditBookingModal(this)">Modify</button><br>
    <?php endif; ?></center>
</span>

<a href="javascript:void(0);" class="cancel-button" onclick="openCancelModal(<?php echo $row['booking_id']; ?>)">
Cancel
</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
<!-- Edit Booking Modal -->
<div id="bookingDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeBookingDetailsModal()">&times;</span>
        <center><h3>Edit Booking</h3></center>
        <form id="editBookingForm" onsubmit="return handleEditBooking(event);">
        <input type="hidden" name="hall_type" id="hall_type">
        <p id="hallTypeDisplay"></p>
            <input type="hidden" name="booking_id" id="bookingId">
            <div class="form-group">
                <label for="roomName">Room Name:</label>
                <input type="text" id="roomName" name="name" readonly>
            </div>
           

            <div class="form-group">
                <label for="startDate">Start Date:</label>
                <input type="date" id="startDate" name="start_date" required>
            </div>
            <div class="form-group">
                <label for="endDate">End Date:</label>
                <input type="date" id="endDate" name="end_date" required>
            </div>
             <div class="form-group">
                <label for="purpose">Purpose Type:</label>
                <select id="purpose" name="purpose" >
                    <option value="class">Class</option>
                    <option value="event">Event</option>
                    
                </select>
            </div>
            <div class="form-group">
                <label for="purpose_name">Purpose:</label>
                <input type="text" id="purpose_name" name="purpose_name" required>
            </div>
            <div class="form-group">
                <label for="organiser_name">Organiser Name:</label>
                <input type="text" id="organiser_name" name="organiser_name" required>
            </div>
            <div class="form-group">
                <label for="organiser_mobile">Organiser Mobile:</label>
                <input type="text" id="organiser_mobile" name="organiser_mobile" required>
            </div>
            <div class="form-group">
                <label for="organiser_email">Organiser Email:</label>
                <input type="text" id="organiser_email" name="organiser_email" required>
            </div>
            <div class="form-group">
                <label for="organiser_department"> Organiser Department</label>
                <input type="text" id="organiser_department" name="organiser_department" required>
            </div>


            <!-- <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" disabled>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div> -->
            <center><button type="submit" class="btn-update">Update Booking</button></center>
        </form>
    </div>
</div>

<!-- Cancel Booking Modal -->
<div id="cancelModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
       <center> <h3>Cancel Booking</h3></center>
        <form onsubmit="return handleCancelBooking(event);">
            <input type="hidden" name="booking_id" id="cancelBookingId">
            <div class="form-group">
                <label for="reason">Reason for Cancellation:</label>
                <select name="reason" id="reason" required>
                    <option value="" disabled selected>Select a reason</option>
                    <option value="Change of plans">Change of plans</option>
                    <option value="Scheduling conflict">Scheduling conflict</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <textarea name="other_reason" id="other_reason" placeholder="Please specify..." style="display:none; width: 100%; height: 50px; resize: vertical;">                </textarea>
            </div>
            <center><button type="submit" class="btn-update">Submit</button></center>
        </form>
    </div>
</div>
<script>
function openEditBookingModal(bookingId) {
    // Fetch booking details and populate the form
    fetch(`get_booking_details.php?id=${bookingId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('bookingId').value = data.booking_id;
            document.getElementById('roomName').value = data.name;
            document.getElementById('startDate').value = data.start_date;
            document.getElementById('endDate').value = data.end_date;
            document.getElementById('status').value = data.status;
            document.getElementById('bookingDetailsModal').style.display = 'block';
        })
        .catch(error => console.error('Error:', error));
}

function closeBookingDetailsModal() {
    document.getElementById('bookingDetailsModal').style.display = 'none';
}

function handleEditBooking(event) {
    event.preventDefault();
    const form = document.getElementById('editBookingForm');
    const formData = new FormData(form);

    fetch('edit_booking.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes('successfully')) {
            alert('Booking updated successfully!');
            location.reload(); // Refresh the page to see changes
        } else {
            alert('Error updating booking. Please try again.');
        }
        closeBookingDetailsModal();
    })
    .catch(error => console.error('Error:', error));
}

function openCancelModal(bookingId) {
    document.getElementById('cancelBookingId').value = bookingId;
    document.getElementById('cancelModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('cancelModal').style.display = 'none';
}

document.getElementById('reason').addEventListener('change', function() {
    var otherReasonTextarea = document.getElementById('other_reason');
    if (this.value === 'Other') {
        otherReasonTextarea.style.display = 'block';
        otherReasonTextarea.required = true;
    } else {
        otherReasonTextarea.style.display = 'none';
        otherReasonTextarea.required = false;
        otherReasonTextarea.value = '';
    }
});

function handleCancelBooking(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    fetch('cancel_booking.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes('successfully')) {
            alert('Booking cancelled successfully!');
            location.reload(); // Refresh the page to see changes
        } else {
            alert('Error cancelling booking. Please try again.');
        }
        closeModal();
    })
    .catch(error => console.error('Error:', error));
}
function filterRooms(type = null) {
        const halls = document.querySelectorAll('.hall-card');
        const selectedFeatures = Array.from(document.querySelectorAll('input[name="feature"]:checked')).map(cb => cb.value);
        const selectedCapacities = Array.from(document.querySelectorAll('input[name="capacity"]:checked')).map(cb => cb.value);

        halls.forEach(hall => {
            let showHall = true;

            // Filter by type
            if (type && hall.dataset.type !== type) {
                showHall = false;
            }

            // Filter by features
            if (showHall && selectedFeatures.length > 0) {
                showHall = selectedFeatures.every(feature => hall.dataset[feature] === '1');
            }

            // Filter by capacity
            if (showHall && selectedCapacities.length > 0) {
                const capacity = parseInt(hall.dataset.capacity);
                showHall = selectedCapacities.some(range => {
                    if (range === 'less_than_50') return capacity < 50;
                    if (range === '50_to_100') return capacity >= 50 && capacity <= 100;
                    if (range === 'more_than_100') return capacity > 100;
                    return false;
                });
            }

            hall.style.display = showHall ? 'block' : 'none';
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const buttons = document.querySelectorAll('.nav-button');
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                buttons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            });
        });
        filterRooms('seminar hall'); // Default to show auditorium rooms
    });
</script><script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.view-room').click(function(event) {

        // Get the URL from the data attribute
        var url = $(this).data('url');
        console.log("URL:", url); // Log the URL to the console
        // Perform AJAX request to fetch the full page content
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                // Replace the content of the main-container with the response
                $('#main-container .room-details').html(response);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status + error);
            }
        });
    });
});
</script>

</body>
</html>


