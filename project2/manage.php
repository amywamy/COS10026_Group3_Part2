<?php
session_start();
if(!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

require_once('settings.php');
$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (isset($_GET['action']) && $_GET['action'] == 'list_all') {
    $sql = "SELECT * FROM eoi ORDER BY EOInumber DESC";
    $result = mysqli_query($conn, $sql);
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'search_job') {
    $job_ref = sanitize_input($_GET['job_reference']);
    $sql = "SELECT * FROM eoi WHERE job_reference = '$job_ref'";
}

if (isset($_GET['action']) && $_GET['action'] == 'search_name') {
    $name = sanitize_input($_GET['name']);
    $sql = "SELECT * FROM eoi WHERE first_name LIKE '%$name%' OR last_name LIKE '%$name%'";
}

if (isset($_GET['action']) && $_GET['action'] == 'delete_job') {
    $job_ref = sanitize_input($_GET['job_reference']);
    $sql = "DELETE * FROM eoi WHERE job_reference = '$job_ref'";
    mysqli_query($conn, $sql);
}

if (isset($_GET['action']) && $_GET['action'] == 'update_status') {
    $eonumber = $_GET['eoi_number'];
    $new_status = $_GET['status'];
    $sql = "UPDATE eoi SET status = '$new_status' WHERE EOInumber = '$eonumber'";
    mysqli_query($conn, $sql);
}
?>

