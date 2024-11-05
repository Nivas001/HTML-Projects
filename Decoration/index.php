<?php


    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "decoration";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);

    $stmt = $conn->prepare("INSERT INTO register (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $password);


    if($stmt->execute()){
        echo "New record created successfully";
    }
    else{
        echo "Error: " . $stmt . "<br>" . $conn->error;
    }

?>
