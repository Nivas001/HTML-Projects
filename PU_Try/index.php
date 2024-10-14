<?php
session_start();
include 'db_connection.php'; // Make sure this file contains your database connection
include 'booking_modal.php';


$loggedIn = isset($_SESSION['role']);
$user_role = $loggedIn ? $_SESSION['role'] : null;

// Fetch all venues
$venueQuery = "SELECT v.* FROM venue v";
$venueResult = $db->query($venueQuery);
$venues = $venueResult->fetch_all(MYSQLI_ASSOC);

// Get features
$features = ['wifi', 'ac', 'projector', 'audio_system', 'smart_board', 'black_board'];

// Fetch departments with names
$deptQuery = "SELECT department_id, department_name FROM departments";
$deptResult = $db->query($deptQuery);
$departments = $deptResult->fetch_all(MYSQLI_ASSOC);

// Define time slots
$timeSlots = [
    '09:30', '10:30', '11:30', '12:30', '01:30', '02:30', '03:30', '04:30'
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondicherry University - Hall Booking System</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
body{
    padding:0px;
    margin:0px;
}

.user-info {
    background-color: #7dbcd3;
    padding: 5px;
    margin: 0;
    display: flex;
    justify-content: flex-end;
}
form{
    box-shadow:none;
    padding:0px;
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
/* Grid layout for displaying halls */
.halls-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}


.hall-card {
    background-color: #ffffff;
    border: 1px solid #27007a;
    border-radius:25px;
    padding: 10px;
    text-align: center;
}
.filter-btn {
    background-color: #007bff; /* Blue color */
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease, transform 0.2s ease; /* For smooth hover transition */
    margin-top: 10px;
  
}

/* Hover effect */
.filter-btn:hover {
    background-color: #0056b3; /* Darker blue on hover */
    transform: scale(1.05); /* Slightly enlarge the button on hover */
}
/* Existing styles */
.booking-area {
    flex-grow: 1;
    margin-left: 20px;
}

.booking-form {
    padding: 20px;
    margin-bottom: 20px;
}

.booking-form input, .booking-form select {
    margin-bottom: 10px;
}

/* Grid layout for displaying halls */
.halls-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}
/* Form alignment and radio button styling */
.booking-form1 {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 30px;
}

.left-section, .right-section {
    display: flex;
    flex-direction: column;
    flex-basis: 45%; /* Takes up 45% of the form width */
}

.form-group {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.form-group label {
    min-width: 100px;
    margin-right: 10px;
}

.date-inputs input[type="date"] {
    width: auto;
    padding: 2px 5px;
    height: 28px;
    font-size: 14px;
}

.nav-radio-button {
    display: flex;
    align-items: center;
    margin-right: 10px;
    cursor: pointer;
}

.nav-radio-button input[type="radio"] {
    margin-right: 5px;
}

#slot-options select {
    padding: 5px;
    font-size: 14px;
}

.form-group label {
    min-width: 150px; /* Consistent width for labels */
    text-align: left;
}

.form-group input[type="date"], .form-group select {
    flex: 1;
    padding: 2px 5px; /* Adjust padding */
    height: 28px; /* Set a smaller height */
    font-size: 14px; /* Adjust font size to match the smaller input */
    border-radius: 4px; /* Optional: give it slightly rounded corners */
    border: 1px solid #ccc; /* Optional: set border style */
    margin-bottom:0px;
}

.date-inputs {
    display: flex;
    gap: 10px; /* Add spacing between date inputs */
}

.nav-buttons {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.room-type-label {
    min-width: 150px;
}

/* Radio Button and Label Styling */
.nav-radio-button {
    display: flex;
    align-items: center;
    justify-content:flex-start;
    gap: 10px; /* Space between radio button and text */
    margin-right: 35px; /* Add space between radio button groups */
}

.nav-radio-button input[type="radio"] {
    accent-color: #c00000; /* Customize the color of the radio button */
    margin: 0; /* Remove default margin to align vertically */
    vertical-align: middle; /* Ensure radio button is vertically centered */
}

.nav-radio-button span {
    font-size: 14px;
    color: #333;
    vertical-align: middle; /* Ensure text is vertically centered with the radio button */
}

    </style>


<script>
  function toggleTimeOptions(option) {
    const sessionOptions = document.getElementById('session-options');
    const slotOptions = document.getElementById('slot-options');
    
    if (option === 'session') {
      sessionOptions.style.display = 'block';
      slotOptions.style.display = 'none';
    } else {
      sessionOptions.style.display = 'none';
      slotOptions.style.display = 'block';
    }
  }
</script>
</head>
<body>
   <?php include "header.php"; ?>
    
    

<div id="main-container">
    <div class="room-details">

    <div class="main-content">
        <div class="filters">
            <h4>Filters </h4>
            <label><input type="radio" name="capacity" value="less_than_50" onchange="filterRooms()"> Less than 50</label>
            <label><input type="radio" name="capacity" value="50_to_100" onchange="filterRooms()"> 50 to 100</label>
            <label><input type="radio" name="capacity" value="more_than_100" onchange="filterRooms()"> More than 100</label><br>
            <?php foreach ($features as $feature): ?>
                <label>
                    <input type="checkbox" name="feature" value="<?php echo $feature; ?>" onchange="filterRooms()">
                    <?php echo ucwords(str_replace('_', ' ', $feature)); ?>
                </label>
            <?php endforeach; ?>
            
            <button type="button" class="filter-btn" onclick="clearFilters()">Clear</button>
        </div>
        
        <div class="booking-area">
    <div class="booking-form">
        <div class="nav-buttons">
            <label class="room-type-label">Room Type:</label>
            <?php 
            $orderedRoomTypes = [
                ['hall_type' => 'seminar hall'],
                ['hall_type' => 'lecture hall room'],
                ['hall_type' => 'auditorium'],        
                ['hall_type' => 'conference hall']
            ];

            foreach ($orderedRoomTypes as $type): ?>
                <label class="nav-radio-button">
                    <input type="radio" name="room_type" value="<?php echo $type['hall_type']; ?>" 
                           <?php echo $type['hall_type'] === 'seminar hall' ? 'checked' : ''; ?>
                           onchange="filterRooms('<?php echo $type['hall_type']; ?>')">
                    <span><?php echo strtoupper($type['hall_type']); ?></span>
                </label>
            <?php endforeach; ?>
        </div>     

        <div class="booking-form1">
    <!-- Left side: Date and Department -->
    <div class="left-section">
        <div class="form-group">
            <label for="booking_start_date">Date:</label>
            <div class="date-inputs">
                <input type="date" id="booking_start_date" name="booking_start_date" required> -
                <input type="date" id="booking_end_date" name="booking_end_date" required>
            </div>
        </div>

        <div class="form-group">
            <label for="department">Department:</label>
            <select style="max-width :250px;" name="department_id" id="department" required>
                <option value="">Select a Department</option>
                <?php
                // Fetch department names and IDs from the database
                $query = "SELECT department_id, department_name FROM departments";
                $result = $db->query($query);

                if (!$result) {
                    die("Query failed: " . $db->error);
                }

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['department_id']) . "'>" . htmlspecialchars($row['department_name']) . "</option>";
                    }
                } else {
                    echo "<option value=''>No departments available</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <!-- Right side: Session and Time Slots -->
    <div class="right-section">
       
</div>

    </div>
    <!-- Div to display available auditoriums -->
    <div id="availableAuditoriums"></div>

            <div class="halls-grid">
                <?php foreach ($venues as $venue): ?>
                    <div class="hall-card" data-type="<?php echo $venue['hall_type']; ?>" data-capacity="<?php echo $venue['capacity']; ?>"
                         <?php foreach ($features as $feature): ?>
                            data-<?php echo $feature; ?>="<?php echo isset($venue[$feature]) ? $venue[$feature] : '0'; ?>"
                         <?php endforeach; ?>
                    >
                        <?php if ($venue['hall_type'] == "auditorium"): ?>
                        <?php endif; ?>
                        <h4><?php echo $venue['hall_name']; ?></h4>
                        <p>Capacity: <?php echo $venue['capacity']; ?></p>
                        <?php if (!empty($venue['complex_name'])): ?>
                            <p>Complex: <?php echo $venue['complex_name']; ?></p>
                        <?php endif; ?>
                        <?php if ($venue['hall_type'] == "auditorium"): ?>
                            <p>Cost per session: <?php echo $venue['cost_per_session']; ?></p>
                        <?php endif; ?>
                        <a class="btn btn-outline-secondary view-room" 
                            href="room_details.php?hall_id=<?php echo $venue['hall_id']; ?>&type=<?php echo urlencode($venue['hall_type']); ?>">
                            View
                        </a>
<?php if ($loggedIn): ?>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal1" data-roomid="<?php echo $venue['hall_id']; ?>" data-roomname="<?php echo $venue['hall_name']; ?>" onclick="event.stopPropagation(); fillModalWithData(this);">Book</button>

<?php else: ?>
    <button class="btn btn-primary" onclick="event.stopPropagation(); window.location.href='login.html';">Book</button>
<?php endif; ?>

                  
                    </div>
                    
                <?php endforeach; ?>
            </div>
        </div>
        </div>
       
</div>
        </div>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function toggleTimeOptions(option) {
    const sessionOptions = document.getElementById('session-options');
    const slotOptions = document.getElementById('slot-options');
    
    if (option === 'session') {
        sessionOptions.style.display = 'flex';
        slotOptions.style.display = 'none';
    } else {
        sessionOptions.style.display = 'none';
        slotOptions.style.display = 'flex';
    }
}

function fillModalWithData(button) {
    // Get the room ID and room name from the button's data attributes
    var roomId = button.getAttribute('data-roomid');
    var roomName = button.getAttribute('data-roomname');
    
    // Log the values to ensure they are being passed correctly
    console.log("Room ID: ", roomId);
    console.log("Room Name: ", roomName);

    // Set the room ID in the hidden input field inside the modal
    document.getElementById('hall_id').value = roomId;  // Set the room ID
    document.getElementById('modalRoomName').innerText = roomName;  // Set the room name in modal title
}

        let selectedRoomType = null; // Variable to store selected room type

    function filterRooms(type = null) {
        const halls = document.querySelectorAll('.hall-card');
        const selectedFeatures = Array.from(document.querySelectorAll('input[name="feature"]:checked')).map(cb => cb.value);
        const selectedCapacities = Array.from(document.querySelectorAll('input[name="capacity"]:checked')).map(cb => cb.value);
        if (type) {
        selectedRoomType = type;
    }
        halls.forEach(hall => {
            let showHall = true;

            // Filter by type
            if (selectedRoomType && hall.dataset.type !== selectedRoomType) {
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

function clearFilters() {
    // Clear all radio buttons for capacity
    const capacityRadios = document.querySelectorAll('input[name="capacity"]');
    capacityRadios.forEach((radio) => {
        radio.checked = false;
    });

    // Clear all checkboxes for features
    const featureCheckboxes = document.querySelectorAll('input[name="feature"]');
    featureCheckboxes.forEach((checkbox) => {
        checkbox.checked = false;
    });

    // Reset the room display by calling filterRooms without any specific type
    filterRooms(); // This will reset the room list based on no filters
}
</script>

</body>
</html>