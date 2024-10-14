<!-- booking_modal.php -->
    <!--For auditorium Booking-->
<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    </head>

<body>

<!-- For Auditorium -->
<div class="modal fade" id="bookingModal1" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
<!--                <h5 class="modal-title" id="bookingModalLabel">Book Room</h5>-->
                <h5 class="modal-title" id="bookingModalLabel">Room Booking for <span id="modalRoomName"></span> </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bookingForm" method="post" action="booking_modal.php">
                    <input type="hidden" id="room_id" name="room_id">
                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">


                    <!-- Start and End Date -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                    </div>

                    <!-- Session Selection -->
                    <div class="mb-3">
                        <label class="form-label">Session</label>
                        <div class="d-flex">
                            <div class="form-check me-3">
                                <input class="form-check-input" type="radio" name="session" id="session_fn" value="FN" required>
                                <label class="form-check-label" for="session_fn">
                                    Forenoon (FN)
                                    <i class="bi bi-sun"></i>
                                </label>
                            </div>
                            <div class="form-check me-3">
                                <input class="form-check-input" type="radio" name="session" id="session_an" value="AN" required>
                                <label class="form-check-label" for="session_an">
                                    Afternoon (AN)
                                    <i class="bi bi-moon"></i>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="session" id="session_both" value="BOTH" required>
                                <label class="form-check-label" for="session_both">
                                    Both (FN & AN)
                                    <i class="bi bi-shift"></i>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Purpose of Booking -->
                    <div class="mb-3">
                        <label for="purpose" class="form-label">Purpose of Booking</label>
                        <textarea class="form-control" id="purpose" name="purpose" rows="3" required></textarea>
                    </div>

                    <!-- Number of Students Expected -->
                    <div class="mb-3">
                        <label for="students_expected" class="form-label">Number of Students Expected</label>
                        <input type="number" class="form-control" id="students_expected" name="students_expected" required>
                    </div>

                    <!-- Professor's Information -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="professor_name" class="form-label">Professor's Name</label>
                            <input type="text" class="form-control" id="professor_name" name="professor_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="professor_department" class="form-label">Professor's Department</label>
                            <input type="text" class="form-control" id="professor_department" name="professor_department" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="professor_contact" class="form-label">Professor's Contact Number</label>
                            <input type="text" class="form-control" id="professor_contact" name="professor_contact" required>
                        </div>
                        <div class="col-md-6">
                            <label for="professor_email" class="form-label">Professor's Email ID</label>
                            <input type="email" class="form-control" id="professor_email" name="professor_email" required>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Book Now</button>
                </form>
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!--<script>-->
<!--    document.getElementById('bookingForm').addEventListener('submit', function(event) {-->
<!--        event.preventDefault(); // Prevent default form submission for debugging-->
<!---->
<!--        var roomId = document.getElementById('room_id').value;-->
<!--        var userId = document.getElementById('user_id').value;-->
<!---->
<!--        console.log('Submitting Form');-->
<!--        console.log('Room ID:', roomId);-->
<!--        console.log('User ID:', userId);-->
<!---->
<!--        // Uncomment the next line to allow the form submission after logging-->
<!--        // this.submit();-->
<!--    });-->
<!---->
<!--</script>-->
</body>
</html>


<!--Connection to database-->
<!-- booking_modal.php -->

<?php
// Include the database connection
$host = 'localhost'; // Database host
$dbname = 'university_portal'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form values
        $room_id = $_POST['room_id'];
        $user_id = $_POST['user_id'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $session = $_POST['session'];
        $purpose = $_POST['purpose'];
        $students_expected = $_POST['students_expected'];
        $professor_name = $_POST['professor_name'];
        $professor_department = $_POST['professor_department'];
        $professor_contact = $_POST['professor_contact'];
        $professor_email = $_POST['professor_email'];

        // Determine start_time and end_time based on session
        if ($session === 'FN') {
            $start_time = '09:30:00';
            $end_time = '13:30:00';
        } elseif ($session === 'AN') {
            $start_time = '13:30:00';
            $end_time = '17:00:00';
        } else { // BOTH
            $start_time = '09:30:00';
            $end_time = '17:00:00';
        }

        // Determine room_type based on room_id
        if ($room_id >= 1 && $room_id <= 5) {
            $room_type = 'auditoriums';
        } else {
            // Assign a default or handle other cases if needed
            $room_type = 'other'; // Change this based on your needs
        }

        // Insert into bookings table first
        $sql2 = "INSERT INTO bookings (room_id, user_id, start_time, end_time, booking_date, room_type, status)
                 VALUES (:room_id, :user_id, :start_time, :end_time, :booking_date, :room_type, 'Pending')";

        $stmt2 = $conn->prepare($sql2);
        $stmt2->bindParam(':room_id', $room_id);
        $stmt2->bindParam(':user_id', $user_id);
        $stmt2->bindParam(':start_time', $start_time);
        $stmt2->bindParam(':end_time', $end_time);
        $stmt2->bindParam(':booking_date', $start_date); // Using start_date as booking_date
        $stmt2->bindParam(':room_type', $room_type); // Assuming you have this variable set correctly

        // Execute the bookings insert
        if ($stmt2->execute()) {
            // Get the last inserted ID from bookings
            $last_booking_id = $conn->lastInsertId();

            // Insert into bookings1 table with the same booking_id
            $sql1 = "INSERT INTO bookings1 (booking_id, room_id, user_id, start_date, end_date, session, purpose, students_expected, professor_name, professor_department, professor_contact, professor_email)
                      VALUES (:booking_id, :room_id, :user_id, :start_date, :end_date, :session, :purpose, :students_expected, :professor_name, :professor_department, :professor_contact, :professor_email)";

            $stmt1 = $conn->prepare($sql1);
            $stmt1->bindParam(':booking_id', $last_booking_id); // Use the same booking_id
            $stmt1->bindParam(':room_id', $room_id);
            $stmt1->bindParam(':user_id', $user_id);
            $stmt1->bindParam(':start_date', $start_date);
            $stmt1->bindParam(':end_date', $end_date);
            $stmt1->bindParam(':session', $session);
            $stmt1->bindParam(':purpose', $purpose);
            $stmt1->bindParam(':students_expected', $students_expected);
            $stmt1->bindParam(':professor_name', $professor_name);
            $stmt1->bindParam(':professor_department', $professor_department);
            $stmt1->bindParam(':professor_contact', $professor_contact);
            $stmt1->bindParam(':professor_email', $professor_email);

            // Execute the bookings1 insert
            if ($stmt1->execute()) {
                // Booking successful
                echo "<script>alert('Booking successful!');</script>";
            } else {
                // Booking1 failed
                echo "<script>alert('Failed to insert into bookings1.');</script>";
            }
        } else {
            // Booking failed
            echo "<script>alert('Failed to insert into bookings.');</script>";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the connection
$conn = null;
?>


<!-- booking_modal.php -->