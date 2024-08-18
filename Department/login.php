<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "department";

$email = $_POST['emails'];
$pwd = $_POST['pwds'];


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn -> connect_error){
    die('Connection failed : '.$conn->connect_error);
}
else{
    echo "Connection successful<br>";
}

$stmt = $conn->prepare("Select pwd from department_value where email = ?");
$stmt->bind_param("s",$email);
$stmt->execute();
$result = $stmt->get_result();

$db_pwd = "";

if($result->num_rows > 0){

    $row = $result->fetch_assoc();
    $db_pwd = $row['pwd'];
    if($pwd === $db_pwd){
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        header("Location: /Department/profile_page.php");
        exit();
    }
    else{
        $_SESSION['loggedin'] = false;
        echo "Password Wrong";
    }
    //echo "<br>Pass : $db_pwd"; For Debugging val.
}
else{
    echo ("Email : $email");
    echo "<br>No Rows detected";
}

$stmt->close();
$conn->close();
?>