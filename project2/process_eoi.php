<?php
require_once("settings.php"); // Load DB connection settings

// Block direct URL access
if (!isset($_POST['givenname'])) {
    header("location: apply.php");
    exit();
}

// Connect to database
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<p>Database connection failure</p>");
}

// Create table if it doesn’t exist
$table = "eoi";
$create_table_sql = "
CREATE TABLE IF NOT EXISTS $table (
    EOInumber INT AUTO_INCREMENT PRIMARY KEY,
    job_ref VARCHAR(5),
    givenname VARCHAR(20),
    familyname VARCHAR(20),
    dob DATE,
    gender VARCHAR(10),
    street VARCHAR(40),
    suburb VARCHAR(40),
    state VARCHAR(3),
    postcode VARCHAR(4),
    email VARCHAR(50),
    phone VARCHAR(15),
    skills TEXT,
    other_skills TEXT,
    status ENUM('New','Current','Final') DEFAULT 'New',
    date_applied DATETIME DEFAULT CURRENT_TIMESTAMP
);
";
mysqli_query($conn, $create_table_sql);


function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Collect and clean inputs
$job_ref = clean_input($_POST["ReferenceID"] ?? "");
$givenname = clean_input($_POST["givenname"] ?? "");
$familyname = clean_input($_POST["familyname"] ?? "");
$dob = clean_input($_POST["date"] ?? "");
$gender = clean_input($_POST["gender"] ?? "");
$street = clean_input($_POST["street"] ?? "");
$suburb = clean_input($_POST["suburb"] ?? "");
$state = clean_input($_POST["state"] ?? "");
$postcode = clean_input($_POST["postcode"] ?? "");
$email = clean_input($_POST["email"] ?? "");
$phone = clean_input($_POST["phone"] ?? "");
$skills = isset($_POST["skills"]) ? implode(", ", (array)$_POST["skills"]) : "";
$other_skills = clean_input($_POST["other_skills"] ?? "");

//  Server-side validation
$errors = [];

// Required fields
if (empty($job_ref) || empty($givenname) || empty($familyname) || empty($email)) {
    $errors[] = "All required fields must be filled out.";
}

// Format checks
if (!preg_match("/^[A-Za-z0-9]{5}$/", $job_ref)) $errors[] = "Invalid job reference (must be 5 alphanumeric characters).";
if (!preg_match("/^[A-Za-z]+$/", $givenname)) $errors[] = "Invalid given name (letters only).";
if (!preg_match("/^[A-Za-z]+$/", $familyname)) $errors[] = "Invalid family name (letters only).";
if (!preg_match("/^[0-9]{4}$/", $postcode)) $errors[] = "Invalid postcode (4 digits).";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $dob)) $errors[] = "Invalid date format (use YYYY-MM-DD).";
if (!preg_match("/^[0-9]{8,12}$/", $phone)) $errors[] = "Invalid phone number (8–12 digits).";
$valid_states = ["VIC", "NSW", "QLD", "NT", "WA", "SA", "TAS", "ACT"];
if (!in_array($state, $valid_states)) $errors[] = "Invalid Australian state.";

// If validation fails, show errors
if (!empty($errors)) {
    echo "<h2>Form submission error:</h2><ul>";
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul><p><a href='apply.php'>Go back</a></p>";
    mysqli_close($conn);
    exit();
}

// Insert using prepared statement
$stmt = mysqli_prepare($conn, "INSERT INTO $table 
(job_ref, givenname, familyname, dob, gender, street, suburb, state, postcode, email, phone, skills, other_skills)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

mysqli_stmt_bind_param(
    $stmt,
    "sssssssssssss",
    $job_ref,
    $givenname,
    $familyname,
    $dob,
    $gender,
    $street,
    $suburb,
    $state,
    $postcode,
    $email,
    $phone,
    $skills,
    $other_skills
);


if (mysqli_stmt_execute($stmt)) {
    $eoi_id = mysqli_insert_id($conn);
    echo "<h2>Application submitted successfully!</h2>";
    echo "<p>Your Expression of Interest (EOI) number is <strong>" . htmlspecialchars($eoi_id) . "</strong>.</p>";
    echo "<p>Status: <strong>New</strong></p>";
    echo "<p><a href='apply.php'>Submit another application</a></p>";
} else {
    echo "<p>Error submitting your application: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
