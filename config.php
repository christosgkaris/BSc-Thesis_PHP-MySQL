
<?php

// Define variables
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'manttdb');

// Create connection
$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Greek characters in database
mysqli_set_charset($db, "utf8");

// Check connection
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

?>