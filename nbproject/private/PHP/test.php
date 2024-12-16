<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli('localhost','cs213user','letmein','fitnesstracker');
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
   
}else{
    echo "Database connected!<br>";
}
$sql = 'SHOW TABLES';
$result = $conn->query($sql);
if($result && $result-> num_rows > 0){
    echo "Tables in the database:<br>";
    while ($row = $result->fetch_row()) {
        echo "- " . $row[0] . "<br>";
    }
} else {
    echo "No tables found in the database or query failed.";
}


?>