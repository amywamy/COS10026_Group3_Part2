<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$status = 'New';

require_once("settings.php"); // Load DB connection settings

// Block direct URL access
if (!isset($_POST['given_name'])) {
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
    job_code VARCHAR(5),
    given_name VARCHAR(20),
    family_name VARCHAR(20),
    date_of_birth DATE,
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
$job_code = clean_input($_POST["ReferenceID"] ?? "");
$givenname = clean_input($_POST["given_name"] ?? "");
$familyname = clean_input($_POST["family_name"] ?? "");
$date_of_birth = clean_input($_POST["date"] ?? "");
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
if (empty($job_code) || empty($givenname) || empty($familyname) || empty($email)) {
    $errors[] = "All required fields must be filled out.";
}

// Format checks
if (!preg_match("/^[A-Za-z0-9]{5}$/", $job_code)) $errors[] = "Invalid job reference (must be 5 alphanumeric characters).";
if (!preg_match("/^[A-Za-z]+$/", $givenname)) $errors[] = "Invalid given name (letters only).";
if (!preg_match("/^[A-Za-z]+$/", $familyname)) $errors[] = "Invalid family name (letters only).";
if (!preg_match("/^[0-9]{4}$/", $postcode)) $errors[] = "Invalid postcode (4 digits).";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date_of_birth)) $errors[] = "Invalid date format (use YYYY-MM-DD).";
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
(job_code, given_name, family_name, date_of_birth, gender, street_address, suburb, state, postcode, email, phone_number, skills, status)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "sssssssssssss", 
    $job_code, 
    $givenname, 
    $familyname, 
    $date_of_birth, 
    $gender, 
    $street, 
    $suburb, 
    $state, 
    $postcode, 
    $email, 
    $phone, 
    $skills, 
    $status
);


if (mysqli_stmt_execute($stmt)) {
    $eoi_id = mysqli_insert_id($conn);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Application Submitted</title>
        <style>
            body {
                font-family: "Poppins", sans-serif;
                background-color: #f7f9fc;
                color: #333;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }

            .success-card {
                background: #fff;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
                border-radius: 16px;
                padding: 40px;
                text-align: center;
                max-width: 500px;
                width: 90%;
            }

            .success-card h2 {
                color: #2b6cb0;
                font-size: 1.8rem;
                margin-bottom: 10px;
            }

            .success-card p {
                font-size: 1rem;
                margin: 10px 0;
                color: #555;
            }

            .success-card strong {
                color: #1a365d;
            }

            .btn {
                display: inline-block;
                background-color: #2b6cb0;
                color: #fff;
                padding: 10px 20px;
                border-radius: 8px;
                text-decoration: none;
                margin-top: 20px;
                transition: 0.3s ease;
            }

            .btn:hover {
                background-color: #1a4f8b;
            }
        </style>
    </head>
    <body>
        <div class="success-card">
            <h2>Application Submitted Successfully!</h2>
            <p>Your Expression of Interest (EOI) number is <strong><?= htmlspecialchars($eoi_id) ?></strong>.</p>
            <p>Status: <strong>New</strong></p>
            <a href="apply.php" class="btn">Submit Another Application</a>
        </div>
    </body>
    </html>
    <?php
}
