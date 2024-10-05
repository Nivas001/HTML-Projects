<?php
// Connect to the database
require 'db_connection.php'; // Adjust according to your database connection file

if (isset($_POST['features'])) {
    $features = json_decode($_POST['features']);
    
    // Create the SQL query
    $sql = "SELECT sh.*, u.username AS in_charge_name 
                 FROM seminar_halls sh 
                 JOIN users u ON sh.in_charge_id = u.user_id"; 

    if (!empty($features)) {
        $featuresCondition = [];
        foreach ($features as $feature) {
            $featuresCondition[] = "features LIKE '%$feature%'";
        }
        $sql .= " AND (" . implode(' AND ', $featuresCondition) . ")";
    }

    $result = mysqli_query($db, $sql);
    
    // Fetch and display seminar halls
    while ($hall = mysqli_fetch_assoc($result)) {
        echo '
        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="card" onclick="location.href=\'room_details.php?room_id=' . $hall['room_id'] . '&type=seminar_hall\'">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <img src="' . $hall['image'] . '" class="card-img" alt="' . $hall['room_name'] . '" style="width: 100%; height: 200px; object-fit: cover;">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">' . $hall['room_name'] . '</h5>
                            <p class="card-text">Location: ' . $hall['location'] . '</p>
                            <p class="card-text">In-Charge: ' . $hall['in_charge_name'] . '</p>
                            <p class="card-text">Features: ' . $hall['features'] . '</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal" data-roomid="' . $hall['room_id'] . '" data-roomname="' . $hall['room_name'] . '" onclick="event.stopPropagation();">Book Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }

    // Close the database connection
    mysqli_close($db);
}
?>
