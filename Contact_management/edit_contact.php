<?php
require 'config.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM contacts WHERE id=$id");
$contact = $result->fetch_assoc();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
    <title>Edit Contact</title>
</head>
<body>
<div class="container mx-auto mt-5">
    <h2 class="text-2xl font-bold mb-4">Edit Contact</h2>
    <form action="update_contact.php" method="POST" class="space-y-4">
        <input type="hidden" name="id" value="<?php echo $contact['id']; ?>">
        <input type="text" name="name" value="<?php echo $contact['name']; ?>" class="border p-2 w-full" required>
        <input type="email" name="email" value="<?php echo $contact['email']; ?>" class="border p-2 w-full" required>
        <input type="text" name="phone" value="<?php echo $contact['phone']; ?>" class="border p-2 w-full" required>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Contact</button>
    </form>
    <a href="index.php" class="mt-4 inline-block text-blue-600">Back to Contact List</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>
</html>
