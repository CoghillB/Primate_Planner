<?php
echo "<link rel='stylesheet' type='text/css' href='../CSS/LoginStyle.css'>";
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
//         Redirect to login page upon successful insertion
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
        <a href='../HTML/CreateAccount.html' id='registerLink' name='createAccount'>Create Account</a>
    </form>
</main>
</body>";
        echo $displayBlock;
        exit();
    } else {
        // redirect user to Login.html because email is already in use
        $errorBlock = "
        <body>
<main>
    <h1 id='pageTitle'>Email already in use. Please Log in.</h1>
    <form method='post' action='UserLogin.php' id='loginForm' autocomplete='off'>
        <h3>Login</h3>
        <label for='username'>Username:</label><br>
        <input type='email' id='username' name='username' placeholder='Enter your email' required autocomplete='off'>
        <br>
        <label for='password'>Password:</label><br>
        <input type='password' id='password' name='password' placeholder='Enter your password' required autocomplete='new-password'>
        <br>
        <button type='submit' name='submit'>Sign In</button>
        <br>
        <a href='../HTML/CreateAccount.html' id='registerLink'>Create Account</a>
    </form>
</main>
</body>";
        echo $errorBlock;
    }

    $stmt->close();
} else {
    $errorBlock = "
        <body>
<main>
    <div class='container'>
        <!-- Page Title -->
        <div class='row'>
            <div class='col'>
                <h1 id='pageTitle'>Email already in use. Please Log in.</h1>
            </div>
        </div>
        <div class='row'>
            <div class='col text - center col - 12 mx - 7'>
            <form action='CreateAccount.php' method='post' id='createAccountForm'>
                <h3>Create an Account</h3>
                <label for='fname'> First Name: </label><br>
                <input type='text' id='fname' name='fname' required><br><br>

                <label for='lname'> Last Name: </label><br>
                <input type='text' id='lname' name='lname' required><br><br>

                <label for='password' required> Password: </label><br>
                <input type='password' id='password' name='password'><br><br>

                <label for='email' required> Email: </label><br>
                <input type='email' id='email' name='email' required><br><br>

                <button type='submit'>Create Account</button>
            </form>
            </div>
        </div>
    </div>
</main>

<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js'>
</script >
</body >
        ";
    echo $errorBlock;
}

$conn->close();
?>