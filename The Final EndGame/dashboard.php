<?php

    session_start();

    if(isset($_SESSION['email'])){
        $email = $_SESSION['email'];

        $conn = new mysqli("localhost", "root", "", "ext");
        $sql = "Select * from details where email = '$email'";
        $result = $conn->query($sql);

        $user = $result->fetch_assoc();

        $name = $user['username'];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $username = $_POST['username'];

            $sql = "Update details set username = '$username' where email = '$email' ";

            $result = $conn->query($sql);

        }
    }

    else{
        echo "<script>
            alert('Please log in to view the dashboard!');
            window.location.href = 'login.php';
          </script>";
        exit(); // Stop the script execution after redirect
    }
?>

<html lang="en">
    <head>
        <title>Dashboard</title>
        <style>
            .navbar{
                display: flex;
                justify-content: space-between;
                font-size: 18px;
                font-family: Arial, 'sans-serif';
                padding : 0 60px;
                height: 70px;
                background-color: grey;
            }
            .left{
                display: flex;
            }
            .right{
                display: flex;
                gap: 20px;
            }

            a{
                margin: auto;
                color: white;
                text-decoration: none;
            }

            a:hover{
                text-decoration: underline;
                color: #0a0e14;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="navbar">
                <div class="left">
                    <a href="#">Home</a>
                </div>

                <div class="right">
                    <a href="about.php">About</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>

            <div class="Details">
                <h2>Personal Details</h2>

                <form action="dashboard.php" method="post">
                    <label for="username">Username</label><br>
                    <input type="text" id="username" name="username" value= <?php echo $name?>>


                    <button type="submit">Update</button>

                </form>
            </div>
        </div>
    </body>
</html>
