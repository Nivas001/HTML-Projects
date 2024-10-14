const bookings = {
    '2024-10-10': {status: 'booked', bookedBy: 'John Doe'},
    '2024-10-15': {status: 'booked', bookedBy: 'Jane Smith'}
};

if (bookings[dateString]) {
    dayElement.setAttribute('title', `Booked by: ${bookings[dateString].bookedBy}`);
    console.log(dayElement.getAttribute('title')); // Debugging
}


document.addEventListener('DOMContentLoaded', function () {
const calendars = document.querySelectorAll('.calendar');
let currentDate = new Date();

const bookings = {
'2024-10-10': {status: 'booked', bookedBy: 'John Doe'},
'2024-10-12': {status: 'booked', bookedBy: 'Jane Smith'}
};

function updateCalendar(calendar, date) {
const headerMonth = calendar.querySelector('h4');
const calendarGrid = calendar.querySelector('.calendar-grid');

headerMonth.textContent = date.toLocaleString('default', {month: 'long', year: 'numeric'});

// Clear existing calendar days
while (calendarGrid.children.length > 7) {
calendarGrid.removeChild(calendarGrid.lastChild);
}

const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
const today = new Date().toISOString().split('T')[0];

// Add empty cells for days before the 1st
for (let i = 0; i < firstDay.getDay(); i++) {
const emptyDay = document.createElement('div');
emptyDay.className = 'calendar-day';
calendarGrid.appendChild(emptyDay);
}

// Add calendar days
for (let i = 1; i <= lastDay.getDate(); i++) {
const dayDate = new Date(date.getFullYear(), date.getMonth(), i);
const dateString = dayDate.toISOString().split('T')[0];
const dayElement = document.createElement('div');
dayElement.className = 'calendar-day';
dayElement.textContent = i;
dayElement.dataset.date = dateString;

if (dateString === today) {
dayElement.classList.add('today');
}

// Set the appropriate class based on booking status
if (dateString < today) {
dayElement.classList.add('past');
} else if (bookings[dateString]) {
// Mark as booked and add a tooltip with the name of the person who booked it
dayElement.classList.add(bookings[dateString].status.toLowerCase());
dayElement.setAttribute('title', `Booked by: ${bookings[dateString].bookedBy}`);
} else {
dayElement.classList.add('available');
}

calendarGrid.appendChild(dayElement);
}
}

calendars.forEach((calendar, index) => {
const prevMonthBtn = calendar.querySelector('.prev-month');
const nextMonthBtn = calendar.querySelector('.next-month');

let calendarDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + index, 1);

updateCalendar(calendar, calendarDate);

prevMonthBtn.addEventListener('click', () => {
calendarDate.setMonth(calendarDate.getMonth() - 1);
updateCalendar(calendar, calendarDate);
});

nextMonthBtn.addEventListener('click', () => {
calendarDate.setMonth(calendarDate.getMonth() + 1);
updateCalendar(calendar, calendarDate);
});
});
});

