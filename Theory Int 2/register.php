<?php
    include "connect.php";
?>
<html>
    <head>
        <title>Register</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container">
            <h2>Register</h2>
            <form action="#" method="post">
                <label for="fname">First Name </label><br>
                <input type="text" name="fname" id="fname"><br>
                <label for="email">Email </label><br>
                <input type="email" name="email" id="email"><br>
                <label for="pwd">Password</label><br>
                <input type="password" name="pwd" id="pwd"><br>

                <button type="submit">Submit</button>
            </form>
        </div>
    </body>
</html>

