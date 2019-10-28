
<?php

include('menu.php');

if(isset($_POST['save88_22'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $id = mysqli_real_escape_string($db, $_POST['sub_id']);
    $old = mysqli_real_escape_string($db, $_POST['old']);
    $hours_new = mysqli_real_escape_string($db, $_POST['n_hours']);
    $flag = 0;
    for($i=0;$i<strlen($hours_new);$i++){
        if(($hours_new[$i]=='0')||($hours_new[$i]=='1')||($hours_new[$i]=='2')||($hours_new[$i]=='3')||($hours_new[$i]=='4')||($hours_new[$i]=='5')||($hours_new[$i]=='6')||($hours_new[$i]=='7')||($hours_new[$i]=='8')||($hours_new[$i]=='9')||($hours_new[$i]=='+')){
            $flag = 1;
        }
        else{
            $flag=0;
            break;
        }
    }
    if((strlen($hours_new)>0)&&(strlen($hours_new)<20)&&($flag==1)){
        $sql = "UPDATE subject SET Hours='$hours_new' WHERE Timetable_idTimetable='$timetable_id' AND idSubject='$id';";
        $result = mysqli_query($db, $sql);
        if($result){
            header("location: configure.php");
        }
        else
        {
            echo "<br /><center>Change failed</center>";
            echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
        }
    }
    else
    {
        echo "<br /><center>Change failed, default value set</center>";
        $hours_new2 = "";
        $hours_new2 = $hours_new2 . "1";
        for($i=1;$i<strlen($old)-2;$i=$i+2){
            $hours_new2 = $hours_new2 . "+1";
        }
        $result = mysqli_query($db, "UPDATE subject SET Hours='$hours_new2' WHERE Timetable_idTimetable='$timetable_id' AND idSubject='$id';");
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}
?>