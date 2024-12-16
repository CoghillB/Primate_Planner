const date = new Date();
const renderCalendar = () => {
    date.setDate(1);
    const monthDays = document.querySelector(".days");
    const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
    const prevLastDay = new Date(date.getFullYear(), date.getMonth(), 0).getDate();
    const firstDayIndex = date.getDay();
    const lastDayIndex = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDay();
    const nextDays = 7 - lastDayIndex - 1;
    const months = [
        "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
    ];

    document.querySelector(".date h1").innerHTML = months[date.getMonth()];

    document.querySelector(".date p").innerHTML = new Date().toDateString();

    let days = "";

    for (let x = firstDayIndex; x > 0; x--) {
        days += `<div class="prev-date">${prevLastDay - x + 1}</div>`;
    }

    for (let i = 1; i <= lastDay; i++) {
        if (i === new Date().getDate() && date.getMonth() === new Date().getMonth()) {
            days += `<div class="current-day">${i}</div>`;
        } else {
            days += `<div>${i}</div>`;
        }
    }

    for (let j = 1; j <= nextDays; j++) {
        days += `<div class="next-date">${j}</div>`;
    }
    monthDays.innerHTML = days;
};

document.addEventListener("DOMContentLoaded", () => {
    renderCalendar();

    // Previous Month
    document.querySelector(".prev").addEventListener("click", () => {
        date.setMonth(date.getMonth() - 1);
        renderCalendar();
    });

    // Next month
    document.querySelector(".next").addEventListener("click", () => {
        const currentMonth = date.getMonth();
        const DECEMBER = 11;
        const JANUARY = 0;

        if (currentMonth === DECEMBER) {
            date.setFullYear(date.getFullYear() + 1); // set next year
            date.setMonth(JANUARY); // set January
        } else {
            date.setMonth(currentMonth + 1); // set next month
        }
        renderCalendar();
    });

    // Event Modal
    const modal = document.getElementById("event-modal");
    const modalEventDate = document.getElementById("event-date");

    document.querySelector(".days").addEventListener("click", (event) => {
        const selectedDay = event.target;

        // Prevent triggering if user clicks on empty spaces or non-day elements.
        if (selectedDay.classList.contains("prev-date") || selectedDay.classList.contains("next-date") || selectedDay.textContent.trim() === "") {
            return;
        }

        // Get the clicked day and construct the date
        const clickedDay = event.target.textContent.trim();
        const clickedDate = new Date(date.getFullYear(), date.getMonth(), +clickedDay);

        // Update the modal with the clicked date and event info
        modalEventDate.value = clickedDate.toDateString();

        // Show the modal
        modal.style.display = "block";

        // Handle closing the modal
        document.getElementById("close-modal").addEventListener("click", () => {
            modal.style.display = "none";
        });

        // Handle saving an event
        document.getElementById("save-event").addEventListener("click", () => {
            const eventName = document.getElementById("event-name").value;
            if (eventName === "") {
                alert("Event name cannot be empty!");
                return;
            }
            alert(`Event "${eventName}" successfully added for ${clickedDate.toDateString()}!`);
            modal.style.display = "none";
        });
    });

    // Object to store events for multiple dates
    const events = {};

    // Example: Adding or updating an event
    document.getElementById("save-event").addEventListener("click", () => {
        const eventName = document.getElementById("event-name").value;
        const eventDescription = document.getElementById("event-description").value;
        const eventDate = modalEventDate.value; // Get the selected date

        if (eventName === "") {
            alert("Event name cannot be empty!");
            return;
        }

        // Save the event to the `events` object
        events[eventDate] = {name: eventName, description: eventDescription};

        alert(`Event "${eventName}" added for ${eventDate}!`);
        modal.style.display = "none";

        console.log(events); // Debugging: Check the stored events
    });

    document.querySelector(".days").addEventListener("click", (event) => {
        const selectedDay = event.target;

        if (selectedDay.classList.contains("prev-date") || selectedDay.classList.contains("next-date") || selectedDay.textContent.trim() === "") {
            return;
        }

        // Remove "selected" class from all previously selected days
        const allDays = document.querySelectorAll(".days div");
        allDays.forEach((day) => day.classList.remove("selected"));

        // Add "selected" class to the clicked day
        selectedDay.classList.add("selected");
    });

    const selectedDates = new Set();

    document.querySelector(".days").addEventListener("click", (event) => {
        const selectedDay = event.target;

        if (selectedDay.classList.contains("prev-date") || selectedDay.classList.contains("next-date") || selectedDay.textContent.trim() === "") {
            return;
        }

        const clickedDay = selectedDay.textContent.trim();
        const clickedDate = new Date(date.getFullYear(), date.getMonth(), +clickedDay).toDateString();

        // Toggle selection
        if (selectedDates.has(clickedDate)) {
            selectedDates.delete(clickedDate); // Unselect date
            selectedDay.classList.remove("selected");
        } else {
            selectedDates.add(clickedDate); // Select date
            selectedDay.classList.add("selected");
        }

        console.log(selectedDates); // Debugging: Check the selected dates
    });

    // Handle saving event when save button is clicked
    document.getElementById("save-event").addEventListener("click", () => {
        const eventName = document.getElementById("event-name").value;
        const eventDate = document.getElementById("event-date").value;
        const eventDescription = document.getElementById("event-description").value;

        if (eventName === "") {
            alert("Event name cannot be empty!");
            return;
        }
        // Save the event to the `events` object
        selectedDates.forEach((eventDate) => {
            events[eventDate] = {name: eventName, description: eventDescription};
        });

        //Append the event to the #events-list list
        const eventsList = document.getElementById("event-list");

        selectedDates.forEach((eventDate) => {
             // Create a list item
            document.getElementsByTagName("li").appendChild(document.createTextNode(`${eventDate}: ${eventName}`));
        });
    });
});