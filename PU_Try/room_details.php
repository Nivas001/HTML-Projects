<?php
session_start();
include 'db_connection.php';
include 'booking_modal.php';

$loggedIn = isset($_SESSION['role']);
$user_role = $loggedIn ? $_SESSION['role'] : null;
$features = ['wifi', 'ac', 'projector', 'audio_system', 'smart_board', 'black_board'];
if (isset($_GET['hall_id'])) {
    $hall_id = intval($_GET['hall_id']);

    $sql = "SELECT v.*, u.username AS in_charge_name 
            FROM venue v
            JOIN users u ON v.in_charge_id = u.user_id
            WHERE v.hall_id = ?";

    $stmt = $db->prepare($sql);
    if (!$stmt) {
        echo '<p>Database error: ' . htmlspecialchars($db->error) . '</p>';
        exit;
    }
    $stmt->bind_param("i", $hall_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();
    } else {
        echo '<p>Room not found.</p>';
        exit;
    }
} else {
    echo '<p>No room selected.</p>';
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($room['name']); ?> Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js'></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
        }
        header {
            color: white;
            background-color: white;
        }
        .calendar-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap:10px;

        }
        .calendar {
            width: 100%;
            max-width: 45%;

        }
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }
        @media only screen and (max-width: 600px) {
            .calendar {
                margin-bottom:20px;
                max-width: 400px;
            }
        }
        .calendar-day {
            aspect-ratio: 4/3;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border: 1px solid #ddd;
            cursor: pointer;
            font-size: 1em;
        }
        .calendar-day .date {
            font-size: 1.2em;
            font-weight: bold;
        }
        .calendar-day.available { background-color: #d4edda; }
        .calendar-day.pending { background-color: #fff3cd; }
        .calendar-day.confirmed { background-color: #f8d7da; }
        .calendar-day.past { background-color: #f2f2f2; color: #6c757d; }
        .calendar-day.today {
            border: 2px solid #007bff;
            font-weight: bold;
        }
        .calendar-legend {
            display: flex;
            justify-content: start;
            gap: 20px;
            margin: 20px 0;
        }
        .legend-item {
            display: flex;
            align-items: center;
        }
        .legend-color {
            width: 20px;
            height: 20px;
            margin-right: 5px;
            border: 1px solid #000;
        }
        .time-slot-grid {
        display: grid;
        grid-template-columns: repeat(32, 1fr); /* Adjust based on number of days + 1 for time slots */
    }

    .time-slot-header {
        background-color: #f0f0f0; /* Light gray for headers */
        padding: 1px;
        text-align: center;
        font-weight: bold;
    }

        .time-slot {
            padding: 5px;
            border: 0.5px solid #ffffff;
            text-align: center;
        }
        .time-slot.available { background-color: #d4edda; }
        .time-slot.booked { background-color: #f8d7da; }

        .feature-icon {
            font-size: 2rem;
            color: #007bff;
        }
        .room-details {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }
        
        .btn-book-now {
            text-align: center;
            margin-top: 20px;
        }
        .cancel-icon {
            position: absolute;
            top: 200px;
            right: 20px;
            cursor: pointer;
            color: #007bff; /* Change to any color you prefer */
            font-size: 1.5em; /* Adjust size */
        }
    </style>
</head>
<body>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<?php include "header.php"; ?>



<div class="cancel-icon" onclick="window.history.back();">
    <i class="fas fa-times-circle"></i>
</div>

<div class="room-details">
    <div class="row">    
        <div class="col-md-2 room-details">
        <h3><?php echo htmlspecialchars($room['hall_name']); ?></h3>
            <span><strong>In-Charge:</strong> <?php echo htmlspecialchars($room['in_charge_name']); ?></span><br>
            <span><strong>Location:</strong> <?php echo htmlspecialchars($room['location']); ?></span><br>
            <span><strong>Capacity:</strong> <?php echo htmlspecialchars($room['capacity']); ?></span><br>
            <span><strong>Features:</strong></span>
            <div class="feature-grid">
            <?php foreach ($features as $feature): ?>
                <div class="feature-item">
                    <i class="feature-icon fas fa-check-circle" style="font-size: 1.2em;"></i> <!-- Adjust font size here -->
                    <span class="feature-name"><?php echo htmlspecialchars(trim($feature)); ?></span>
                </div>
            <?php endforeach; ?>
        </div>

            <?php if ($loggedIn): ?>
        <div class="btn-book-now">
       
<?php if ($loggedIn): ?>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal1" data-roomid="<?php echo $room['hall_id']; ?>" data-roomname="<?php echo $room['hall_name']; ?>" onclick="event.stopPropagation(); fillModalWithData(this);">Book Now</button>





                                    <?php else: ?>
                                        <button class="btn btn-primary" onclick="event.stopPropagation(); window.location.href='login.html';">Book Now</button>
                                    <?php endif; ?>

                     <?php endif; ?>
            </div>
            </div>
 

        <div class="container col-md-10 mt-4">
        <?php if ( $room['hall_type'] == 'auditorium'): ?>
            <div class="calendar-legend">

                <div class="legend-item">
                    <div class="legend-color" style="background-color: #d4edda;"></div>
                    <span>Available</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #fff3cd;"></div>
                    <span>Pending</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #f8d7da;"></div>
                    <span>Booked</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #e9ecef;"></div>
                    <span>Past</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="border: 2px solid #007bff; background-color: transparent;"></div>
                    <span>Today</span>
                </div>
        </div>
        <br>

  <!-- Calender part -->
  <div class="calendar-container">
            <?php
            $current_month = new DateTime();
            for ($i = 0; $i < 2; $i++):
                $year = $current_month->format('Y');
                $month = $current_month->format('m');
                ?>
                <div class="calendar">
                    <div class="calendar-header">
                        <button class="btn btn-sm btn-light prev-month">&lt;</button>
                        <h4><?php echo $current_month->format('F Y'); ?></h4>
                        <button class="btn btn-sm btn-light next-month">&gt;</button>
                    </div>
                    <div class="calendar-grid">
                        <?php
                        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                        foreach ($days as $day) {
                            echo "<div class='calendar-day'><span class='day-name'>$day</span></div>";
                        }

                        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                        $first_day = date('w', strtotime("$year-$month-01"));
                        $today = date('Y-m-d');

                        // Add empty cells for days before the 1st
                        for ($j = 0; $j < $first_day; $j++) {
                            echo '<div class="calendar-day"></div>';
                        }

                        for ($day = 1; $day <= $days_in_month; $day++) {
                            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                            $class = 'calendar-day';
                            if (isset($bookings[$date])) {
                                $class .= $bookings[$date] == 'Confirmed' ? ' confirmed' : ' pending';
                            } elseif ($date < $today) {
                                $class .= ' past';
                            } else {
                                $class .= ' available';
                            }
                            if ($date == $today) {
                                $class .= ' today';
                            }
                            echo "<div class='$class' data-date='$date'>$day</div>";
                        }
                        ?>
                    </div>
                </div>
                <?php
                $current_month->modify('+1 month');
            endfor;
            ?>
        </div>

    <?php else: ?>
        <div class="container mt-4">
    <h3><?php echo date('F Y'); ?></h3>
    <div class="time-slot-grid">
        <div class="time-slot-header"></div> 
        
        <?php
        // Set the date for the first day of the current month
        $date = new DateTime(date('Y-m-01'));
        $daysInMonth = date('t'); // Total days in current month
        
        // Output the date headers
        for ($d = 1; $d <= $daysInMonth; $d++) {
            echo "<div class='time-slot-header'>" . $date->format('j') . "</div>";
            $date->modify('+1 day'); // Move to next day
        }
        ?>
        
        <?php
        // Time slots from 9:30 to 16:30
        for ($hour = 9; $hour <= 16; $hour++) {
            echo '<div class="time-slot">' . sprintf('%02d:30', $hour) . '</div>';

            // Reset the date to the first of the month for each time slot
            $date = new DateTime(date('Y-m-01'));

            // Iterate through each day of the month
            for ($day = 1; $day <= $daysInMonth; $day++) {
                // Set the current date
                $current_date = $date->format('Y-m-d');
                $current_time = sprintf('%02d:30', $hour);
                $slot_status = 'available';

                // Check if there are bookings on the current date
                if (isset($bookings[$current_date])) {
                    // If booked, change status accordingly
                    if ($bookings[$current_date] === 'booked') {
                        $slot_status = 'booked';
                    }
                }

                // Check for time slots for the current hour
                if (isset($time_slots[$current_date])) {
                    foreach ($time_slots[$current_date] as $slot) {
                        if ($current_time >= $slot['start_time'] && $current_time < $slot['end_time']) {
                            $slot_status = 'booked';
                            break;
                        }
                    }
                }

                // Output the status of the time slot
                echo "<div class='time-slot $slot_status'></div>";
                $date->modify('+1 day'); // Move to next day
            }
        }
        ?>
    </div>
    <?php endif; ?>
    <br>
</div>

    </div>
</div>


<script>
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

</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        let currentImageIndex = 0;
        const images = $('.gallery-image');
        const navItems = $('.gallery-nav-item');

        function showImage(index) {
            images.removeClass('active');
            navItems.removeClass('active');
            $(images[index]).addClass('active');
            $(navItems[index]).addClass('active');
        }

        navItems.on('click', function() {
            currentImageIndex = $(this).data('index');
            showImage(currentImageIndex);
        });

        setInterval(function() {
            currentImageIndex = (currentImageIndex + 1) % images.length;
            showImage(currentImageIndex);
        }, 5000);
    });


</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
