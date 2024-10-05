<?php
session_start();
include 'db_connection.php'; // Include database connection

// Determine if the user is logged in
$loggedIn = isset($_SESSION['role']);
$user_role = $loggedIn ? $_SESSION['role'] : null;

// Set the room type based on the URL parameter, default to 'all'
$room_type = isset($_GET['type']) ? $_GET['type'] : 'all';
$auditoriums = ($room_type === 'auditorium' || $room_type === 'all') ?  
    $db->query("SELECT a.*, u.username AS in_charge_name 
                FROM auditoriums a 
                JOIN users u ON a.in_charge_id = u.user_id")->fetch_all(MYSQLI_ASSOC) : [];
$seminar_halls = ($room_type === 'seminar_hall' || $room_type === 'all') ? 
    $db->query("SELECT sh.*, u.username AS in_charge_name 
                 FROM seminar_halls sh 
                 JOIN users u ON sh.in_charge_id = u.user_id")->fetch_all(MYSQLI_ASSOC) : [];
$complexes = ($room_type === 'lecture_hall_complex' || $room_type === 'all') ? 
    $db->query("SELECT lhc.*, u.username AS in_charge_name 
                FROM lecture_hall_complex lhc 
                JOIN users u ON lhc.in_charge_id = u.user_id")->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Room Booking System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your custom CSS -->
    <style>
        
#filterForm {
    border: 1px solid #ccc; /* Add a border */
    padding: 15px; /* Increase padding */
    border-radius: 5px; /* Optional: add rounded corners */
    background-color: #f9f9f9;
    width:250px; /* Light background color */
}
        </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Pondicherry University</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php?type=auditorium">Auditorium</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?type=seminar_hall">Seminar Hall</a></li>
                <li class="nav-item"><a class="nav-link" href="complex.php">Lecture Hall Complex</a></li>
                
                <?php if ($loggedIn): ?>
                    <li class='nav-item'><a class='nav-link' href='logout.php'>Logout</a></li>
                <?php else: ?>
                    <li class='nav-item'><a class='nav-link' href='login.html'>Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <?php if ($room_type === 'auditorium' || $room_type === 'all'): ?>
        <h2>Auditoriums</h2>
        <div class="row">
            <?php foreach ($auditoriums as $auditorium): ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4"> <!-- Responsive column -->
                    <div class="card" onclick="location.href='room_details.php?room_id=<?php echo $auditorium['room_id']; ?>&type=auditorium'">
                        <div class="row no-gutters">
                            <div class="col-md-4">
                                <img src="<?php echo $auditorium['image']; ?>" class="card-img" alt="<?php echo $auditorium['room_name']; ?>" style="width: 100%; height: 200px; object-fit: cover;">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $auditorium['room_name']; ?></h5>
                                    <p class="card-text">Location: <?php echo $auditorium['location']; ?></p>
                                    <p class="card-text">In-Charge: <?php echo $auditorium['in_charge_name']; ?></p>
                                    <p class="card-text">Features: <?php echo $auditorium['features']; ?></p>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal" data-roomid="<?php echo $auditorium['room_id']; ?>" data-roomname="<?php echo $auditorium['room_name']; ?>" onclick="event.stopPropagation();">Book Now</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($room_type === 'seminar_hall'): ?>
    
        <div class="mb-6 d-flex">
        <div class="me-4">
            <h5>Filter by Features</h5>
            <form id="filterForm">
                <div>
                    <input type="checkbox" value="Wi-Fi" id="wifi" class="feature">
                    <label for="wifi">Wi-Fi</label><br>
                    <input type="checkbox" value="Projector" id="projector" class="feature">
                    <label for="projector">Projector</label><br>
                    <input type="checkbox" value="Smart Board" id="smart_board" class="feature">
                    <label for="smart_board">Smart Board</label><br>
                    <input type="checkbox" value="Blackboard" id="blackboard" class="feature">
                    <label for="blackboard">Blackboard</label><br>
                    <input type="checkbox" value="AC" id="ac" class="feature">
                    <label for="ac">AC</label><br>
                    <input type="checkbox" value="Microphone" id="microphone" class="feature">
                    <label for="microphone">Microphone</label><br>
                    <input type="checkbox" value="Video Conferencing" id="video_conferencing" class="feature">
                    <label for="video_conferencing">Video Conferencing</label><br>
                </div>
            </form>
        </div>

        <div class="flex-grow-1">
            <h2>Seminar Halls</h2>
                <div class="row" id="seminarHallContainer">
                    <?php foreach ($seminar_halls as $seminar_hall): ?>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="card" onclick="location.href='room_details.php?room_id=<?php echo $seminar_hall['room_id']; ?>&type=seminar_hall'">
                                <div class="row no-gutters">
                                    <div class="col-md-4">
                                        <img src="<?php echo $seminar_hall['image']; ?>" class="card-img" alt="<?php echo $seminar_hall['room_name']; ?>" style="width: 100%; height: 200px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $seminar_hall['room_name']; ?></h5>
                                            <p class="card-text">Location: <?php echo $seminar_hall['location']; ?></p>
                                            <p class="card-text">In-Charge: <?php echo $seminar_hall['in_charge_name']; ?></p>
                                            <p class="card-text">Features: <?php echo $seminar_hall['features']; ?></p>
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal" data-roomid="<?php echo $seminar_hall['room_id']; ?>" data-roomname="<?php echo $seminar_hall['room_name']; ?>" onclick="event.stopPropagation();">Book Now</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
        </div>   
    <?php endif; ?>

    <?php if ($room_type === 'all'): ?>
        <div class="flex-grow-1">
            <h2>Seminar Halls</h2>
                <div class="row" id="seminarHallContainer">
                    <?php foreach ($seminar_halls as $seminar_hall): ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card" onclick="location.href='room_details.php?room_id=<?php echo $seminar_hall['room_id']; ?>&type=seminar_hall'">
                                <div class="row no-gutters">
                                    <div class="col-md-4">
                                        <img src="<?php echo $seminar_hall['image']; ?>" class="card-img" alt="<?php echo $seminar_hall['room_name']; ?>" style="width: 100%; height: 200px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $seminar_hall['room_name']; ?></h5>
                                            <p class="card-text">Location: <?php echo $seminar_hall['location']; ?></p>
                                            <p class="card-text">In-Charge: <?php echo $seminar_hall['in_charge_name']; ?></p>
                                            <p class="card-text">Features: <?php echo $seminar_hall['features']; ?></p>
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal" data-roomid="<?php echo $seminar_hall['room_id']; ?>" data-roomname="<?php echo $seminar_hall['room_name']; ?>" onclick="event.stopPropagation();">Book Now</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
        </div>   
    <?php endif; ?>


    <?php if ($room_type === 'lecture_hall_complex' || $room_type === 'all'): ?>
        <h2>Lecture Hall Complexes</h2>
        <div class="row">
            <?php foreach ($complexes as $complex): ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card" onclick="location.href='complex.php?complex_id=<?php echo $complex['complex_id']; ?>'">
                        <div class="row no-gutters">
                            <div class="col-md-4">
                                <img src="<?php echo $complex['image']; ?>" class="card-img" alt="<?php echo $complex['complex_name']; ?>" style="width: 100%; height: 200px; object-fit: cover;">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $complex['complex_name']; ?></h5>
                                    <p class="card-text">Location: <?php echo $complex['location']; ?></p>
                                    <p class="card-text">In-Charge: <?php echo $complex['in_charge_name']; ?></p>
                                    <a href="complex.php?complex_id=<?php echo $complex['complex_id']; ?>" class="btn btn-primary">View All Rooms</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingModalLabel">Book Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bookingForm">
                    <input type="hidden" id="room_id" name="room_id">
                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <div class="mb-3">
                        <label for="booking_date" class="form-label">Booking Date</label>
                        <input type="text" class="form-control" id="booking_date" name="booking_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="text" class="form-control" id="start_time" name="start_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="text" class="form-control" id="end_time" name="end_time" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Book Now</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');

        // Function to fetch filtered seminar halls
        function fetchFilteredHalls() {
            const checkedFeatures = Array.from(filterForm.querySelectorAll('input[type="checkbox"]:checked')).map(cb => cb.value);
            
            // Create an AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'seminar_filters.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('seminarHallContainer').innerHTML = xhr.responseText;
                }
            };
            // Send the selected features as a query string
            xhr.send('features=' + JSON.stringify(checkedFeatures));
        }

        // Add change event listener to checkboxes
        filterForm.addEventListener('change', fetchFilteredHalls);
    });
</script>


<script>
 document.addEventListener('DOMContentLoaded', function() {
    flatpickr("#booking_date", {
        dateFormat: "Y-m-d",
        minDate: "today",
    });

    flatpickr("#start_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });

    flatpickr("#end_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });

    $('#bookingModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var roomId = button.data('roomid');
        var roomName = button.data('roomname');
        var modal = $(this);
        modal.find('#room_id').val(roomId);
        modal.find('.modal-title').text('Book ' + roomName);
    });

    $('#bookingForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'book_room.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    alert(response.success);
                    $('#bookingModal').modal('hide');
                } else if(response.error) {
                    alert(response.error);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });

   
});

</script>
</body>
</html>
