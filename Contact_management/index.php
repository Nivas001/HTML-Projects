<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
    <title>Contact Management</title>
</head>
<body>
<div class="container mx-auto mt-5">
    <h2 class="text-2xl font-bold mb-4">Contact List</h2>
    <a href="add_contact.php" class="bg-blue-500 text-white px-4 py-2 rounded">Add Contact</a>
    <table class="min-w-full mt-5 bg-white border border-gray-200 rounded-lg shadow">
        <thead>
        <tr class="bg-gray-100">
            <th class="p-2">Name</th>
            <th class="p-2">Email</th>
            <th class="p-2">Phone</th>
            <th class="p-2">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        require 'config.php';
        $result = $conn->query("SELECT * FROM contacts");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                            <td class='p-3'>{$row['name']}</td>
                            <td class='p-3'>{$row['email']}</td>
                            <td class='p-3'>{$row['phone']}</td>
                            <td class='p-3 flex gap-5'>
                                <a href='edit_contact.php?id={$row['id']}' class='text-blue-600'>Edit</a>
                                <a data-modal-target='popup-modal' data-id='{$row['id']}' class='delete-button text-red-600 cursor-pointer'>Delete</a>
                            </td>
                          </tr>";
        }
        ?>
        </tbody>
    </table>
    <div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden  top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this contact?</h3>
                    <form id="delete-form" action="delete_contact.php" method="POST">
                        <input type="hidden" name="id" id="contact-id">
                        <button data-modal-hide="popup-modal" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            Yes, I'm sure
                        </button>
                    </form>
                    <button data-modal-hide="popup-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, cancel</button>
                </div>
            </div>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
<script>
    const deleteButtons = document.querySelectorAll('.delete-button');
    const modal = document.getElementById('popup-modal');
    const contactIdInput = document.getElementById('contact-id');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const contactId = this.getAttribute('data-id');
            contactIdInput.value = contactId;
            modal.classList.remove('hidden');
        });
    });

    document.querySelector('[data-modal-hide="popup-modal"]').addEventListener('click', function() {
        modal.classList.add('hidden');
    });
</script>

</body>
</html>
