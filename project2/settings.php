<!-- settings for database connection -->
<?php
$host = "localhost";
$user = "root";
$pwd = "";
$sql_db = "sdlrc_db";

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Unable to connect to database, connection failure: " . mysqli_connect_error() . "</p>");
}
?>