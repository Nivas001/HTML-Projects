<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee_details";

$name = $_POST['emp_name'];
$id = $_POST['emp_id'];
$gender = $_POST['gender'];
$email = $_POST['emp_email'];
$phone = $_POST['emp_phone'];
$address = $_POST['emp_address'];
$designation = $_POST['emp_desig'];
$salary = $_POST['emp_sal'];
$dob = date('Y-m-d', strtotime($_POST['emp_dob']));
$doj = date('Y-m-d', strtotime($_POST['emp_doj']));

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
else{
    echo "Connected successfully";
}

$sql = "INSERT INTO details (id, name, gender, email, phone, address, designation, salary, dob, doj) VALUES ('$id', '$name', '$gender', '$email', '$phone', '$address', '$designation', '$salary', '$dob', '$doj')";

if ($conn->query($sql) === TRUE){
    echo "<br>";
    echo "New record created successfully";
}
else{
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>