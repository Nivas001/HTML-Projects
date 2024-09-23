<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Manrope:wght@200..800&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">


    <title>Admin Dashboard</title>

    <style>
        body{
            font-family: "Inter", sans-serif;
        }

        h2,h3{
            text-align: center;
        }

        .tb{
            margin: 0 auto;
            border-collapse: collapse;
        }
    </style>

</head>
<body>
<h2>Welcome, Admin</h2>

<h3>All Employee Details:</h3>

<table class="tb">
    <tr>
        <th>Employee</th>
    </tr>
</table>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name FROM users WHERE role='employee'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
//        echo "Employee: " . $row["name"] . "<br>";
        echo "<table class='tb'> 
                <tr>
                    <td>" . $row["name"] . "</td>
                </tr>
                </table>";
    }
} else {
    echo "No employees found.";
}

$conn->close();
?>
</body>
</html>
