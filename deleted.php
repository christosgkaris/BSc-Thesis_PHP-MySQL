
<?php
include('menu.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <link type="text/css" rel="stylesheet" href="stylesheet.css"/>
        <title>Deleted timetable</title>
    </head>
    <body>
        <?php
        if(isset($_POST['submit'])) {
            $selected_val = $_POST['Name'];
        }
        echo "<br /><br /><center>You have selected: " . $selected_val . " to be deleted.</center>";
        
        $result00 = mysqli_query($db, "SELECT idTimetable FROM timetable WHERE Name='$selected_val'");
        $id = mysqli_fetch_row($result00)[0];
        
        $result01 = mysqli_query($db, "DELETE FROM cgroup_has_subject WHERE Subject_Timetable_idTimetable='$id';");
        $result02 = mysqli_query($db, "DELETE FROM dgroup_has_subject WHERE Subject_Timetable_idTimetable='$id';");
        $result03 = mysqli_query($db, "DELETE FROM cgroup WHERE Timetable_idTimetable='$id';");
        $result04 = mysqli_query($db, "DELETE FROM dgroup WHERE Timetable_idTimetable='$id';");
        $result05 = mysqli_query($db, "DELETE FROM equal WHERE Subject_Timetable_idTimetable='$id';");
        $result06 = mysqli_query($db, "DELETE FROM timeclassroom WHERE Subject_Timetable_idTimetable='$id';");
        $result07 = mysqli_query($db, "DELETE FROM subjectrestrictions WHERE Subject_Timetable_idTimetable='$id';");
        $result08 = mysqli_query($db, "DELETE FROM subject_has_classroom_preference WHERE Subject_Timetable_idTimetable='$id';");
        $result09 = mysqli_query($db, "DELETE FROM subject_has_teacher WHERE Subject_Timetable_idTimetable='$id';");
        $result10 = mysqli_query($db, "DELETE FROM classroomrestrictions WHERE Classroom_Timetable_idTimetable='$id';");
        $result11 = mysqli_query($db, "DELETE FROM teacherrestrictions WHERE Teacher_Timetable_idTimetable='$id';");
        $result12 = mysqli_query($db, "DELETE FROM classroom WHERE Timetable_idTimetable='$id';");
        $result13 = mysqli_query($db, "DELETE FROM teacher WHERE Timetable_idTimetable='$id';");
        $result14 = mysqli_query($db, "DELETE FROM dayname WHERE Timetable_idTimetable='$id';");
        $result15 = mysqli_query($db, "DELETE FROM hourname WHERE Timetable_idTimetable='$id';");
        $result16 = mysqli_query($db, "DELETE FROM subject WHERE Timetable_idTimetable='$id';");
        $result17 = mysqli_query($db, "DELETE FROM colorgroup WHERE Timetable_idTimetable='$id';");
        $result18 = mysqli_query($db, "DELETE FROM printgroup WHERE Timetable_idTimetable='$id';");
        $result19 = mysqli_query($db, "DELETE FROM timetable WHERE idTimetable='$id';");
        
        echo "<br /><center>Deleted Successfully.</center><br />";  
        unset($_SESSION['selected_timetable']);
        unset($_SESSION['id_timetable']);
        ?>
    </body>
</html>