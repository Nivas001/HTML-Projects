<?php
session_start();
include 'db_connection.php'; // Include database connection

$loggedIn = isset($_SESSION['role']);
$user_role = $loggedIn ? $_SESSION['role'] : null;

// Fetch all lecture hall complexes
$complexes = $db->query("SELECT * FROM lecture_hall_complex")->fetch_all(MYSQLI_ASSOC);

// Check if a complex_id is provided in the URL and fetch corresponding rooms
$complex_id = isset($_GET['complex_id']) ? intval($_GET['complex_id']) : null;
$rooms = [];

// Fetch rooms for the specified complex or all rooms if no specific complex is selected
if ($complex_id) {
    // Fetch rooms for the specified complex
    $rooms = $db->query("SELECT * FROM lecture_hall_rooms WHERE complex_id = $complex_id")->fetch_all(MYSQLI_ASSOC);
} else {
    // Fetch all rooms if no specific complex is selected
    $rooms = $db->query("SELECT * FROM lecture_hall_rooms")->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecture Hall Complexes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your custom CSS -->
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
    <div id="complexButtons" class="mb-3">
        <?php foreach ($complexes as $complex): ?>
            <a href="complex.php?complex_id=<?php echo $complex['complex_id']; ?>" class="btn btn-secondary me-2">
                <?php echo htmlspecialchars($complex['complex_name']); ?>
            </a>
        <?php endforeach; ?>
        <a href="complex.php" class="btn btn-secondary" style="float: right;">Show All Rooms</a>
    </div>

    <div id="roomList" class="row">
        <?php if ($rooms): ?>
            <?php foreach ($rooms as $room): ?>
    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card" onclick="window.location.href='room_details.php?room_id=<?php echo $room['room_id']; ?>&type=lecture_hall_complex'">
            <div class="row no-gutters">
                <div class="col-md-4">
                    <img src="<?php echo htmlspecialchars($room['image']); ?>" class="card-img" alt="<?php echo htmlspecialchars($room['room_name']); ?>" style="width: 100%; height: auto;">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($room['room_name']); ?></h5>
                        <p class="card-text">Features: <?php echo htmlspecialchars($room['features']); ?></p>
                        <button class="btn btn-primary" 
                            data-bs-toggle="modal" 
                            data-bs-target="#bookingModal" 
                            data-roomid="<?php echo $room['room_id']; ?>" 
                            data-roomname="<?php echo htmlspecialchars($room['room_name']); ?>" 
                            onclick="event.stopPropagation();">Book Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

        <?php else: ?>
            <p>No rooms available for this complex.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingModalLabel">Booking Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bookingForm">
                    <input type="hidden" id="roomId" name="room_id">
                    <div class="mb-3">
                        <label for="bookingDate" class="form-label">Booking Date</label>
                        <input type="date" class="form-control" id="bookingDate" name="booking_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="startTime" class="form-label">Start Time</label>
                        <input type="time" class="form-control" id="startTime" name="start_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="endTime" class="form-label">End Time</label>
                        <input type="time" class="form-control" id="endTime" name="end_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="userId" class="form-label">User ID</label>
                        <input type="text" class="form-control" id="userId" name="user_id" value="<?php echo $_SESSION['user_id']; ?>" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Set room ID and name in the booking modal
    $('#bookingModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var roomId = button.data('roomid'); // Extract info from data-* attributes
        var roomName = button.data('roomname');

        var modal = $(this);
        modal.find('#roomId').val(roomId); // Set the room ID
        modal.find('.modal-title').text('Booking: ' + roomName); // Set the title
    });

    // Handle booking form submission
    $('#bookingForm').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission
        var formData = $(this).serialize(); // Serialize form data

        // Make an AJAX request to book the room
        $.ajax({
            type: 'POST',
            url: 'book_room.php', // URL to the booking handler
            data: formData,
            success: function (response) {
                alert(response); // Display response
                $('#bookingModal').modal('hide'); // Close the modal
                $('#bookingForm')[0].reset(); // Reset the form
            },
            error: function (error) {
                alert('Error booking the room. Please try again.'); // Error handling
            }
        });
    });
</script>
</body>
</html>

<style>
    .card {
        cursor: pointer;
    }
</style>
