<?php
// Database connection details
$host = 'sysmysql8.auburn.edu';    // MySQL server hostname
$username = 'ods0005';                    // Your MySQL username
$password = '1';                    // Your MySQL password
$dbname = 'ods0005db';                      // Your database name

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>