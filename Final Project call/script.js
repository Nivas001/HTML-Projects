// Dummy data array
const patients = [
    { time: "10:30", name: "Abdul", age: 25, problem: "Bleeding gums", gender: "Male" },
    { time: "11:00", name: "Ahmed", age: 22, problem: "Bad Breath" },
    { time: "11:30", name: "Krishna", age: 28, problem: "Jaw Pain" },
    { time: "12:00", name: "Radha", age: 26, problem: "Sensitivity when biting" },
    { time: "12:30", name: "Shahjahan", age: 24, problem: "Grinding teeth" },
    { time: "5:00", name: "Mumtaz", age: 27, problem: "Periodontal problem" },
    { time: "5:30", name: "Arjith Singh", age: 37, problem: "Sores" },
    { time: "6:00", name: "Harini", age: 21, problem: "Gum/Tooth Pain" },
    { time: "6:30", name: "Hema", age: 22, problem: "Food collection between teeth and bleeding gums" }
];

// Function to generate the cards dynamically
function generateCards() {
    const cardContainer = document.querySelector('.card-container');

    patients.forEach(patient => {
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

        table.appendChild(nameRow);
        table.appendChild(ageRow);
        table.appendChild(problemRow);

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
    });
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

// Function to show details of a clicked card
function showDetails(index) {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, i) => {
        if (i === index) {
            card.classList.toggle('expanded');
        } else {
            card.classList.add('hidden');
        }
    });
}

// Call the function to generate cards when the page loads
window.onload = generateCards;
