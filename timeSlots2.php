
    
    <!-- <div>
        <label for="salonSelect">Select a Salon:</label>
        <select id="salonSelect">
            <option value="vetterhealth">Vetter Health</option>
            <option value="kanji">Kanji</option>
            <option value="davids">Davids</option>
        </select>
    </div> -->

    <div id="timeSlotsContainer" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; 
    font-family: 'Poppins', sans-serif; text-align: center;"></div>
    
    <script>
        // Define the time slots for each salon
        const salonTimeSlots = {
            '1': { start: 8, end: 17 }, // 8 AM to 5 PM
            '2': { start: 8, end: 17 }, // 8 AM to 5 PM
            '3': { start: 9, end: 19 } // 9 AM to 7 PM
        };

        // Function to generate time slots based on selected salon
        function generateTimeSlots(salon) {
            const timeSlotsContainer = document.getElementById('timeSlotsContainer');
            timeSlotsContainer.innerHTML = ''; // Clear previous time slots
        
            if (salonTimeSlots[salon]) {
                const schedule = salonTimeSlots[salon];
        
                for (let hour = schedule.start; hour < schedule.end; hour++) {
                    // Convert hour to 12-hour format with AM/PM
                    const period = hour >= 12 ? 'PM' : 'AM';
                    const displayHour = hour % 12 === 0 ? 12 : hour % 12; // Adjust for 12 AM/PM
                    const formattedHour = `${displayHour} ${period}`;
        
                    // Format the value for the radio button
                    const value = `${hour.toString().padStart(2, '0')}:00`;
        
                    // Create the time slot element
                    const div = document.createElement('div');
                    div.innerHTML = `
                        <input type="radio" id="timeSlot${hour}" name="timeSlot" value="${value}">
                        <label for="timeSlot${hour}">${formattedHour}</label>
                    `;
                    timeSlotsContainer.appendChild(div);
                }
            }
        }


        // Event listener for salon selection
        // document.getElementById('salonSelect').addEventListener('change', function() {
        //     const selectedSalon = this.value;
        //     generateTimeSlots(selectedSalon); // Generate time slots based on selected salon
        // });

        // Initial call to generate time slots for the default selected salon
        // generateTimeSlots(document.getElementById('salonSelect').value);
    </script>