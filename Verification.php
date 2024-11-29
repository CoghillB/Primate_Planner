<?php
$username = 'root';
$password = 'letmein';
$hostname = 'localhost';
$dbname = 'Primate_Planner';
$dbhandle = new mysqli($hostname, $username, $password, $dbname);
if ($dbhandle->connect_error) {
die('Connection failed: ' . $dbhandle->connect_error);
}

// Get user input

// Get user input
$user_input = $_GET['user_input'] ?? '';

// Sanitize user input
$sanitized_input = htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');

// Query the database with user input
$query = "SELECT * FROM table_name WHERE column_name LIKE ?";
$stmt = $dbhandle->prepare($query);
$search_term = '%' . $sanitized_input . '%';
$stmt->bind_param('s', $search_term);

// Execute the statement
$stmt->execute();

// Fetch results
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
echo 'Result: ' . htmlspecialchars($row['result_column'], ENT_QUOTES, 'UTF-8') . '<br>';
}

// Close statement and connection
$stmt->close();
$dbhandle->close();

?>