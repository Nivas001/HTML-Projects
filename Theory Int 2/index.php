<?php

?>

<html lang="en">
    <head>
        <title>Choose</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class="container">
            <h2>Choose what action to happen</h2>
            <div class="btns">
                <button type="submit" onclick="loadReg()">Register</button>
                <button type="submit" onclick="loadLog()">Login</button>
            </div>
        </div>
    <script>
        function loadReg(){
            location.href = "register.php";
        }

        function loadLog(){
            location.href = "login.php";
        }
    </script>
    </body>
</html>
