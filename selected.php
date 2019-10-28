
<?php
include('menu.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <link type="text/css" rel="stylesheet" href="stylesheet.css"/>
        <title>Selected timetable</title>
    </head>
    <body>
        <?php
        if(isset($_POST['submit'])) {
            $selected_val = $_POST['Name'];
            $_SESSION['selected_timetable'] = $selected_val;
        }
        echo "<br /><br /><center>You have selected: " . $_SESSION['selected_timetable'] . "</center><br />";
        ?>
    </body>
</html>