<?php
session_start();
include 'db_connection.php';
include 'booking_modal.php';

$loggedIn = isset($_SESSION['role']);
$user_role = $loggedIn ? $_SESSION['role'] : null;

if (isset($_GET['room_id']) && isset($_GET['type'])) {
    $room_id = intval($_GET['room_id']);
    $type = $_GET['type'];

    switch ($type) {
        case 'auditorium':
            $sql = "SELECT a.*, u.username AS in_charge_name 
                    FROM auditoriums a
                    JOIN users u ON a.in_charge_id = u.user_id
                    WHERE a.room_id = ?";
            break;
        case 'seminar_hall':
            $sql = "SELECT s.*, u.username AS in_charge_name 
                    FROM seminar_halls s
                    JOIN users u ON s.in_charge_id = u.user_id
                    WHERE s.room_id = ?";
            break;
        case 'lecture_hall_complex':
            $sql = "SELECT lhr.room_name, lhr.room_id, lhr.capacity, lhr.features, lhr.status, lhr.image, lhc.complex_name, lhc.location, u.username AS in_charge_name 
                    FROM lecture_hall_rooms lhr 
                    INNER JOIN lecture_hall_complex lhc ON lhr.complex_id = lhc.complex_id 
                    INNER JOIN users u ON lhc.in_charge_id = u.user_id 
                    WHERE lhr.room_id = ?";
            break;
        default:
            echo '<p>Invalid room type selected.</p>';
            exit;
    }

    $stmt = $db->prepare($sql);
    if (!$stmt) {
        echo '<p>Database error: ' . htmlspecialchars($db->error) . '</p>';
        exit;
    }
    $stmt->bind_param("i", $room_id);
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

// Fetch room gallery images
$gallery_sql = "SELECT * FROM room_gallery WHERE room_id = ?";
$gallery_stmt = $db->prepare($gallery_sql);
$gallery_stmt->bind_param("i", $room_id);
$gallery_stmt->execute();
$gallery_result = $gallery_stmt->get_result();
$gallery_images = $gallery_result->fetch_all(MYSQLI_ASSOC);

// Fetch bookings for this room for the next 30 days
$start_date = date('Y-m-d');
$end_date = date('Y-m-d', strtotime('+60 days'));
$booking_sql = "SELECT booking_date, status FROM bookings WHERE room_id = ? AND booking_date BETWEEN ? AND ?";
$booking_stmt = $db->prepare($booking_sql);
if (!$booking_stmt) {
    echo '<p>Error preparing calendar query: ' . htmlspecialchars($db->error) . '</p>';
    exit;
}
$booking_stmt->bind_param("iss", $room_id, $start_date, $end_date);
if (!$booking_stmt->execute()) {
    echo '<p>Error executing calendar query: ' . htmlspecialchars($booking_stmt->error) . '</p>';
    exit;
}
$booking_result = $booking_stmt->get_result();

$bookings = [];
while ($row = $booking_result->fetch_assoc()) {
    $bookings[$row['booking_date']] = $row['status'];
}
// Fetch time slots for seminar halls
$time_slots = [];
if ($type == 'seminar_hall') {
    $slot_sql = "SELECT booking_date, TIME_FORMAT(start_time, '%H:%i') as start_time, TIME_FORMAT(end_time, '%H:%i') as end_time, status 
                 FROM bookings 
                 WHERE room_id = ? AND booking_date BETWEEN ? AND ?";
    $slot_stmt = $db->prepare($slot_sql);
    $slot_stmt->bind_param("iss", $room_id, $start_date, $end_date);
    $slot_stmt->execute();
    $slot_result = $slot_stmt->get_result();

    while ($row = $slot_result->fetch_assoc()) {
        if (!isset($time_slots[$row['booking_date']])) {
            $time_slots[$row['booking_date']] = [];
        }
        $time_slots[$row['booking_date']][] = [
            'start_time' => $row['start_time'],
            'end_time' => $row['end_time'],
            'status' => $row['status']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($room['room_name']); ?> Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js'></script>

    <style>
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
        }.time-slot-grid {
             display: grid;
             grid-template-columns: auto repeat(7, 1fr);
             gap: 5px;
         }
        .time-slot {
            padding: 5px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .time-slot.available { background-color: #d4edda; }
        .time-slot.booked { background-color: #f8d7da; }

        .gallery-container {
            position: relative;
            width: 100%;
            height: 400px;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .gallery-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            object-fit: cover;
        }
        .gallery-image.active {
            opacity: 1;
        }
        .gallery-nav {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }
        .gallery-nav-item {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .gallery-nav-item.active {
            background-color: white;
        }
        .feature-icon {
            font-size: 2rem;
            color: #007bff;
        }
        .room-details {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .feature-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 15px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }
        .feature-item:hover {
            transform: translateY(-5px);
        }
        .feature-name {
            margin-top: 10px;
            font-size: 0.9rem;
        }
        .btn-book-now {
            text-align: center;
            margin-top: 20px;
        }

        .calendar-day[title] {
            position: relative;
        }

        .calendar-day[title]:hover::after {
            content: attr(title);
            position: absolute;
            bottom: 100%; /* Adjust as needed */
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: #fff;
            padding: 5px;
            border-radius: 4px;
            white-space: nowrap;
            z-index: 1000;
            font-size: 12px;
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
    <h2 class="mb-4"><?php echo htmlspecialchars($room['room_name']); ?></h2>
    <div class="row">
        <div class="col-md-6">
            <?php if (!empty($gallery_images)): ?>
                <div class="gallery-container">
                    <?php foreach ($gallery_images as $index => $image): ?>
                        <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="Room Image" class="gallery-image <?php echo $index === 0 ? 'active' : ''; ?>" />
                    <?php endforeach; ?>
                    <div class="gallery-nav">
                        <?php foreach ($gallery_images as $index => $image): ?>
                            <div class="gallery-nav-item <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>"></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="gallery-container">
                    <?php
                    $roomImage = htmlspecialchars($room['image']);
                    ?>
                    <img src="<?php echo !empty($roomImage) ? $roomImage : 'path/to/default-image.jpg'; ?>" alt="Room Image" style="height: 100%; width: 100%; object-fit: cover;" />
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-6 room-details">
            <p><strong>In-Charge:</strong> <?php echo htmlspecialchars($room['in_charge_name']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($room['location']); ?></p>
            <p><strong>Capacity:</strong> <?php echo htmlspecialchars($room['capacity']); ?></p>
            <p><strong>Features:</strong></p>
            <div class="feature-grid">
                <?php foreach (explode(',', $room['features']) as $feature): ?>
                    <div class="feature-item">
                        <i class="feature-icon fas fa-check-circle"></i>
                        <span class="feature-name"><?php echo htmlspecialchars(trim($feature)); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($loggedIn): ?>
                <div class="btn-book-now">
                    <button class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#bookingModal1"
                            data-roomid="<?php echo $room['room_id']; ?>"
                            data-roomname="<?php echo htmlspecialchars($room['room_name']); ?>"
                            onclick="fillModalWithData(this);">
                        Book Now
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="container mt-4">
    <h3>Availability Calendar</h3>
    <?php if ($type == 'auditorium'): ?>
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
        <div class="time-slot-grid">
            <div class="time-slot"></div>
            <?php
            $start_date = new DateTime();
            for ($i = 0; $i < 7; $i++) {
                echo '<div class="time-slot">' . $start_date->format('D j/n') . '</div>';
                $start_date->modify('+1 day');
            }

            for ($hour = 9; $hour < 17; $hour++) {
                echo '<div class="time-slot">' . sprintf('%02d:30', $hour) . '</div>';
                $date = new DateTime();
                for ($day = 0; $day < 7; $day++) {
                    $current_date = $date->format('Y-m-d');
                    $current_time = sprintf('%02d:30', $hour);
                    $slot_status = 'available';

                    if (isset($time_slots[$current_date])) {
                        foreach ($time_slots[$current_date] as $slot) {
                            if ($current_time >= $slot['start_time'] && $current_time < $slot['end_time']) {
                                $slot_status = 'booked';
                                break;
                            }
                        }
                    }

                    echo "<div class='time-slot $slot_status'></div>";
                    $date->modify('+1 day');
                }
            }
            ?>
        </div>
    <?php endif; ?>
    <br>
</div>
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

        const calendars = document.querySelectorAll('.calendar');
        let currentDate = new Date();

        function updateCalendar(calendar, date) {
            const headerMonth = calendar.querySelector('h4');
            const calendarGrid = calendar.querySelector('.calendar-grid');

            headerMonth.textContent = date.toLocaleString('default', { month: 'long', year: 'numeric' });

            // Clear existing calendar days
            while (calendarGrid.children.length > 7) {
                calendarGrid.removeChild(calendarGrid.lastChild);
            }

            const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
            const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
            const today = new Date().toISOString().split('T')[0];

            // Add empty cells for days before the 1st
            for (let i = 0; i < firstDay.getDay(); i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'calendar-day';
                calendarGrid.appendChild(emptyDay);
            }

            // Add calendar days
            for (let i = 1; i <= lastDay.getDate(); i++) {
                const dayDate = new Date(date.getFullYear(), date.getMonth(), i);
                const dateString = dayDate.toISOString().split('T')[0];
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                dayElement.textContent = i;
                dayElement.dataset.date = dateString;

                if (dateString === today) {
                    dayElement.classList.add('today');
                }

                // Set the appropriate class based on booking status
                if (dateString < today) {
                    dayElement.classList.add('past');
                } else if (bookings[dateString]) {
                    // Mark as booked and add a tooltip with the name of the person who booked it
                    dayElement.classList.add(bookings[dateString].status.toLowerCase());
                    dayElement.setAttribute('title', `Booked by: ${bookings[dateString].bookedBy}`);
                } else {
                    dayElement.classList.add('available');
                }

                calendarGrid.appendChild(dayElement);
            }
        }
        calendars.forEach((calendar, index) => {
            const prevMonthBtn = calendar.querySelector('.prev-month');
            const nextMonthBtn = calendar.querySelector('.next-month');

            let calendarDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + index, 1);

            updateCalendar(calendar, calendarDate);

            prevMonthBtn.addEventListener('click', () => {
                calendarDate.setMonth(calendarDate.getMonth() - 1);
                updateCalendar(calendar, calendarDate);
            });

            nextMonthBtn.addEventListener('click', () => {
                calendarDate.setMonth(calendarDate.getMonth() + 1);
                updateCalendar(calendar, calendarDate);
            });
        });

</script>
</body>
</html>
<script>


    function fillModalWithData(buttonElement) {
        // Get the room name and room ID from the data attributes of the clicked button
        var roomName = buttonElement.getAttribute('data-roomname');
        var roomId = buttonElement.getAttribute('data-roomid');

        // Set the modal room name
        document.getElementById('modalRoomName').innerText = roomName;

        // Set the room ID in the hidden input field
        document.getElementById('room_id').value = roomId;

        // You can also use the roomId if you need it, for example:
        console.log('Room ID:', roomId);
    }



    // Assign the PHP value to a JavaScript variable
    var roomName = <?php echo json_encode($room['room_name']); ?>;
    var roomID = <?php echo json_encode($room['room_id']); ?>;

    // Now you can use the roomName variable in JavaScript
    console.log('Room id : ', roomID);  // This will log the room name in the browser's console


     document.addEventListener('click', function(e) {
         if (e.target.closest('.calendar-day')) {
             const selectedDate = e.target.closest('.calendar-day').dataset.date;

             // Check if the selected date is available
             if (e.target.closest('.calendar-day').classList.contains('available')) {
                 // Set the selected date as start and end date in the modal
                 document.getElementById('start_date').value = selectedDate;
                 document.getElementById('end_date').value = selectedDate;


                 // Set the room name in the modal
                 document.getElementById('modalRoomName').innerText = roomName;

                    // Set the room ID in the hidden input field
                    document.getElementById('room_id').value = roomID;

                 // Open the booking modal
                 const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal1'));
                 bookingModal.show();
             }
         }
     });






</script>