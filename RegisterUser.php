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

// get user input
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$password = $_POST['password'];

// check if email already exists
$email_check_stmt = $dbhandle->prepare("SELECT * FROM users WHERE email = ?");
$email_check_stmt->bind_param("s", $email);
$email_check_stmt->execute();
$email_check_result = $email_check_stmt->get_result();

if ($email_check_result->num_rows > 0) {
    //pop up stating email is in use and to use a different one. Pop up should be on same screen
    echo "<script>alert('Email is already in use. Please use a different email.');</script>";
    $email_check_stmt->close();
    $dbhandle->close();
} else {
    // prepare and bind
    $stmt = $dbhandle->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fname, $lname, $email, $password);

    // execute the query
    if ($stmt->execute()) {
        echo "Account created successfully. <a href='Login.html'>Login here</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // close the statement
    $stmt->close();
}

// close the email check statement and connection
$email_check_stmt->close();
$dbhandle->close();
?>