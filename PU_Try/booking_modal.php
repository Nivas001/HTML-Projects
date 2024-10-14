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
            <!--<h5 class="modal-title" id="bookingModalLabel">Book Room</h5>-->
                <h5 class="modal-title" id="bookingModalLabel">Room Booking for <span id="modalRoomName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bookingForm" method="post" action="booking_modal.php">
                <input type="hidden" id="hall_id" name="hall_id" value="">

                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">


                    <!-- Start and End Date -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" onchange="syncDates()" required>
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                    </div>

            <div class="mb-3">
            <div>
                <label style="margin-right:10px;" class="form-label">Booking Type</label>
                <input   style="margin:0 5px 0 20px;"type="radio" id="session_radio" name="booking_type" value="session" onclick="showSessionOptions()" required>
                <label  style="margin:0 10px 0 0px;"for="session_radio">Session</label>
                <input   style="margin:0 5px 0 20px;"type="radio" id="slot_radio" name="booking_type" value="slot" onclick="showSlotOptions()" required>
                <label   style="margin:0 10px 0 0px;"for="slot_radio">Slot</label>
            </div>
        </div>

        <!-- Session Options (FN, AN, Both) -->
        <div class="mb-3" id="session_options" style="display:none;">
            <div>
            <label class="form-label">Choose Session</label>
                <input style="margin:0 5px 0 20px;" type="radio" id="fn_session" name="session_choice" value="fn">
                <label style="margin:0 10px 0 0px;"for="fn_session">FN (Forenoon)</label>
                <input style="margin:0 5px 0 20px;" type="radio" id="an_session" name="session_choice" value="an">
                <label style="margin:0 10px 0 0px;"for="an_session">AN (Afternoon)</label>
                <input style="margin:0 5px 0 20px;" type="radio" id="both_session" name="session_choice" value="both">
                <label style="margin:0 10px 0 0px;"for="both_session">Both</label>
            </div>
        </div>

        <!-- Slot Options (Checkboxes for Time Slots) -->
        <div class="mb-3" id="slot_options" style="display:none;">
            <div>
            <label class="form-label">Choose Slot(s)</label>
                <input type="checkbox" id="slot_1" name="slots[]" value="1">
                <label for="slot_1">9:30am </label>
                <input type="checkbox" id="slot_2" name="slots[]" value="2">
                <label for="slot_2">10:30am </label> 
                <input type="checkbox" id="slot_3" name="slots[]" value="3">
                <label for="slot_3">11:30am </label> 
                <input type="checkbox" id="slot_4" name="slots[]" value="4">
                <label for="slot_4">12:30pm </label> 
                <input type="checkbox" id="slot_5" name="slots[]" value="5">
                <label for="slot_5">1:30pm </label> 
                <input type="checkbox" id="slot_6" name="slots[]" value="6">
                <label for="slot_6">2:30pm </label> 
                <input type="checkbox" id="slot_7" name="slots[]" value="7">
                <label for="slot_7">3:30pm </label> 
                <input type="checkbox" id="slot_8" name="slots[]" value="8">
                <label for="slot_8">4:30pm </label>
            </div>
        </div>

        <!-- Add a span to display the warning -->
        <div class="mb-3" id="session_availability" style="display:none; color:red;">
            This session is already booked.
        </div>


        <div class="mb-3">
            <div>
            <label class="form-label">Purpose of Booking</label>
                <input style="margin:0 5px 0 20px;"type="radio" id="purpose_event" name="purpose" value="event" required>
                <label style="margin:0 10px 0 0px;"for="purpose_event">Event</label>
                <input style="margin:0 5px 0 20px;" type="radio" id="purpose_class" name="purpose" value="class" required>
                <label style="margin:0 10px 0 0px;"for="purpose_class">Class</label>
            </div>
        </div>
                    <!-- purpose_name_name_name_name of Booking -->
                    <div class="mb-3">
                        <label for="purpose_name" class="form-label">Name of the Programme/Event</label>
                        <textarea class="form-control" id="purpose_name" name="purpose_name" rows="3" required></textarea>
                    </div>

                    <!-- Number of Students Expected -->
                    <div class="mb-3">
                        <label for="students_count" class="form-label">Number of Students Expected</label>
                        <input type="number" class="form-control" id="students_count" name="students_count" required>
                    </div>

                    <!-- organiser's Information -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="organiser_name" class="form-label">Organiser's Name</label>
                            <input type="text" class="form-control" id="organiser_name" name="organiser_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="organiser_department" class="form-label">Organiser's Department</label>
                            <input type="text" class="form-control" id="organiser_department" name="organiser_department" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="organiser_mobile" class="form-label">Organiser's Contact Number</label>
                            <input type="text" class="form-control" id="organiser_mobile" name="organiser_mobile" required>
                        </div>
                        <div class="col-md-6">
                            <label for="organiser_email" class="form-label">Organiser's Email ID</label>
                            <input type="email" class="form-control" id="organiser_email" name="organiser_email" required>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <!--to make button as disabled-->
                  <center> <button type="submit" class="btn btn-primary" id="submitBtn">Book Now</button></center>
                </form>
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function showSessionOptions() {
        document.getElementById('session_options').style.display = 'block';
        document.getElementById('slot_options').style.display = 'none';
    }

    function showSlotOptions() {
        document.getElementById('slot_options').style.display = 'block';
        document.getElementById('session_options').style.display = 'none';
    }

    // Function to sync the end date with the start date
        function syncDates() {
            // Get the value of the start date
            var startDate = document.getElementById('start_date').value;

            // Set the end date to the same as the start date
            document.getElementById('end_date').value = startDate;
        }

    //Make the session options visible and book or show warning if already booked
    function showSessionOptions() {
        document.getElementById('session_options').style.display = 'block';
        document.getElementById('slot_options').style.display = 'none';

        // Add event listeners for session radio buttons
        const sessionRadios = document.querySelectorAll('input[name="session_choice"]');
        sessionRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                checkAvailability();
            });
        });
    }

    function checkAvailability() {
        const hall_id = document.getElementById('hall_id').value;
        const start_date = document.getElementById('start_date').value;
        const session_choice = document.querySelector('input[name="session_choice"]:checked').value;

        if (hall_id && start_date && session_choice) {
            $.ajax({
                url: 'check_availability.php', // PHP script to check availability
                type: 'POST',
                data: {
                    hall_id: hall_id,
                    start_date: start_date,
                    session_choice: session_choice
                },
                success: function(response) {
                    if (response === 'booked') {
                        document.getElementById('session_availability').style.display = 'block';
                        document.getElementById('submitBtn').disabled = true;
                    } else {
                        document.getElementById('session_availability').style.display = 'none';
                        document.getElementById('submitBtn').disabled = false;
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error in availability check: " + error);
                }
            });
        }
    }

</script>
</body>

</html>


<!--Connection to database-->
<!-- booking_modal.php -->
<?php
// Include the database connection
$host = 'localhost'; // Database host
$dbname = 'hall_booking'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password
try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form values
        $hall_id = $_POST['hall_id'];
        $user_id = $_POST['user_id'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $purpose = $_POST['purpose']; // Get the value of the selected purpose (event or class)

        $purpose_name = $_POST['purpose_name'];
        $students_count = $_POST['students_count'];
        $organiser_name = $_POST['organiser_name'];
        $organiser_department = $_POST['organiser_department'];
        $organiser_mobile = $_POST['organiser_mobile'];
        $organiser_email = $_POST['organiser_email'];

        // Booking type and time handling
        $start_time = '';
        $end_time = '';
        $slot_or_session = ''; // Field to store the slot or session data
        
        if (isset($_POST['session_choice'])) {
            // Handle session choices
            $session_choice = $_POST['session_choice'];
            
            if ($session_choice === 'fn') {
                $start_time = '09:30:00';
                $end_time = '12:30:00';
                $slot_or_session = '1,2,3,4'; // Fill for fn
            } elseif ($session_choice === 'an') {
                $start_time = '12:30:00';
                $end_time = '17:30:00';
                $slot_or_session = '5,6,7,8'; // Fill for an
            } elseif ($session_choice === 'both') {
                $start_time = '09:30:00';
                $end_time = '17:30:00';
                $slot_or_session = '1,2,3,4,5,6,7,8'; // Fill for both
            }
        } elseif (isset($_POST['slots'])) {
            // Handle slot choices
            $slots = $_POST['slots']; // Array of selected slots
            $slot_times = [
                '1' => '09:30:00',
                '2' => '10:30:00',
                '3' => '11:30:00',
                '4' => '12:30:00',
                '5' => '13:30:00',
                '6' => '14:30:00',
                '7' => '15:30:00',
                '8' => '16:30:00'
            ];
            
            // Build the slot_or_session string based on selected slots
            $selected_slots = [];
            foreach ($slots as $slot) {
                if (array_key_exists($slot, $slot_times)) {
                    $selected_slots[] = $slot;
                }
            }
            
            // Join the selected slots into a string for the slot_or_session field
            $slot_or_session = implode(',', $selected_slots);
            
            // Get the start and end times based on the selected slots
            if (!empty($selected_slots)) {
                $start_time = $slot_times[min($selected_slots)]; // Earliest slot start time
                $end_time = date("H:i:s", strtotime($slot_times[max($selected_slots)]) + 3600); // Latest slot end time + 1 hour
            }
        }

        // Insert all booking details at once
        $sql = "INSERT INTO bookings_pu (hall_id, user_id, start_date, end_date, 
            purpose, purpose_name, students_count, organiser_name, organiser_department, 
            organiser_mobile, organiser_email, start_time, end_time, slot_or_session, booking_date, status)
            VALUES (:hall_id, :user_id, :start_date, :end_date, 
            :purpose, :purpose_name, :students_count, :organiser_name, :organiser_department, 
            :organiser_mobile, :organiser_email, :start_time, :end_time, :slot_or_session, :booking_date, 'Pending')";

        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':hall_id', $hall_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':purpose', $purpose);
        $stmt->bindParam(':purpose_name', $purpose_name);
        $stmt->bindParam(':students_count', $students_count);
        $stmt->bindParam(':organiser_name', $organiser_name);
        $stmt->bindParam(':organiser_department', $organiser_department);
        $stmt->bindParam(':organiser_mobile', $organiser_mobile);
        $stmt->bindParam(':organiser_email', $organiser_email);
        $stmt->bindParam(':start_time', $start_time);
        $stmt->bindParam(':end_time', $end_time);
        $stmt->bindParam(':slot_or_session', $slot_or_session); // Bind the slot_or_session field
        $stmt->bindParam(':booking_date', $start_date); // Assuming booking_date is the start_date

        // Execute the query
        if ($stmt->execute()) {
            // Booking successful
            echo "<script>alert('Booking successful!'); window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Booking failed!');</script>";
        }

        //Added to check if the session is already booked
        $sql = "SELECT COUNT(*) FROM bookings_pu
                WHERE hall_id = :hall_id
                AND start_date = :start_date
                AND start_time <= :end_time
                AND end_time >= :start_time
                AND status IN ('approved', 'booked')";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':hall_id', $hall_id);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':start_time', $start_time);
        $stmt->bindParam(':end_time', $end_time);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // Session is already booked
            echo 'booked';
        } else {
            // Session is available
            echo 'available';
        }

    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the connection
$conn = null;
?>
