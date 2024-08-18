<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "department";

$roll = $_POST['roll'];
$name = $_POST['name'];
$email = $_POST['email'];
$pwd = $_POST['pwd'];
$phone = $_POST['phone'];
$course = $_POST['course'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn -> connect_error){
    die('Connection failed : '.$conn->connect_error);
}
else{
    echo "Connection successful";
}

$sql = "INSERT INTO department_value(roll, name, email, pwd, phone, course) VALUES ('$roll','$name', '$email', '$pwd', '$phone','$course')";

if($conn->query($sql)===TRUE){
    echo "<br>";
    echo "New record came in succesfully";
}
else{
    echo "Error: " .$sql. "<br>".$conn->error;
}

?>