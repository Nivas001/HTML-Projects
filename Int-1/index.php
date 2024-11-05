<html lang="en">
<head>
    <meta http-equiv="refresh" >
    <title>Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container w-50">
        <h1>Register to our Organization</h1>

        <form class="row g-3" method="post" action="connect.php">
            <label for="username">Name</label>
            <div class="row">

                <div class="col">
                    <input type="text" name="uname" id="username" placeholder="Firstname" class="form-control">
                </div>

                <div class="col">
                    <input type="text" name="lname" id="username" placeholder="Lastname" class="form-control"><br>
                </div>
            </div>

            <div class="row-mb-2">
                <label for="email">Email</label>
                <input type="type" name="email" id="email" class="form-control">
            </div>

            <div class="row-sm-12">
                <label for="pass">Password</label>
                <input type="password" name="password" id="pass" class="form-control">
            </div>

            <div class="row button">
                <button type="submit" class="btn btn-primary">Submit</button>



            </div>







        </form>

    </div>

</body>
</html>