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
                <h5 class="modal-title" id="bookingModalLabel">Book Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bookingForm">
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
</body>
</html>
