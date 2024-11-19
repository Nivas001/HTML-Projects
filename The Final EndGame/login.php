<?php

?>

<html lang="en">
    <head>
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <style>
        body{
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
        }

        .container{
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 4px;
            padding: 30px;
            margin-top: 150px;
        }

        h2{
            color: #333;
        }

        label{
            font-size: 14px;
            color: #555;
        }

        input{
            font-size: 14px;
            border-radius: 4px;
            padding: 10px;
            border: 1px solid #ccc;
        }

        form{
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        button{
            font-size: 14px;
            padding: 10px;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            background-color: #007bff;
            border: none;
        }

        button:hover{
            background-color: #0056b3;
        }
    </style>
<body>
    <div class="container w-25">
        <h2>Login to our Service!</h2>

        <form action="#" method="post">
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" placeholder="janedoe@gmail.com" required /><br>

            <label for="pwd">Password</label><br>
            <input type="password" id="pwd" name="pwd" required /> <br>

            <button type="submit" class="w-50 align-self-center">Login</button>

            <p>Don't have an account <a href="register.php">Click here</a> to register</p>
        </form>
    </div>
</body>
</html>


<?php

     $conn = new mysqli('localhost', 'root', '' ,'ext');

     if($conn->connect_error){
         die("Connection failed ". $conn->connect_error);
     }

     if($_SERVER['REQUEST_METHOD'] === 'POST'){
         $email = $_POST['email'];
         $pwd = $_POST['pwd'];
         $pwd = md5($pwd);

         $sql = "Select * from details where email = '$email' and password = '$pwd' ";

         $result = $conn->query($sql);
         if($result && $result->num_rows == 1){
             $user = $result->fetch_assoc();

             session_start();
             $_SESSION['email'] = $user['email'];
             echo"<script>alert('Login Successful! Welcome ".$user['username']."') 
                            window.location.href = 'dashboard.php';
                </script>";
         }
         else{
             echo "<script>alert('Invalid Email or Password') 
                            window.location.href = 'login.php';
                </script>";
         }

     }

    $conn->close();

?>