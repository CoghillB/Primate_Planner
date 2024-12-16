<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['member_id'])) {
    // If not logged in, redirect to the login page
    header("Location: Login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Specifies the character encoding for the HTML document -->
    <meta charset="UTF-8">
    <!-- Sets the viewport to make the website responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title of the webpage -->
    <title>Home</title>
    <!-- Link to the external CSS file for styling the home page -->
    <link rel="stylesheet" href="../CSS/HomePageStyle.css">
    <!-- Link to the Font Awesome library for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<header>
    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark w-100 fixed-top">
        <!-- Logo of the website -->
        <p class="logo">Primate Planner</p>
        <!-- Button for toggling the navigation menu on small screens -->
        <button class="navbar-toggler ml-auto" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Links for "Home" and "Fitness" -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto ms-auto">
                <!-- Home link -->
                <li class="nav-item active">
                    <a class="nav-link" href="../HTML/HomePage.html">Home</a>
                </li>
                <!-- Fitness Tracker link -->
                <li class="nav-item">
                    <a class="nav-link" href="FitnessTracker.php">Fitness-Tracker</a>
                </li>
                <!-- Logout link -->
                <li class="nav-item">
                    <a class="nav-link" href="../PHP/Logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<main>
    <!-- Add a hidden modal popup to the HTML -->
    <div id="event-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2 id="title">Add Event</h2>
            <form id="event-block">
                <label for="event-name" class="label">Event Name:</label>
                <input type="text" id="event-name" placeholder="Enter event name"/>
                <br/>
                <label for="event-date">Date:</label>
                <input type="text" id="event-date" value="" disabled/>
                <br/>
                <label for="event-description">Description:</label><br/>
                <textarea id="event-description" placeholder="Enter event description"></textarea>
                <br/>
                <button id="save-event">Save</button>
                <button id="close-modal">Close</button>
            </form>
        </div>
    </div>

    <div class="container-fluid">
        <div class="calendar">
            <div class="month">
                <!-- Previous month button -->
                <i class="fas fa-angle-left prev"></i>
                <div class="date">
                    <!-- Current month and year -->
                    <h1></h1>
                    <p></p>
                </div>
                <div class="date">
                    <!-- Current month and year -->
                    <h1></h1>
                    <p></p>
                </div>
                <!-- Next month button -->
                <i class="fas fa-angle-right next"></i>
            </div>
            <!-- Weekdays header -->
            <div class="weekdays">
                <div>Sun</div>
                <div>Mon</div>
                <div>Tue</div>
                <div>Wed</div>
                <div>Thu</div>
                <div>Fri</div>
                <div>Sat</div>
            </div>
            <!-- Days of the month -->
            <div class="days">
                <div class="prev-date">4</div>
                <div class="prev-date">5</div>
                <div class="prev-date">6</div>
                <div class="prev-date">7</div>
                <div class="prev-date">8</div>
                <div class="prev-date">9</div>
                <div>1</div>
                <div>2</div>
                <div>3</div>
                <div>4</div>
                <div>5</div>
                <div class="current-day">6</div>
                <div>7</div>
                <div>8</div>
                <div>9</div>
                <div>10</div>
                <div>11</div>
                <div>12</div>
                <div>13</div>
                <div>14</div>
                <div>15</div>
                <div>16</div>
                <div>17</div>
                <div>18</div>
                <div>19</div>
                <div>20</div>
                <div>21</div>
                <div>22</div>
                <div>23</div>
                <div>24</div>
                <div>25</div>
                <div>26</div>
                <div>27</div>
                <div>28</div>
                <div>29</div>
                <div class="next-date">1</div>
                <div class="next-date">2</div>
                <div class="next-date">3</div>
                <div class="next-date">4</div>
                <div class="next-date">5</div>
            </div>
        </div>
    </div>

<!--  Display list of events that have been added to Calendar  -->
    <div class="container">
        <div class="row">
            <div class="col">
                <h2>Upcoming Events</h2>
                <ul id="events-list">
                    <!-- List of upcoming events -->
                </ul>
                <button id="delete-event">Delete</button>
            </div>
        </div>

    <!-- Link to the Bootstrap CSS library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <!-- Link to the external JavaScript file for the home page -->
    <script src="../JavaScript/HomePage.js"></script>
</main>
</body>

<!-- Footer -->
<div class="row" id="footer">
    <div class="row-12 d-flex justify-content-center">
        <!-- Footer text -->
        <p>2024 Primate Planner</p>
    </div>
    <div class="row-12 d-flex justify-content-center">
        <!-- Instagram icon -->
        <a>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                 class="bi bi-instagram" viewBox="0 0 16 16">
                <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
            </svg>
        </a>
        <!-- Twitter icon -->
        <a>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                 class="bi bi-twitter-x" viewBox="0 0 16 16">
                <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/>
            </svg>
        </a>
        <!-- Facebook icon -->
        <a>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                 class="bi bi-facebook" viewBox="0 0 16 16">
                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
            </svg>
        </a>
    </div>
</div>
</html>