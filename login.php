
<!--
http://www.9lessons.info/2009/09/php-login-page-example.html
https://www.freshdesignweb.com/css-login-form-templates/
-->

<?php
include('config.php');
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $my_email = mysqli_real_escape_string($db, $_POST['Email']);
    $my_password = mysqli_real_escape_string($db, $_POST['Password']);
    $my_password = md5($my_password);

    $sql = "SELECT idAdmin FROM admin WHERE Email='$my_email' AND Password='$my_password';";
    $result = mysqli_query($db, $sql);
    $count = mysqli_num_rows($result);

    if ($count == 1) {
        $_SESSION['login_user'] = $my_email;
        header("location: main.php");
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="login.css"/>
        <title>Login</title>
    </head>
    <body>
        <div class="login-card">
            <h1>Welcome to the</h1>
            <h1>Timetable Editor</h1>
            <br />
            <form action="" method="post">
                <input type="text" name="Email" placeholder="Email">
                <input type="password" name="Password" placeholder="Password">
                <input type="submit" class="login login-submit" value="Login">
            </form>
        </div>
    </body>
</html>