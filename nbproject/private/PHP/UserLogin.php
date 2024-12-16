<?php
session_start();
echo "<link rel='stylesheet' type='text/css' href='../CSS/LoginStyle.css'>";

// Define constants
define('LOGIN_URL', 'Login.html');
define('HOME_PAGE', 'HomePage.php');
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
        <a href='../HTML/CreateAccount.html' id='registerLink'>Create Account</a>
        </form>
    </main>
</body>");

$mysqli = mysqli_connect('localhost', 'root', 'letmein', 'Primate_Planner');

if (isset($_POST['submit'])) {
    $email = filter_input(INPUT_POST, 'username', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');

    if ($email && $password) {
        $memberId = is_valid_user($mysqli, $email, $password);
        if ($memberId) {
            // Store member ID in the session
            $_SESSION['member_id'] = $memberId;

            // Redirect to the home page
            header("Location: " . HOME_PAGE);
            exit;
        } else {
            echo INCORRECT_MSG;
            exit;
        }
    } else {
        // Redirect to login page if input is invalid
        header("Location: " . LOGIN_URL);
        exit;
    }
}

function is_valid_user($mysqli, $email, $password)
{
    $email = strtolower($email);
    $query = 'SELECT id, password FROM Members WHERE LOWER(email) = ?';
    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        return false; // Handle statement preparation error
    }

    $stmt->bind_param('s', $email);
    if (!$stmt->execute()) {
        $stmt->close();
        return false; // Handle execution error
    }

    $result = $stmt->get_result();
    if ($result->num_rows != 1) {
        $stmt->close();
        return false; // User not found or duplicate email
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    // Verify the password
    if (password_verify($password, $user['password'])) {
        return $user['id']; // Return id if password is valid
    }

    return false; // Password mismatch
}
?>
