<?php

?>

<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            padding: 10px 30px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #333333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 14px;
            color: #555;
            /*margin-bottom: 5px;*/
        }

        input[type="text"],
        input[type="date"],
        input[type="password"],
        input[type="radio"] {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
        }

        input[type="radio"] {
            width: auto;
            margin-right: 5px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .gender-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .gender-group label {
            margin: 0;
        }

        p{
            margin-bottom: 0;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Register to use our Service!</h2>

    <form action="register.php" method="post">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Jane Doe" required/>

        <label for="email">Email</label>
        <input type="text" id="email" name="email" placeholder="janedoe@gmail.com" required/>

        <label for="phone">Phone number</label>
        <input type="text" id="phone" name="phone" placeholder="7417418520" maxlength="10" required/>

        <label for="dob">Date of Birth</label>
        <input type="date" id="dob" name="dob" placeholder="" required/>

        <div class="gender-group">
            <label for="male">Male</label>
            <input type="radio" id="male" name="gender" value="Male"/>
            <label for="female">Female</label>
            <input type="radio" id="female" name="gender" value="Female"/>
        </div>

        <label for="pwd">Password</label>
        <input type="password" id="pwd" name="pwd" placeholder="ADe$4KilF;" minlength="8" required/>

        <button type="submit">Submit Details</button>

        <p>Already have an account? <a href="login.php">Click here </a></p>
    </form>
</div>
</body>
</html>


<?php

    $conn = new mysqli ('localhost', 'root','','ext');

    if($conn->connect_error){
        die('Connection failed to establish '. $conn->connect_error());
    }

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        $username = $_POST['username'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $password = $_POST['pwd'];
        $password = md5($password);

        $sql = "Insert into details (username, email, phone, dob, gender, password) values ('$username', '$email', '$phone', '$dob', '$gender', '$password')";

        if($conn->query($sql)===True){
            echo "<script> 
                    alert('Successfully Registered');
                    window.location.href = 'login.php';
//                    setTimeout(function(){
//                        window.location.href = 'login.php';
//                        } ,3000
//                    );
              </script>";
        }

        else{
            echo "Some error occured and data has not been added in database" . $conn->connect_error;
        }
    }

    $conn->close();
?>