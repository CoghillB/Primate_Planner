<?php
session_start();

// Check for required fields from the form
if (isset($_POST['submit']) && (!filter_input(INPUT_POST, 'username') || !filter_input(INPUT_POST, 'password'))) {
    header("Location: login.html");
    exit;
}

// Connect to server and select database
$mysqli = mysqli_connect("localhost", "root", "letmein", "Primate_Planner");

// Handle login
if (isset($_POST['submit'])) {
    $targetemail = filter_input(INPUT_POST, 'username', FILTER_VALIDATE_EMAIL);
    $targetpasswd = filter_input(INPUT_POST, 'password');

    if ($targetemail && $targetpasswd) {
        $stmt = $mysqli->prepare("SELECT email FROM Members WHERE LOWER(email) = ? AND password = SHA1(?)");
        $targetemail = strtolower($targetemail);
        $stmt->bind_param("ss", $targetemail, $targetpasswd);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            setcookie("auth", session_id(), time() + 60 * 30, "/", "", 0);
            header("Location: HomePage.html");
            exit;
        } else {
            header("Location: login.html");
            exit;
        }
    } else {
        header("Location: login.html");
        exit;
    }
}
?>






















<?php
//$servername = 'localhost';
//$username = 'root';
//$password = 'letmein';
//$dbname = 'Primate_Planner';
//
////Connect to DB
//$conn = new mysqli($servername, $username, $password, $dbname);
//
////Check connection
//if($conn->connect_error){
//    die("Connection failed: " . $conn->connect_error);
//}
//
////Retrieve form data
//$user = $_POST['username'];
//$pass = $_POST['password'];
//
////Query to verify the user
//$sql = "SELECT * FROM Members WHERE Username = ? AND Password = ?";
//$stmt = $conn->prepare($sql);
//$stmt->bind_param("ss", $user, $pass);
//$stmt->execute();
//$result = $stmt->get_result();
//
////Check if a matching user exists
//if($result->num_rows > 0) {
//    header("Location: HomePage.html");
//    exit();
//} else {
//    //If no matching user is found, redirect to the login page
//    header("Location: Login.html");
//}
//
//$stmt->close();
//$conn->close();
//?>