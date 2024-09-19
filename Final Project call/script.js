// Dummy data array
const patients = [
    { time: "10:30", name: "Abdul", age: 25, problem: "Bleeding gums", gender: "Male", patientId: 1, dob: "05 May 1999", extra_illness: false, on_medication: true, blood_transfusion: false, allergy: "None" },
    { time: "11:00", name: "Ahmed", age: 22, problem: "Bad Breath", gender: "Male", patientId: 2, dob: "01 August 2002", extra_illness: false, on_medication: true, blood_transfusion: false, allergy: "Ice" },
    { time: "11:30", name: "Krishna", age: 28, problem: "Jaw Pain" ,gender: "Male", patientId: 3, dob: "28 July 1996", extra_illness: false, on_medication: false, blood_transfusion: true, allergy: "None"},
    { time: "12:00", name: "Radha", age: 26, problem: "Sensitivity when biting", gender: "Female", patientId: 4, dob: "25 September 1997", extra_illness: true, on_medication: true, blood_transfusion: false, allergy: "None" },
    { time: "12:30", name: "Shahjahan", age: 24, problem: "Grinding teeth" ,gender: "Male", patientId: 5, dob: "15 February 2000", extra_illness: true, on_medication: false, blood_transfusion: true, allergy: "None"},
    { time: "5:00", name: "Mumtaz", age: 27, problem: "Periodontal problem", gender: "Female", patientId: 6, dob: "22 December 1996", extra_illness: true, on_medication: true, blood_transfusion: false, allergy: "None" },
    { time: "5:30", name: "Arjith Singh", age: 37, problem: "Sores", gender: "Male", patientId: 7, dob: "25 May 1986", extra_illness: false, on_medication: false, blood_transfusion: true, allergy: "None" },
    { time: "6:00", name: "Harini", age: 21, problem: "Gum/Tooth Pain", gender: "Female", patientId: 8, dob: "31 January 2003", extra_illness: false, on_medication: false, blood_transfusion: false, allergy: "None" },
    { time: "6:30", name: "Hema", age: 22, problem: "Food collection between teeth and bleeding gums", gender: "Female", patientId: 9, dob: "28 February 2002", extra_illness: true, on_medication: false, blood_transfusion: true, allergy: "None" }
];

//Dummy Yesterday data array
const yesterdayPatients = [
    { time: "10:30", name: "Wafiq", age: 25, problem: "Tooth Extraction", gender: "Male", patientId: 10, dob: "05 May 1999", extra_illness: false, on_medication: true, blood_transfusion: false, allergy: "None" },
    { time: "11:00", name: "Sara", age: 22, problem: "Cavity", gender: "Female", patientId: 11, dob: "05 May 1999", extra_illness: false, on_medication: true, blood_transfusion: false, allergy: "None" },
    { time: "11:30", name: "Raj", age: 28, problem: "Gum Disease", gender: "Male", patientId: 12, dob: "1 June 1996", extra_illness: true, on_medication: false, blood_transfusion: false, allergy: "latex"},
];

//Dummy Tomorrow data array
const tomorrowPatients = [
    { time: "10:30", name: "Rahul", age: 25, problem: "Wisdom Tooth Pain", gender: "Male", patientId: 13, dob: "05 May 1999", extra_illness: false, on_medication: true, blood_transfusion: false, allergy: "None" },

];



// Function to filter and generate cards dynamically based on the selected date (Today, Yesterday, Tomorrow)
function generateCardsBasedOnSelection() {
    const selectedDate = document.querySelector('#dateFilter').value;
    let filteredPatients;

    // Get the heading element where the text will be updated
    const patientLabel = document.querySelector('#patientLabel');


    // Filter logic based on the dropdown selection
    if (selectedDate === 'yesterday') {
        filteredPatients = yesterdayPatients;
        patientLabel.textContent = 'Yesterday\'s Patients';
    } else if (selectedDate === 'tomorrow') {
        filteredPatients = tomorrowPatients;
        patientLabel.textContent = 'Tomorrow\'s Patients';
    } else {
        filteredPatients = patients; // Default to today's patients
        patientLabel.textContent = 'Today\'s Patients';
    }

    const cardContainer = document.querySelector('.card-container');
    cardContainer.innerHTML = ''; // Clear existing cards before generating new ones

    filteredPatients.forEach(patient => {
        createCard(patient); // Use the helper function to create the card
    });

    // Re-attach the event listeners to the new cards
    addevent();
}




// Helper function to create a card
function createCard(patient) {
    const cardContainer = document.querySelector('.card-container');

    // Create a card div
    const cardDiv = document.createElement('div');
    cardDiv.classList.add('card');

    // Create the time element
    const timeDiv = document.createElement('div');
    timeDiv.classList.add('time');
    timeDiv.textContent = patient.time;

    // Create the table element
    const table = document.createElement('table');
    const nameRow = createTableRow('Name', patient.name);
    const ageRow = createTableRow('Age', patient.age);
    const problemRow = createTableRow('Problem', patient.problem);
    const genderRow = createTableRow('Gender', patient.gender);
    const patientIdRow = createTableRow('PatientId', patient.patientId);
    const dobRow = createTableRow('DOB', patient.dob);
    const extraIllnessRow = createTableRow('Extra Illness', patient.extra_illness ? 'Yes' : 'No');
    const onMedicationRow = createTableRow('On Medication', patient.on_medication ? 'Yes' : 'No');
    const bloodTransfusionRow = createTableRow('Recent Blood Transfusion', patient.blood_transfusion ? 'Yes' : 'No');
    const allergyRow = createTableRow('Allergy', patient.allergy ? patient.allergy : 'None');

    // Hide extra details initially
    genderRow.style.display = 'none';
    patientIdRow.style.display = 'none';
    dobRow.style.display = 'none';
    extraIllnessRow.style.display = 'none';
    onMedicationRow.style.display = 'none';
    bloodTransfusionRow.style.display = 'none';
    allergyRow.style.display = 'none';

    table.appendChild(nameRow);
    table.appendChild(ageRow);
    table.appendChild(problemRow);
    table.appendChild(genderRow);
    table.appendChild(patientIdRow);
    table.appendChild(dobRow);
    table.appendChild(extraIllnessRow);
    table.appendChild(onMedicationRow);
    table.appendChild(bloodTransfusionRow);
    table.appendChild(allergyRow);

    // Create the button
    const buttonDiv = document.createElement('div');
    buttonDiv.classList.add('button-container');
    const button = document.createElement('button');
    button.classList.add('btn');
    button.textContent = "Details";
    buttonDiv.appendChild(button);

    // Append everything to the card
    cardDiv.appendChild(timeDiv);
    cardDiv.appendChild(table);
    cardDiv.appendChild(buttonDiv);

    // Append the card to the container
    cardContainer.appendChild(cardDiv);

    // Attach extra details to the card for expansion
    cardDiv.genderRow = genderRow;
    cardDiv.patientIdRow = patientIdRow;
    cardDiv.dobRow = dobRow;
    cardDiv.extraIllnessRow = extraIllnessRow;
    cardDiv.onMedicationRow = onMedicationRow;
    cardDiv.bloodTransfusionRow = bloodTransfusionRow;
    cardDiv.allergyRow = allergyRow;
}




// Helper function to create table rows
function createTableRow(label, value) {
    const row = document.createElement('tr');
    const labelCell = document.createElement('td');
    const valueCell = document.createElement('td');

    labelCell.textContent = label;
    valueCell.textContent = value;

    row.appendChild(labelCell);
    row.appendChild(valueCell);

    return row;
}





// Function to add event listener to the buttons
function addevent() {
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const card = this.closest('.card');

            // Check if the card is already expanded
            if (card.classList.contains('expanded')) {
                // If it's expanded, minimize it and reset the view
                card.classList.remove('expanded');

                // Hide the additional details
                card.genderRow.style.display = 'none';
                card.patientIdRow.style.display = 'none';
                card.dobRow.style.display = 'none';
                card.extraIllnessRow.style.display = 'none';
                card.onMedicationRow.style.display = 'none';
                card.bloodTransfusionRow.style.display = 'none';
                card.allergyRow.style.display = 'none';

                // Reset the button text back to "Details"
                this.textContent = "Details";

                // Show all cards again based on the current filter selection
                generateCardsBasedOnSelection();
            } else {
                // Expand the card if it's not already expanded
                card.classList.add('expanded');

                // Show the additional details
                card.genderRow.style.display = '';
                card.patientIdRow.style.display = '';
                card.dobRow.style.display = '';
                card.extraIllnessRow.style.display = '';
                card.onMedicationRow.style.display = '';
                card.bloodTransfusionRow.style.display = '';
                card.allergyRow.style.display = '';

                // Change button text to "Back"
                this.textContent = "Back";

                // Hide all other cards
                const cards = document.querySelectorAll('.card');
                cards.forEach(c => {
                    if (c !== card) {
                        c.style.display = 'none';
                    }
                });
            }
        });
    });
}



// Attach event listener to dropdown change
document.querySelector('#dateFilter').addEventListener('change', generateCardsBasedOnSelection);

// Call generateCardsBasedOnSelection on page load
window.onload = function () {
    generateCardsBasedOnSelection(); // Default selection
};





