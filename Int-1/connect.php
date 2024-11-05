<?php

$server = "localhost";
$username = "root";
$password = "";
$db_name = "int1";

$conn = new mysqli($server, $username, $password, $db_name );

if($conn->connect_error){
    die("Conenction failed".conn->connect_error);
}

$fname = $_POST['uname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$password = md5($_POST['password']);

$sql = "Insert into form_post (fname, lname, email, password) values ('$fname', '$lname', '$email', '$password')";

if($conn->query($sql)===TRUE){
    echo "New data Inserted successfully";
}
else{
    echo "Failed".$sql."<br>".conn->error;
}