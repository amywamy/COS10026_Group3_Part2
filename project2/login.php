<?php
session_start();

require_once "settings.php";
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<p> Database connection failed: " . mysqli_connect_error() . "</p>");
}

function sanitize_input($data){
   return htmlspecialchars(stripslashes(trim($data)));
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = SHA2(?, 256)");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if(mysqli_num_rows($result) == 1){
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header("Location: manage.php");
        exit;
    } else {
        echo "Invalid username or password.";
    }
}
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="A simple login page for user authentication.">
        <meta name="keywords" content="login, username, password, authentication">
        <meta name="author" content="Nithya B">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Page</title>
        <link rel="stylesheet" href="../styles/style.css">
    </head>
    <body id="admin-login">
        <div class="login-box">
            <img src="../images/website_logo.png" alt="SDLRC Logo" class="logo">
        <h1>Login in</h1>
        <p class="subtitle">Admin Portal access</p>
        <form method ="POST" action="login.php">
            <div class="input-group">
            <label> Username:</label>
            <input type="text" name="username" required></div>
            <div class="input-group">
            <label> Password:</label>
            <input type="password" name="password">
            <button type="submit">Login</button></div>
        </form>
    </body>
</html>
   