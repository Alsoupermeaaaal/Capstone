document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  const salonID = document.getElementById('salon_select');
  
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    contentHeight: 400,
    selectable: true,
    themeSystem: 'standard',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
    },

    eventSources: [{
      url: 'ajax/monthSchedule.php',
      method: 'GET',
      extraParams: {
        salon_id: salonID.value
      }
     }
    ],
    eventColor: 'transparent', 
    eventBackgroundColor: 'transparent',
    eventDidMount: function (info) {
      const eventDate = info.event.start; // Get the event date
      const today = new Date(); // Get today's date
      today.setHours(0, 0, 0, 0); // Set time to midnight for comparison
      const yesterday = new Date(today);
      yesterday.setDate(today.getDate() - 1); // Get yesterday's date

      // Check if the event date is in the past or is yesterday
        // Style for available dates (today and onwards)
        info.el.style.backgroundColor = 'green';
        info.el.style.border = '1px solid green';
        info.el.querySelector('.fc-event-title').innerText  = "Available";
        
        // Check if the event is marked as full
        if (info.event.extendedProps && info.event.extendedProps.status === 'full') {
          info.el.style.backgroundColor = 'red';
          info.el.style.border = '1px solid red';
          info.el.querySelector('.fc-event-title').innerText  = "Full";
          info.el.style.cursor = 'not-allowed';
        }
        if (eventDate < today) {
          info.el.style.backgroundColor = 'gray'; // Style for unavailable dates
          info.el.style.border = '1px solid gray';
          info.el.querySelector('.fc-event-title').innerText  = "Unavailable";
          info.el.style.cursor = 'not-allowed';
        }
    },
    dateClick: function (info) {
      // Triggered when a date is clicked
      document.getElementById('selectedDate').value = info.dateStr;
      handleDateClick(info.dateStr); // Pass the clicked date to your function
    },
    
  });

  calendar.render();

  salonID.addEventListener('change', function () {
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      contentHeight: 400,
      selectable: true,
      themeSystem: 'standard',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
      },
  
      eventSources: [{
        url: 'ajax/monthSchedule.php',
        method: 'GET',
        extraParams: {
          salon_id: salonID.value
        }
       }
      ],
      eventColor: 'transparent', 
      eventBackgroundColor: 'transparent',
      eventDidMount: function (info) {
        const eventDate = info.event.start; // Get the event date
        const today = new Date(); // Get today's date
        today.setHours(0, 0, 0, 0); // Set time to midnight for comparison
        const yesterday = new Date(today);
        yesterday.setDate(today.getDate() - 1); // Get yesterday's date

        // Check if the event date is in the past or is yesterday
          // Style for available dates (today and onwards)
          if (info.event.extendedProps && info.event.extendedProps.status === 'available') {
            info.el.style.backgroundColor = 'green';
            info.el.style.border = '1px solid green';
            // info.el.query.querySelector('.fc-event-title').innerText  = "Full";
            info.el.style.cursor = 'not-allowed';
          }
          // Check if the event is marked as full
          if (info.event.extendedProps && info.event.extendedProps.status === 'full') {
            info.el.style.backgroundColor = 'red';
            info.el.style.border = '1px solid red';
            // info.el.query.querySelector('.fc-event-title').innerText  = "Full";
            info.el.style.cursor = 'not-allowed';
          }
          if (eventDate < today) {
            console.log(eventDate,"date unavailable");
            info.el.style.backgroundColor = 'gray'; // Style for unavailable dates
            info.el.style.border = '1px solid gray';
            info.el.querySelector('.fc-event-title').innerText  = "Unavailable";
            info.el.style.cursor = 'not-allowed';
            console.log(info.el.querySelector('.fc-event-title').innerText)
          } 
      },
      dateClick: function (info) {
        document.getElementById('selectedDate').value = info.dateStr;
        handleDateClick(info.dateStr); 
      },
      
    });
  
    calendar.render();
  });
});

// New functions added here
function handleDateClick(date) {
  const selectedDate = date;
  const salonID = document.getElementById('salon_select');
  
  // Build URL string
  const url = new URL('ajax/daySchedule.php', window.location.origin);
  url.searchParams.append('date', selectedDate);
  url.searchParams.append('salon_id', salonID.value);

  // Run daySchedule query
  fetch(url)
    .then(response => response.json())
    .then(data => {
      disableRadioTime(data,selectedDate); // Disable the time slots based on the booked times
    })
    .catch(error => {
      console.error('Fetch error:', error);
    });
}

function disableRadioTime(data,date) {
  const radios = document.getElementsByName('timeSlot'); // Assuming your radio buttons have this name
  const bookedTimes = data.length > 0 ? data : []; // Get booked times from the response
  const today = new Date();
  const currentTime = today.getHours();

  radios.forEach(radio => {
    const label = radio.parentElement; // Get the label for the radio button
    if (bookedTimes.includes(radio.value) 
      || (date === today.toISOString().split('T')[0] && currentTime >= radio.value.split(':')[0]) 
      || date < today.toISOString().split('T')[0]) {
      radio.disabled = true; // Disable the radio input
      label.classList.add("timeSlot-occupied"); // Add a class to style the occupied time slots
    } else {
      radio.disabled = false; // Enable the radio input
      label.classList.remove("timeSlot-occupied"); // Remove the occupied class
    }
  });
}