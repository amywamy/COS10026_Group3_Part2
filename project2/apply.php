<?php 
require_once "settings.php";
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<p>Database connection failed: " . mysqli_connect_error() . "</p>");
}
$jobs = [];
$query = "SELECT job_code, title FROM jobs ORDER BY job_code ASC";
$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $jobs[] = $row;
    }
}
$selected_job = isset($_GET['job']) ? htmlspecialchars($_GET['job']) : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="Job Application Form for Expression of Interest (EOI)" />
    <meta name="keywords" content="Job, Application, EOI, Form" />
    <meta name="author" content="Sarah Agate" />
    <title>Apply</title>
</head>
<body>

<?php include "header.inc"; ?>
<main id="apply-main" class="apply">
    <section class="apply-hero">
        <h1>Expression of Interest</h1>
        <p class="apply-intro">Complete the form below to submit your application for this exciting opportunity.</p>
    </section>
     <?php
    // Capture job from query string
    $selected_job = isset($_GET['job']) ? htmlspecialchars($_GET['job']) : "";
    ?>
    <form method="post" action="process_eoi.php" class="apply-form" novalidate>
        <!-- Reference Selection Section -->
        <fieldset>
            <legend>Position Applying For</legend>
            <label for="job_select">Select Job Position:</label>
            <select id="job_select" name="job_select" required>
                <option value="">-- Select a Position --</option>
                <?php
                foreach ($jobs as $job) {
                    $code = htmlspecialchars($job['job_code']);
                    $title = htmlspecialchars($job['title']);
                    $selected = ($selected_job == $code) ? 'selected' : '';
                    echo "<option value='$code' $selected>$code – $title</option>";
                }
                ?>
            </select>
        </fieldset>

        <!-- Reference Section -->
        <fieldset>
            <legend>Reference</legend>
            <label for="ReferenceID">Reference ID:</label>
            <input type="text" id="ReferenceID" name="ReferenceID" placeholder="5 alphanumeric characters">
        </fieldset>

        <!-- About You -->
        <fieldset>
            <legend>About You</legend>
            <label for="given_name">Given Name:</label>
            <input type="text" id="given_name" name="given_name" maxlength="20" placeholder="First Name">

            <label for="family_name">Family Name:</label>
            <input type="text" id="family_name" name="family_name" maxlength="20" placeholder="Last Name">

            <label for="date">Date of Birth:</label>
            <input type="date" id="date" name="date">
        </fieldset>

        <!-- Gender -->
        <fieldset>
            <legend>Gender</legend>
            <div class="radio-group">
                <label><input type="radio" id="male" name="gender" value="Male"> Male</label>
                <label><input type="radio" id="female" name="gender" value="Female"> Female</label>
                <label><input type="radio" id="other" name="gender" value="Other"> Other</label>
            </div>
        </fieldset>

        <!-- Address -->
        <fieldset>
            <legend>Address</legend>

            <label for="street">Street Address (max 40 chars):</label>
            <input type="text" id="street" name="street" maxlength="40">

            <label for="suburb">Suburb/Town (max 40 chars):</label>
            <input type="text" id="suburb" name="suburb" maxlength="40">

            <label for="state">State:</label>
            <select id="state" name="state">
                <option value="">-- Select State --</option>
                <option value="VIC">VIC</option>
                <option value="NSW">NSW</option>
                <option value="QLD">QLD</option>
                <option value="NT">NT</option>
                <option value="WA">WA</option>
                <option value="SA">SA</option>
                <option value="TAS">TAS</option>
                <option value="ACT">ACT</option>
            </select>

            <label for="postcode">Postcode (4 digits):</label>
            <input type="text" id="postcode" name="postcode" maxlength="4">
        </fieldset>

        <!-- Contact Details -->
        <fieldset>
            <legend>Contact Details</legend>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="example@email.com">

            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" placeholder="8–12 digits">
        </fieldset>

        <!-- Skills -->
        <fieldset>
            <legend>Skills</legend>
            <div class="checkbox-group">
                <label><input type="checkbox" name="skills[]" value="programming"> Programming</label>
                <label><input type="checkbox" name="skills[]" value="design"> Design</label>
                <label><input type="checkbox" name="skills[]" value="management"> Management</label>
                <label><input type="checkbox" id="other_skills_checkbox" name="skills[]" value="other"> Other skills...</label>
            </div>

            <div id="other_skills_container" class="hidden">
                <label for="other_skills_textarea">Please specify:</label>
                <textarea id="other_skills_textarea" name="other_skills"
                          placeholder="Enter other skills here..."></textarea>
            </div>
        </fieldset>

        <!-- Form Buttons -->
        <div class="form-buttons">
            <button type="submit" class="apply-btn">Submit Application</button>
            <button type="reset" class="reset-btn">Reset Form</button>
        </div>
    </form>
</main>

<?php include "footer.inc"; ?>

<script>
(function(){
    const otherCheckbox = document.getElementById('other_skills_checkbox');
    const otherContainer = document.getElementById('other_skills_container');
    function toggle() {
        otherContainer.classList.toggle('hidden', !otherCheckbox.checked);
    }
    otherCheckbox.addEventListener('change', toggle);
    toggle();
})();
</script>

</body>
</html>
