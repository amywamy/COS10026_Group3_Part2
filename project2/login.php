
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    require_once "settings.php";
    $conn = mysqli_connect($host, $user, $pwd, $sql_db);

    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header("Location: manage.php");
    } else {
        echo "Invalid username or password.";
    }
}

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
    </head>
    <body>
        <h1>Login in</h1>
        <form method ="POST" action="login.php">
            <label> Username:</label>
            <input type="text" name="username" required>
            <label> Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </body>
</html>
   