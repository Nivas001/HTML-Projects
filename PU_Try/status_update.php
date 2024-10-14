<?php
session_start();
include 'db_connection.php';

// Ensure user is logged in and has the role of Dean
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Dean') {
    header("Location: login.php");
    exit();
}

// Fetch Dean's school_id
$dean_id = $_SESSION['user_id'];
$sql_dean_department = "SELECT school_id FROM users WHERE user_id = ?";
$stmt = $db->prepare($sql_dean_department);
$stmt->bind_param("i", $dean_id);
$stmt->execute();
$stmt->bind_result($dean_school_id);
$stmt->fetch();
$stmt->close();

// Fetch pending bookings for the Dean's department
$sql = "SELECT b.*, r.hall_name, r.hall_type ,d.department_name
        FROM bookings_pu b 
        JOIN venue r ON b.hall_id = r.hall_id 
        LEFT JOIN 
    departments d ON r.department_id = d.department_id
        WHERE b.status = 'Pending' AND r.school_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $dean_school_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['booking_id'], $_POST['new_status'])) {
    // Sanitize input
    $booking_id = filter_input(INPUT_POST, 'booking_id', FILTER_SANITIZE_NUMBER_INT);
    $new_status = filter_input(INPUT_POST, 'new_status', FILTER_SANITIZE_STRING);

    // Validate status
    if (!in_array($new_status, ['Approved', 'Rejected'])) {
        $message = '<div class="alert alert-danger" role="alert">Invalid status.</div>';
    } else {
        // Update status
        $sql_update = "UPDATE bookings_pu 
                       SET status = ? 
                       WHERE booking_id = ? 
                       AND EXISTS (
                           SELECT 1 
                           FROM venue r 
                           WHERE r.hall_id = (SELECT hall_id FROM bookings_pu WHERE booking_id = ?) 
                           AND r.school_id = ?
                       )";
        $stmt = $db->prepare($sql_update);
        $stmt->bind_param("siii", $new_status, $booking_id, $booking_id, $dean_school_id);

        if ($stmt->execute()) {
            // Set success message in session
            $_SESSION['message'] = "Booking status updated to $new_status.";
        } else {
            // Set error message in session
            $_SESSION['message'] = "Error updating status: " . $stmt->error;
        }
        
        // Redirect to the same page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit; // Stop further execution
        
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Hall Bookings - Pondicherry University</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    .btn{
        padding: 5px 10px;
    
    border: none;
    cursor: pointer;
    border-radius: 5px;
    margin: 0 auto; /* To center the button */
    display: block; 

    }
</style>
<body>
<?php include "header.php"; ?>

<div class="container mt-5">
    <table class="table table-bordered">
        <thead>
            <tr>
               <th>Hall Type</th>
                <th>Name of the Hall</th>
                <th>Booked By</th>
                <th> <center>Date</center></th>
                <th> <center>Timing</center></th>
                <th>Status</th>
                <th> <center>Action</center></th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo strtoupper(htmlspecialchars($row['hall_type'])); ?></td>
                        <td><?php echo htmlspecialchars($row['hall_name']); ?></td>
                        <td><b><span style="color:#5f000a;"><?php echo $row['organiser_department'];?></span></b><br>
       <span style="color:#08005f;"><?php echo $row['organiser_name'];?><br></span> 
        <?php echo $row['organiser_mobile'];?><br>
        <?php echo $row['organiser_email'];?>
        </td>  <td><center><?php echo date('Y-m-d', strtotime($row['start_date'])); ?> <br>to<br>
        <?php echo date('Y-m-d', strtotime($row['end_date'])); ?></td></center>
                        <td><center><?php echo date('h:i A', strtotime($row['start_time'])); ?><br>to<br>
                        <?php echo date('h:i A', strtotime($row['end_time'])); ?></td></center>
                        <td><?php echo ucwords(htmlspecialchars($row['status'])); ?></td>
                        <td>
                            <form action="" method="post" style="display:inline;">
                                <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($row['booking_id']); ?>">
                                <button type="submit" name="new_status" value="Approved" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this booking?');">Approve</button>
                                <button style="padding:5px 17px" type="submit" name="new_status" value="Rejected" class="btn btn-danger" onclick="return confirm('Are you sure you want to reject this booking?');">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8">No pending requests.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$db->close();
?>