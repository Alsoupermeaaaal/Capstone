let salonBookedDates = {
    "davids": {
        "2024-09-10": ["09:00 AM", "11:00 AM"],
        "2024-09-12": ["10:00 AM"],
        "2024-09-15": ["12:00 PM"],
        "2024-09-20": [] // No slots booked
    },
    "vetter": {
        "2024-09-10": ["10:00 AM", "02:00 PM"],
        "2024-09-12": ["01:00 PM", "03:00 PM"],
        "2024-09-15": [],
        "2024-09-20": [] // No slots booked
    },
    "kanjis": {
        "2024-09-10": [],
        "2024-09-12": ["01:00 PM"],
        "2024-09-15": ["10:00 AM", "11:00 AM"],
        "2024-09-20": ["12:00 PM"] // Booked
    }
};
 
// Define unique time slots for each salon
const salonTimeSlots = {
    "davids": ["09:00 AM", "10:00 AM", "11:00 AM", "12:00 PM", "01:00 PM"],
    "vetter": ["10:00 AM", "12:00 PM", "02:00 PM", "03:00 PM", "04:00 PM"],
    "kanjis": ["08:00 AM", "10:00 AM", "11:00 AM", "01:00 PM", "02:00 PM"]
};

// Keep track of the current month, year, and selected salon
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();
let selectedSalon = "davids"; // Default selection

// Track the user's current selection (date and time)
let userSelection = { date: null, time: null };

// Function to render the calendar
function renderCalendar() {
    const calendarDiv = document.getElementById('calendar');
    calendarDiv.innerHTML = '';

    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    const monthAndYear = document.createElement('div');
    monthAndYear.className = 'month-and-year';
    monthAndYear.innerText = `${new Date(currentYear, currentMonth).toLocaleString('default', { month: 'long' })} ${currentYear}`;
    calendarDiv.appendChild(monthAndYear);

    const daysOfWeek = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
    const daysRow = document.createElement('div');
    daysRow.className = 'days-row';
    daysOfWeek.forEach(day => {
        const dayCell = document.createElement('div');
        dayCell.className = 'day-cell';
        dayCell.innerText = day;
        daysRow.appendChild(dayCell);
    });
    calendarDiv.appendChild(daysRow);

    const datesGrid = document.createElement('div');
    datesGrid.className = 'dates-grid';

    // Empty cells for days before the start of the month
    for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.className = 'date-cell';
        datesGrid.appendChild(emptyCell);
    }

    // Create date cells
    for (let date = 1; date <= daysInMonth; date++) {
        const formattedDate = `${currentYear}-${(currentMonth + 1).toString().padStart(2, '0')}-${date.toString().padStart(2, '0')}`;
        const dateCell = document.createElement('div');
        dateCell.className = 'date-cell';
        dateCell.innerText = date;

        // Check if the key exists in the object before trying to access it
        if (salonBookedDates[selectedSalon][formattedDate]) {
            const bookedTimes = salonBookedDates[selectedSalon][formattedDate];

            // Marking the date based on booking status
            if (bookedTimes.length === salonTimeSlots[selectedSalon].length) {
                dateCell.classList.add('fully-booked');
                dateCell.style.cursor = 'not-allowed';
                dateCell.addEventListener('click', (e) => {
                    e.preventDefault();
                });
            } else if (bookedTimes.length > 0) {
                dateCell.classList.add('partially-booked');
            } else {
                dateCell.classList.add('available');
            }
        } else {
            // If the key does not exist, add the 'available' class
            dateCell.classList.add('available');
        }

        // Disable Sundays
        const dayOfWeek = new Date(currentYear, currentMonth, date).getDay();
        if (dayOfWeek === 0) {
            dateCell.classList.add('disabled-date');
            dateCell.style.cursor = 'not-allowed';
            dateCell.addEventListener('click', (e) => e.preventDefault());
        } else {
            // Add event listener for date clicks if not fully booked
            dateCell.addEventListener('click', () => {
                if (!dateCell.classList.contains('fully-booked')) {
                    // Clear previous selection mark
                    const previouslySelected = document.querySelector('.selected-date');
                    if (previouslySelected) {
                        previouslySelected.classList.remove('selected-date');
                    }

                    // Highlight the clicked date
                    dateCell.classList.add('selected-date');

                    // Load available times for the selected date
                    loadAvailableTimes(formattedDate);
                }
            });
        }

        datesGrid.appendChild(dateCell);
    }

    calendarDiv.appendChild(datesGrid);
}

// Function to load available times for the selected date and salon
function loadAvailableTimes(selectedDate) {
    const timeSlotsDiv = document.getElementById('timeSlots');
    timeSlotsDiv.innerHTML = '';  // Clear previous time slots

    const bookedTimes = salonBookedDates[selectedSalon][selectedDate] || [];
    const timeSlots = salonTimeSlots[selectedSalon]; // Get the time slots for the selected salon

    // Show time slots only when a date is clicked
    if (bookedTimes.length === timeSlots.length) {
        timeSlotsDiv.innerHTML = '<p>Fully booked</p>';
    } else if (bookedTimes.length === 0) {
        const form = document.createElement('form');
        timeSlots.forEach(time => {
            const isBooked = bookedTimes.includes(time);

            const label = document.createElement('label');
            label.style.display = 'block';
            label.style.marginBottom = '5px';

            const radioButton = document.createElement('input');
            radioButton.type = 'radio';
            radioButton.name = 'timeSlot';
            radioButton.disabled = isBooked; // Disable if booked
            radioButton.value = time;

            if (!isBooked) {
                radioButton.addEventListener('click', () => {
                    userSelection = { date: selectedDate, time: time };
                });
            }

            const statusSpan = document.createElement('span');
            statusSpan.innerText = isBooked ? 'Booked' : 'Available';
            statusSpan.style.color = isBooked ? 'red' : 'green';
            statusSpan.style.marginLeft = '10px';

            label.appendChild(radioButton);
            label.appendChild(document.createTextNode(time));
            label.appendChild(statusSpan);
            form.appendChild(label);
        });
        timeSlotsDiv.appendChild(form);
    }
}

document.getElementById('salon_select').addEventListener('change', (event) => {
    selectedSalon = event.target.value; // Update the selected salon
    currentMonth = new Date().getMonth(); // Reset the current month
    currentYear = new Date().getFullYear(); // Reset the current year
    renderCalendar(); // Re-render the calendar for the selected salon
    document.getElementById('timeSlots').innerHTML = ''; // Clear previous time slots
});
// Function to handle month navigation
document.getElementById('prevMonth').addEventListener('click', function() {
    if (currentMonth === 0) {
        currentMonth = 11;
        currentYear--;
    } else {
        currentMonth--;
    }
    renderCalendar();
    checkPrevButtonVisibility();
});

document.getElementById('nextMonth').addEventListener('click', function() {
    if (currentMonth === 11) {
        currentMonth = 0;
        currentYear++;
    } else {
        currentMonth++;
    }
    renderCalendar();
    checkPrevButtonVisibility();
});

function checkPrevButtonVisibility() {
    const currentDate = new Date();
    const prevButton = document.getElementById('prevMonth');
    if (currentMonth === currentDate.getMonth() && currentYear === currentDate.getFullYear()) {
        prevButton.style.display = 'none'; // Hide if in the current month
    } else {
        prevButton.style.display = 'block'; // Show otherwise
    }
}

// Initial rendering of the calendar
renderCalendar();
checkPrevButtonVisibility();
