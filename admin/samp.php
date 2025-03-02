<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Booking</title>
    <style>
        .completed {
            color: green;
        }
        .ongoing {
            color: orange;
        }
    </style>
</head>
<body>
    <div id="booking1" class="booking" data-date="2024-07-10">Appointment 1</div>
    <div id="booking2" class="booking" data-date="2024-07-14">Appointment 2</div>
    <div id="booking3" class="booking" data-date="2024-07-20">Appointment 3</div>

    <script>
        function markBookings() {
            const bookings = document.querySelectorAll('.booking');
            const currentDate = new Date();

            bookings.forEach(booking => {
                const appointmentDate = new Date(booking.getAttribute('data-date'));

                if (currentDate > appointmentDate) {
                    booking.classList.add('completed');
                    booking.textContent += ' - Completed';
                } else {
                    booking.classList.add('ongoing');
                    booking.textContent += ' - On Going';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', markBookings);
    </script>
</body>
</html>
