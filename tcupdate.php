
<?php

    include('lock.php');
    
    $timetableid = $_POST["timetableid"];
    $data0 = $_POST["data0"];
    $data1 = $_POST["data1"];
    $data2 = $_POST["data2"];
    $data3 = $_POST["data3"];
         
    echo $timetableid;
    echo $data0;
    echo $data1;
    echo $data2;
    echo $data3;    
    
    $sql = "UPDATE timeclassroom SET Time='$data2', Classroom='$data3' WHERE Subject_Timetable_idTimetable='$timetableid' AND Time='$data0' AND Classroom='$data1';";
    $result = mysqli_query($db, $sql);
    
?>