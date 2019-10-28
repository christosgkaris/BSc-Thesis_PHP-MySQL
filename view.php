
<?php
include('config.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>View timetable</title>
        <meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="stylesheet.css"/>
    </head>
    <body>
        <div id="uploadpage">
            <h3>Select timetable to view</h3>
            
            <form action="viewtimetable.php" method="get" enctype="multipart/form-data">
                <?php
                $sql = "SELECT Name FROM timetable WHERE Viewable=1 ORDER BY idTimetable DESC;";
                $result = mysqli_query($db, $sql);

                echo "<select name='Name'>";
                while ($row = mysqli_fetch_array($result)) {
                    echo "<option value='" . $row['Name'] . "'>" . $row['Name'] . "</option>";
                }
                echo "</select>";
                ?>
                <input type="submit" value="View timetable" name="submit">
            </form>
              
        </div>
    </body>
</html>