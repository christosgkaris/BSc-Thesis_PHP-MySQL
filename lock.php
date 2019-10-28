
<?php

include('config.php');

session_start();

$user_check = $_SESSION['login_user'];

$sql = "SELECT Email FROM admin WHERE Email='$user_check';";

$ses_sql = mysqli_query($db, $sql);

$row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);

$login_session = $row['Email'];

if (!isset($login_session)) {
    header("Location: login.php");
}

?>