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
        //echo "Connection Successful<br>";
    }
    $stmt = $conn->prepare("Select * from department_value where email = ?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $db_details = $stmt->get_result();

    if($db_details->num_rows > 0){
        $row = $db_details->fetch_assoc();
        $name = $row['name'];
        $course = $row['course'];
        $ph = $row['phone'];
        $abouts = $row['about'];
        $streets = $row['street'];
        $cities = $row['city'];
        $states = $row['state'];
        $countries = $row['country'];
        $zip_code = $row['zip'];
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form data
        $about = $conn->real_escape_string($_POST['about']);
        $street = $conn->real_escape_string($_POST['street-address']);
        $city = $conn->real_escape_string($_POST['city']);
        $state = $conn->real_escape_string($_POST['region']);
        $zip = $conn->real_escape_string($_POST['postal-code']);
        $country = $conn->real_escape_string($_POST['country']);

        // Insert the data into the database
        $stmt = $conn->prepare("Update department_value set about = ?, street = ?, city = ?, country = ?, state = ?, zip = ? where email = ?");
        $stmt->bind_param("sssssis",  $about, $street, $city, $country ,$state, $zip, $email);

        if ($stmt->execute()) {
            //echo "<p>Details have been saved successfully!</p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
        $conn->close();
    }
    else{

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
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css"  rel="stylesheet" />
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
                <li><a href="#" class="nav-link px-2 link-secondary cl">Profile</a></li>
                <li><a href="#" class="nav-link px-2 link-body-emphasis">Sign out</a></li>
                <li><a href="#offcanvasExample" class="nav-link px-2 link-body-emphasis sh" data-bs-toggle="offcanvas" role="button" aria-controls="offcanvasExample">Time Table</a></li>


            </ul>



            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
                <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
            </form>




        </div>
    </div>
</header>











<div class="container w-1/2 p-2 mt-5">
    <!--
  This example requires some changes to your config:

  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/forms'),
    ],
  }
  ```
-->
    <form method = "POST" action="">
        <div class="full-form">
            <div class="space-y-12">

            <div class="border-gray-900/10 pb-12 mb-5">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Profile</h2>
                <p class="mt-1 text-sm leading-6 text-gray-600">This information will be displayed publicly to others so.</p>

                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Username</label>
                        <div class="mt-2">
                            <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                <span class="flex select-none items-center pl-3 text-gray-500 sm:text-sm">pondiuni.com/</span>
                                <input type="text" name="username"  autocomplete="username" id="disabled-input-2" aria-label="disabled input 2" class="block cursor-not-allowed flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" value="<?php echo $name; ?>" disabled readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-full mt-5">
                        <label for="about" class="block text-sm font-medium leading-6 text-gray-900">About</label>
                        <div class="mt-2">
                            <textarea id="about" name="about" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"><?php echo $abouts; ?></textarea>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-gray-600">Write a few sentences about yourself.</p>
                    </div>

                    <div class="col-span-full mt-8">
                        <label for="photo" class="block text-sm font-medium leading-6 text-gray-900">Photo</label>
                        <div class="mt-2 flex justify-center flex-col items-center gap-x-3">
                            <svg class="h-16 w-16 text-gray-300" viewBox="0 0 22 22" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd" />
                            </svg>
                            <button type="button" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Change</button>
                        </div>
                    </div>


                </div>
            </div>

            <div class="border-b border-gray-900/10 pb-6">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Personal Information</h2>
                <p class="mt-1 text-sm leading-6 text-gray-600">Use a permanent address where you can receive mail.</p>

                <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                    <div class="flex gap-3">
                        <div class="sm:col-span-4">
                            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                            <div class="mt-2">
                                <input id="email" name="email" type="email"  class=" block cursor-not-allowed w-max rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" value="<?php echo $email; ?>" disabled readonly>
                            </div>
                        </div>

                        <div class="sm:col-span-4 ">
                            <label for="course" class="block text-sm font-medium leading-6 text-gray-900">Course </label>
                            <div class="mt-2">
                                <input id="course" name="course" type="email" class="block cursor-not-allowed w-1/4 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" value="<?php echo $course; ?>" disabled readonly>
                            </div>
                        </div>

                        <div class="sm:col-span-4 ">
                            <label for="course" class="block text-sm font-medium leading-6 text-gray-900">Phone </label>
                            <div class="mt-2">
                                <input id="course" name="course" type="email" class="block cursor-not-allowed w-1/4 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" value="<?php echo $ph; ?>" disabled readonly>
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-3 mt-4">
                        <label for="country" class="block text-sm font-medium leading-6 text-gray-900">Country</label>
                        <div class="mt-2">
                            <select id="country" name="country" autocomplete="country-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                <option>India</option>
                                <option>Nepal</option>
                                <option>Russia</option>
                                <option>Nederland</option>
                                <option>United States</option>
                                <option>Canada</option>
                                <option>Sri Lanka</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-full mt-4">
                        <label for="street-address" class="block text-sm font-medium leading-6 text-gray-900">Street address</label>
                        <div class="mt-2">
                            <input type="text" name="street-address" id="street-address" autocomplete="street-address" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" value="<?php echo $streets ?>">
                        </div>
                    </div>

                    <div class="sm:col-span-2 sm:col-start-1 mt-4">
                        <label for="city" class="block text-sm font-medium leading-6 text-gray-900">City</label>
                        <div class="mt-2">
                            <input type="text" name="city" id="city" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" value="<?php echo $cities; ?>">
                        </div>
                    </div>

                    <div class="sm:col-span-2 mt-4">
                        <label for="region" class="block text-sm font-medium leading-6 text-gray-900">State / Province</label>
                        <div class="mt-2">
                            <input type="text" name="region" id="region" autocomplete="address-level1" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" value="<?php echo $states; ?>">
                        </div>
                    </div>

                    <div class="sm:col-span-2 mt-4">
                        <label for="postal-code" class="block text-sm font-medium leading-6 text-gray-900">ZIP / Postal code</label>
                        <div class="mt-2">
                            <input type="text" name="postal-code" id="postal-code" autocomplete="postal-code" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" value="<?php echo $zip_code; ?>">
                        </div>
                    </div>
                </div>
            </div>


        </div>

                <div class="mt-6 flex items-center justify-end gap-6">
                    <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                     <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
        </div>
        </div>
    </form>



    <div class="relative tb overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3 rounded-s-lg">
                    Day
                </th>
                <th scope="col" class="px-6 py-3">
                    9:30 - 10:30
                </th>
                <th scope="col" class="px-6 py-3 rounded-e-lg">
                    10:30 - 11:30
                </th>
                <th scope="col" class="px-6 py-3">
                    11:30 - 12:30
                </th>
                <th scope="col" class="px-6 py-3 rounded-e-lg">
                    12:30 - 1:30
                </th>
                <th scope="col" class="px-6 py-3">
                    1:30 - 2:30
                </th>
                <th scope="col" class="px-6 py-3 rounded-e-lg">
                    2:30 - 3:30
                </th>
                <th scope="col" class="px-6 py-3">
                    3:30 - 4:30
                </th>

            </tr>
            </thead>
            <tbody>
            <tr class="bg-white dark:bg-gray-800">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Monday
                </th>
                <td colspan="3" class="px-6 py-4 text-center">
                    Web Technology lab
                </td>
                <td class="px-6 py-4">
                    -
                </td>
                <td class="px-6 py-4">
                    -
                </td>
                <td class="px-6 py-4">
                    MCS
                </td>
                <td class="px-6 py-4">
                    -
                </td>

            </tr>
            <tr class="bg-white dark:bg-gray-800">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Tuesday
                </th>
                <td class="px-6 py-4">
                    -
                </td>
                <td colspan="2" class="px-6 py-4 text-center">
                    MCS
                </td>
                <td class="px-6 py-4">
                    SNA
                </td>
                <td class="px-6 py-4">
                    -
                </td>
                <td class="px-6 py-4">
                    -
                </td>
                <td class="px-6 py-4">
                    -
                </td>
            </tr>
            <tr class="bg-white dark:bg-gray-800">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Wednesday
                </th>
                <td class="px-6 py-4">
                    -
                </td>
                <td colspan="2" class="px-6 py-4">
                    WT Theory
                </td>
                <td class="px-6 py-4">
                    -
                </td>
                <td class="px-6 py-4">
                    -
                </td>
                <td class="px-6 py-4">
                    -
                </td>
                <td class="px-6 py-4">
                    -
                </td>
            </tr>
            <tr class="bg-white dark:bg-gray-800">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Thursday
                </th>
                <td class="px-6 py-4">
                    -
                </td>
                <td class="px-6 py-4">
                    -
                </td>
                <td class="px-6 py-4">
                    -
                </td>
                <td class="px-6 py-4">
                    SNA
                </td>
                <td class="px-6 py-4">
                    -
                </td>
                <td colspan="2" class="px-6 py-4">
                    Software Engineering
                </td>
            </tr>
            <tr class="bg-white dark:bg-gray-800">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Friday
                </th>
                <td class="px-6 py-4">
                    -
                </td>
                <td class="px-6 py-4">
                    WT Theory
                </td>
                <td class="px-6 py-4">
                    -
                </td>
                <td class="px-6 py-4">
                    SNA
                </td>
                <td class="px-6 py-4">
                    -
                </td>
                <td class="px-6 py-4">
                    -
                </td>
                <td class="px-6 py-4">
                    -
                </td>
            </tr>
            </tbody>
            <tfoot>
            <tr class="font-semibold text-gray-900 dark:text-white">
                <th colspan="2" scope="row" class="px-6 py-3 text-base">Saturday & Sunday</th>
                <td  colspan="6" class="px-6 py-3">will be Holiday always. Enjoy!! Jin Jamaku Dhum dhum </td>
            </tr>
            </tfoot>
        </table>
    </div>




</div>




<!--Time table-->





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    document.querySelector('.tb').style.display = 'none';

    document.querySelector('.sh').addEventListener('click', function(){
        document.querySelector('.full-form').style.display = 'none';
        document.querySelector('.tb').style.display = 'block';
    });
    document.querySelector('.cl').addEventListener('click', function(){
        document.querySelector('.full-form').style.display = 'block';
        document.querySelector('.tb').style.display = 'none';
    });
</script>


</body>
</html>