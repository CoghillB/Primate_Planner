<?php
// connect to DB
$username = "root";
$password = "letmein";
$hostname = "localhost";
$dbname = "Primate_Planner";

// connection to the database
$dbhandle = new mysqli($hostname, $username, $password, $dbname);

// check connection
if ($dbhandle->connect_error) {
    die("Connection failed: " . $dbhandle->connect_error);
}

// verify the user
$myusername = $_POST['myusername'];
$mypassword = $_POST['mypassword'];

// prepare and bind
$stmt = $dbhandle->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $myusername, $mypassword);

// execute the query
$stmt->execute();
$result = $stmt->get_result();

// check if the user exists
if ($result->num_rows > 0) {
    // valid user, redirect to calendar.html
    header("Location: calendar.html");
    exit();
} else {
    // invalid user, prompt to create an account
    echo "Invalid username or password. <a href='CreateAccount.html'>Create an account</a>";
}

// close the statement and connection
$stmt->close();
$dbhandle->close();
?>