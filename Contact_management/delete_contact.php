//<?php
//require 'config.php';
//
//$id = $_GET['id'];
//$conn->query("DELETE FROM contacts WHERE id=$id");
//header("Location: index.php");
//?>

<?php
require 'config.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->bind_param("i", $id); // "i" denotes the type (integer)

    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No ID provided.";
}
?>

