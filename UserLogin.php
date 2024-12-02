<?php
session_start();
echo "<link rel='stylesheet' type='text/css' href='LoginStyle.css'>";

// Define constants
define('LOGIN_URL', 'Login.html');
define('HOME_PAGE', 'HomePage.html');
define('INCORRECT_MSG', "
<body>
    <main>
        <h1 id='wrongInfo'>Incorrect Username or Password</h1>
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
        <a href='CreateAccount.html' id='registerLink'>Create Account</a>
        </form>
    </main>
</body>");

$mysqli = mysqli_connect('localhost', 'root', 'letmein', 'Primate_Planner');

if (isset($_POST['submit'])) {
    $email = filter_input(INPUT_POST, 'username', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');

    if ($email && $password) {
        if (is_valid_user($mysqli, $email, $password)) {
            setcookie('auth', session_id(), time() + 60 * 30, '/', '', 0);
            header("Location: " . HOME_PAGE);
            exit;
        } else {
            echo INCORRECT_MSG;
            exit;
        }
    } else {
        header("Location: " . LOGIN_URL);
        exit;
    }
}

function is_valid_user($mysqli, $email, $password) {
    $email = strtolower($email);
    $query = 'SELECT email FROM Members WHERE LOWER(email) = ? AND password = SHA1(?)';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    return ($result->num_rows == 1);
}
?>