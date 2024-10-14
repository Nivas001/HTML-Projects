<?php
include 'db_connection.php';

$features = isset($_POST['features']) ? $_POST['features'] : [];

$query = "SELECT r.*, u.username AS in_charge_name 
          FROM rooms r 
          JOIN users u ON r.in_charge_id = u.user_id";


        echo '<div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card h-100">
                    <img src="' . $room['image'] . '" class="card-img-top" alt="' . $room['room_name'] . '">
                    <div class="card-body">
                        <h5 class="card-title">' . $room['room_name'] . '</h5>
                        <p class="card-text">Location: ' . $room['location'] . '</p>
                        <p class="card-text">Capacity: ' . $room['capacity'] . '</p>
                        <p class="card-text">Features: ' . $room['features'] . '</p>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal" data-roomid="' . $room['hall_id'] . '" data-roomname="' . $room['room_name'] . '">Book Now</button>
                    </div>
                </div>
              </div>';
   
$db->close();
?>