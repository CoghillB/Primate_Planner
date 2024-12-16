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

// Save the event
        document.getElementById("save-event").addEventListener("click", () => {
            const eventDetailsField = document.getElementById("event-description");
            const eventDetails = eventDetailsField.value.trim();
            const eventName = document.getElementById("event-name").value;

            if (!eventDetails) {
                alert("Event details cannot be empty!");
                return;
            }

            const selectedDate = modalEventDate.value; // The date already present in the modal
            const eventData = {
                date: selectedDate,
                name: eventName,
                details: eventDetails,
            };

            // Save the event in local storage
            const storedEvents = JSON.parse(localStorage.getItem("events")) || [];
            storedEvents.push(eventData);
            localStorage.setItem("events", JSON.stringify(storedEvents));

            // Close modal
            modal.style.display = "none";
            eventDetailsField.value = ""; // Clear the event input field

            console.log("Event Saved: ", eventData);


            //debug
            console.log("Event Saved: ", eventData);
            // display all events that are saved in console
            const allEvents = JSON.parse(localStorage.getItem("events"));
            console.log("All Events: ", allEvents);
        });

        // Display the event in <ul id="events-list"> on the page as a list item
        const eventsList = JSON.parse(localStorage.getItem("events")) || [];
        const eventName = document.getElementById("event-name").value;
        const eventDate = modalEventDate.value;
        const eventDescription = document.getElementById("event-description").value;
        const eventItem = document.createElement("li");
        eventItem.innerHTML = `<strong>${eventName}</strong> - ${eventDate} - ${eventDescription}`;
        document.getElementById("events-list").appendChild(eventItem);

    });
});