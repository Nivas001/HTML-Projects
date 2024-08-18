<?php
    session_start();
    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!==true){
        header("Location:/Department/register.html");
        exit();
    }
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : 'Email Not Found';

    $conn = new mysqli("localhost","root","","department");
    if($conn->connect_error){
        die("Connection Failed : ".$conn->connect_error);
    }
    else{
//        echo "Connection Successful<br>";
    }

    $stmt = $conn->prepare("Select * from department_value where email = ?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $db_details = $stmt->get_result();

    if($db_details->num_rows > 0){
        $row = $db_details->fetch_assoc();
        $name = $row['name'];
        $course = $row['course'];
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="about.css">
</head>
<body>
<header class="p-3 mb-3 border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="#" class="nav-link px-2 link-secondary">Profile</a></li>
                <li><a href="#" class="nav-link px-2 link-body-emphasis">Sign out</a></li>

            </ul>

            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
                <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
            </form>

            <div class="dropdown text-end">
                <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
                </a>
                <ul class="dropdown-menu text-small">
                    <li><a class="dropdown-item" href="#">New project...</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Sign out</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>


<!--<h1>Welcome --><?php //echo htmlspecialchars($name); ?><!--</h1>-->
<!--<p> Course : --><?php //echo htmlspecialchars($course); ?><!--</p>-->
</body>
</html>