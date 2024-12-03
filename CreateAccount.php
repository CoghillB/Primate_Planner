<?php
echo "<link rel='stylesheet' type='text/css' href='LoginStyle.css'>";
// Retrieve form data with basic sanitization
$fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
$lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

// Connect to the database
$servername = 'localhost';
$username = 'root';
$dbPassword = 'letmein';
$dbname = 'Primate_Planner';

$conn = new mysqli($servername, $username, $dbPassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Hash the password securely using BCRYPT
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Prepare and execute the SQL statement
$query = 'INSERT INTO Members (fname, lname, email, password) VALUES (?, ?, ?, ?)';
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param('ssss', $fname, $lname, $email, $hashedPassword);

    if ($stmt->execute()) {
        // Redirect to login page upon successful insertion
        $displayBlock = "
<body>
<main>
    <!-- Page Title -->
    <h1 id='pageTitle'>Account Created Successfully!</h1>

    <!-- Login form with option to create account -->
    <form method='post' action='UserLogin.php' id='loginForm'>
        <h3>Login</h3>
        <label for='username'>Username:</label><br>
        <input type='email' id='username' name='username' placeholder='Enter your email' required>
        <br>
        <label for='password'>Password:</label><br>
        <input type='password' id='password' name='password' placeholder='Enter your password' required>
        <br>
        <button type='submit' name='submit'>Sign In</button>
        <br>
        <a href='CreateAccount.html' id='registerLink' name='createAccount'>Create Account</a>
    </form>
</main>
</body>";
        echo $displayBlock;
        exit();
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
} else {
    echo 'Error preparing statement: ' . $conn->error;
}

$conn->close();
?>













































<?php
//session_start();
//echo '<link rel='stylesheet' type='text/css' href='LoginStyle.css'>';
//
//define('HOME_PAGE', 'HomePage.html');
//
//// Check if connection and statement objects are defined and prepared properly
//if (isset($stmt) && $stmt->execute()) {
//    $displayBlock = '<body>
//<main>
//    <!-- Page Title -->
//    <h1 id='pageTitle'>Account Successfully Created!</h1>
//
//    <!-- Login form with option to create account -->
//    <form method='post' action='UserLogin.php' id='loginForm'>
//        <h3>Login</h3>
//        <label for='username'>Username:</label><br>
//        <input type='email' id='username' name='username' placeholder='Enter your email' required>
//        <br>
//        <label for='password'>Password:</label><br>
//        <input type='password' id='password' name='password' placeholder='Enter your password' required>
//        <br>
//        <button type='submit' name='submit'>Sign In</button>
//        <br>
//        <a href='CreateAccount.html' id='registerLink' name='createAccount'>Create Account</a>
//    </form>
//</main>
//</body>';
//    echo $displayBlock;
//} else {
//    // Ensure error handling for uninitialized or failed statement
//    echo 'Error: ' . (isset($stmt) ? $stmt->error : 'Statement not initialized');
//}
//
//// It's good practice to check if these objects are set before calling methods
//if (isset($stmt)) {
//    $stmt->close();
//}
//if (isset($conn)) {
//    $conn->close();
//}
//
//// Ensure database connection is valid before using it in functions
//function insert_user($conn, $fname, $lname, $email, $password) {
//    $query = 'INSERT INTO Members (fname, lname, email, password) VALUES (?, ?, ?, ?)';
//
//    // Hash the password securely
//    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
//
//    $stmt = $conn->prepare($query);
//    if ($stmt === false) {
//        // Handle error, e.g., by logging and returning false
//        error_log('prepare() failed: ' . htmlspecialchars($conn->error));
//        return false;
//    }
//
//    $stmt->bind_param('ssss', $fname, $lname, $email, $hashedPassword);
//
//    if ($stmt->execute()) {
//        $stmt->close();
//        return true;
//    } else {
//        // Handle error, e.g., by logging without too much exposure
//        error_log('execute() failed: ' . htmlspecialchars($stmt->error));
//        $stmt->close();
//        return false;
//    }
//}
//?>