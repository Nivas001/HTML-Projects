<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($room['room_name']); ?> Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js'></script>

    <style>
        .calendar-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 10px;
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
                margin-bottom: 20px;
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
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .calendar-day .date {
            font-size: 1.2em;
            font-weight: bold;
        }

        .calendar-day.available {
            background-color: #d4edda;
        }

        .calendar-day.pending {
            background-color: #fff3cd;
        }

        .calendar-day.confirmed {
            background-color: #f8d7da;
        }

        .calendar-day.past {
            background-color: #f2f2f2;
            color: #6c757d;
        }

        .calendar-day.today {
            border: 2px solid #007bff;
            font-weight: bold;
        }

        .calendar-day:hover {
            background-color: #dfe7fa;
            transform: scale(1.05);
        }

        /* Style for checked boxes */
        .calendar-day.checked {
            background-color: #28a745;
            color: white;
        }

        /* Add black checkmark icon */
        .calendar-day.checked::before {
            content: '\2713'; /* Unicode for a checkmark */
            font-size: 1.5em;
            color: black; /* Set color to black */
            position: absolute;
            top: 5px;
            right: 5px;
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

        .submit-container {
            text-align: center;
            margin-top: 20px;
        }

        .submit-container button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-container button:hover {
            background-color: #0056b3;
        }

        /* Scheduling Styles */
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .schedule-table th,
        .schedule-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            position: relative; /* For absolute positioning of checkmark */
        }

        .schedule-table th {
            background-color: #007bff;
            color: white;
        }

        .schedule-table td:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        .time-slot.checked {
            background-color: #28a745; /* Optional: change background when checked */
            color: white; /* Optional: change text color when checked */
        }

        /* Add black checkmark icon to selected time slots */
        .time-slot.checked::before {
            content: '\2713'; /* Unicode for a checkmark */
            font-size: 1.5em;
            color: black; /* Set color to black */
            position: absolute;
            top: 5px;
            right: 5px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Pondicherry University</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                    <li class='nav-item'><a class='nav-link' href='login.php'>Login</a></li>
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
                    <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="Room Image"
                        class="gallery-image <?php echo $index === 0 ? 'active' : ''; ?>" />
                    <?php endforeach; ?>
                    <div class="gallery-nav">
                        <?php foreach ($gallery_images as $index => $image): ?>
                        <div class="gallery-nav-item <?php echo $index === 0 ? 'active' : ''; ?> "
                            data-index="<?php echo $index; ?>"></div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="gallery-container">
                    <?php 
                        $roomImage = htmlspecialchars($room['image']);
                        ?>
                    <img src="<?php echo !empty($roomImage) ? $roomImage : 'path/to/default-image.jpg'; ?>"
                        alt="Room Image" style="height: 100%; width: 100%; object-fit: cover;" />
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
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal"
                        data-roomid="<?php echo $room['hall_id']; ?>"
                        data-roomname="<?php echo htmlspecialchars($room['room_name']); ?>">Book Now</button>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Scheduling Table -->
        <h3>Schedule</h3>
        <table class="schedule-table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                    <th>Saturday</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Define time slots
                $timeSlots = [
                    '09:30 AM',
                    '10:30 AM',
                    '11:30 AM',
                    '12:30 PM',
                    '01:30 PM',
                    '02:30 PM',
                    '03:30 PM',
                    '04:30 PM',
                ];
                foreach ($timeSlots as $time): ?>
                    <tr>
                        <td><?php echo $time; ?></td>
                        <?php for ($i = 0; $i < 7; $i++): ?>
                            <td class="time-slot" data-time="<?php echo $time; ?>" data-day="<?php echo $i; ?>">
                                <!-- Optional: If you want to display availability state, modify this -->
                            </td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Submit button -->
        <div class="submit-container">
            <button id="submit-selection">Submit Selected Times</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        // Handling schedule time slot selection
        const timeSlots = document.querySelectorAll('.time-slot');
        timeSlots.forEach(slot => {
            slot.addEventListener('click', () => {
                slot.classList.toggle('checked');
            });
        });

        // Handling submit button
        const submitButton = document.getElementById('submit-selection');
        submitButton.addEventListener('click', () => {
            const selectedTimes = [];
            document.querySelectorAll('.time-slot.checked').forEach(slot => {
                selectedTimes.push(slot.dataset.time + ' on Day ' + slot.dataset.day);
            });

            alert('Selected times: ' + selectedTimes.join(', '));
            // Here you can send the selectedTimes array to your backend server
        });
    </script>
</body>

</html>
