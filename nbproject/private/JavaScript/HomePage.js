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
    const modalEventTitle = document.getElementById("event-name");
    const modalEventDescription = document.getElementById("event-description");

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

    });

    // Append the event to <ul> element with id "events-list" using the input from user in the form with id "event-block" when the user clicks the "save-event" button.

    const saveEventButton = document.getElementById("save-event");

    const eventList = document.getElementById("events-list");

    document.getElementById("event-block");
    saveEventButton.addEventListener("click", () => {
        event.preventDefault();

        const eventName = modalEventTitle.value.trim();
        const eventDescription = modalEventDescription.value.trim();
        const eventDate = modalEventDate.value.trim();

        // Validate input fields
        if (!eventName || !eventDescription || !eventDate) {
            alert("Please fill in all fields before saving the event.");
            return;
        }

        // Create an event list item
        const eventItem = document.createElement("li");
        eventItem.innerHTML = `
            <strong>${eventDate}:</strong> ${eventName} - ${eventDescription}
        `;

        // Append the new event to the event list
        eventList.appendChild(eventItem);

        // Clear the form fields
        modalEventTitle.value = "";
        modalEventDescription.value = "";
        modalEventDate.value = "";

        // Delete last added event


        // Close the modal
        modal.style.display = "none";

        console.log(eventList); // Should not be null

    });

    const deleteEventButton = document.getElementById("delete-event");
    deleteEventButton.addEventListener("click", () => {

        if (eventList.lastElementChild) {
            // Remove the last `<li>` from the DOM
            eventList.removeChild(eventList.lastElementChild);
        } else {
            alert("No events to delete!");
        }
    });

    // Logout button
    const logoutButton = document.getElementById("logout-button");

    logoutButton.addEventListener("click", () => {
        // Make a request to the Logout.php script to destroy the server session
        fetch('../PHP/Logout.php', {
            method: 'GET', // Use GET to call the script
            credentials: 'include' // Ensure cookies are included in the request
        })
            .then((response) => {
                if (response.ok) {
                    // Clear client-side session storage for extra security
                    window.sessionStorage.clear();

                    // Redirect the user to the login page after the session is destroyed
                    window.location.href = "../PHP/UserLogin.php";
                } else {
                    // Handle any errors if the logout request fails
                    console.error("Logout failed. Please try again.");
                }
            })
            .catch((error) => {
                // Log any unexpected errors
                console.error("Error during the logout process:", error);
            });
    });
});
