
<?php
include('menu.php');

if(isset($_POST['submit0'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $viewable = mysqli_real_escape_string($db, $_POST['viewable']);
    if($viewable==0||$viewable==1){
        $sql = "UPDATE timetable SET Viewable='$viewable' WHERE idTimetable='$timetable_id';";
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
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}

if(isset($_POST['submit3'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $name_new = mysqli_real_escape_string($db, $_POST['name']);
    
    $result0 = mysqli_query($db, "SELECT Name FROM timetable;");
    $count_tn = mysqli_num_rows($result0);
    $flag=1;
    for($i=0;$i<$count_tn;$i++){
        if($name_new == mysqli_fetch_row($result0)[0]){
            $flag=0;
        }
    }
    
    if((strlen($name_new)>0)&&(strlen($name_new)<80)&&($flag)){
        $sql = "UPDATE timetable SET Name='$name_new' WHERE idTimetable='$timetable_id';";
        $result = mysqli_query($db, $sql);
        if($result){
            $_SESSION['selected_timetable'] = $name_new;
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
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}

if(isset($_POST['submit4'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $id = mysqli_real_escape_string($db, $_POST['dayname_id']);
    $name_new = mysqli_real_escape_string($db, $_POST['dayname_name']);
    $sql = "UPDATE dayname SET Name='$name_new' WHERE Timetable_idTimetable='$timetable_id' AND idDayname='$id';";
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

if(isset($_POST['delete4'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $id = mysqli_real_escape_string($db, $_POST['dayname_id']);
    $sql = "DELETE FROM dayname WHERE Timetable_idTimetable='$timetable_id' AND idDayname='$id';";
    $result = mysqli_query($db, $sql);
    if($result){
        $result0 = mysqli_query($db, "SELECT idDayname FROM dayname WHERE Timetable_idTimetable='$timetable_id';");
        $count_d = mysqli_num_rows($result0);
        $result00 = mysqli_query($db, "UPDATE timetable SET Days='$count_d' WHERE idTimetable='$timetable_id';");
        header("location: configure.php");
    }
    else
    {
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }    
}

if(isset($_POST['submit44'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $row0 = mysqli_fetch_row(mysqli_query($db, "SELECT idDayname FROM dayname WHERE Timetable_idTimetable='$timetable_id' ORDER BY idDayname DESC LIMIT 1;"));
    $id = $row0[0]+1;
    $name_new = mysqli_real_escape_string($db, $_POST['dayname_name']);
    $sql = "INSERT INTO dayname (idDayname, Timetable_idTimetable, Name) VALUES ($id, $timetable_id, '$name_new');";
    $result = mysqli_query($db, $sql);
    if($result){
        $result0 = mysqli_query($db, "SELECT idDayname FROM dayname WHERE Timetable_idTimetable='$timetable_id';");
        $count_d = mysqli_num_rows($result0);
        $result00 = mysqli_query($db, "UPDATE timetable SET Days='$count_d' WHERE idTimetable='$timetable_id';");
        header("location: configure.php");
    }
    else
    {
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }    
}

if(isset($_POST['submit5'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $id = mysqli_real_escape_string($db, $_POST['hourname_id']);
    $name_new = mysqli_real_escape_string($db, $_POST['hourname_name']);
    $sql = "UPDATE hourname SET Name='$name_new' WHERE Timetable_idTimetable='$timetable_id' AND idHourname='$id';";
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

if(isset($_POST['delete5'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $id = mysqli_real_escape_string($db, $_POST['hourname_id']);
    $sql = "DELETE FROM hourname WHERE Timetable_idTimetable='$timetable_id' AND idHourname='$id';";
    $result = mysqli_query($db, $sql);
    if($result){
        $result0 = mysqli_query($db, "SELECT idHourname FROM hourname WHERE Timetable_idTimetable='$timetable_id';");
        $count_h = mysqli_num_rows($result0);
        $result00 = mysqli_query($db, "UPDATE timetable SET Hours='$count_h' WHERE idTimetable='$timetable_id';");
        header("location: configure.php");
    }
    else
    {
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}

if(isset($_POST['submit55'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $row0 = mysqli_fetch_row(mysqli_query($db, "SELECT idHourname FROM hourname WHERE Timetable_idTimetable='$timetable_id' ORDER BY idHourname DESC LIMIT 1;"));
    $id = $row0[0]+1;
    $name_new = mysqli_real_escape_string($db, $_POST['hourname_name']);
    $sql = "INSERT INTO hourname (idHourname, Timetable_idTimetable, Name) VALUES ($id, $timetable_id, '$name_new');";
    $result = mysqli_query($db, $sql);
    if($result){
        $result0 = mysqli_query($db, "SELECT idHourname FROM hourname WHERE Timetable_idTimetable='$timetable_id';");
        $count_h = mysqli_num_rows($result0);
        $result00 = mysqli_query($db, "UPDATE timetable SET Hours='$count_h' WHERE idTimetable='$timetable_id';");
        header("location: configure.php");
    }
    else
    {
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}

if(isset($_POST['submit6'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $id = mysqli_real_escape_string($db, $_POST['classroom_id']);
    $name_new = mysqli_real_escape_string($db, $_POST['classroom_name']);
    $capacity_new = mysqli_real_escape_string($db, $_POST['classroom_capacity']);
    if((strlen($name_new)>0)&&(strlen($name_new)<80)){
        $sql = "UPDATE classroom SET Name='$name_new', Capacity='$capacity_new' WHERE Timetable_idTimetable='$timetable_id' AND idClassroom='$id';";
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
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}

if(isset($_POST['delete6'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $id = mysqli_real_escape_string($db, $_POST['classroom_id']);
    $sql = "DELETE FROM classroom WHERE Timetable_idTimetable='$timetable_id' AND idClassroom='$id';";
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

if(isset($_POST['submit666'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $row0 = mysqli_fetch_row(mysqli_query($db, "SELECT idClassroom FROM classroom WHERE Timetable_idTimetable='$timetable_id' ORDER BY idClassroom DESC LIMIT 1;"));
    $id = $row0[0]+1;
    $name_new = mysqli_real_escape_string($db, $_POST['classroom_name']);
    $capacity_new = mysqli_real_escape_string($db, $_POST['classroom_capacity']);
    if((strlen($name_new)>0)&&(strlen($name_new)<80)){
        $sql = "INSERT INTO classroom (idClassroom, Timetable_idTimetable, Name, Capacity) VALUES ($id, $timetable_id, '$name_new', '$capacity_new');";
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
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}

if(isset($_POST['delete66'])) {
    $id = mysqli_real_escape_string($db, $_POST['classroom_restriction_id']);
    $sql = "DELETE FROM classroomrestrictions WHERE idClassroomRestrictions='$id';";
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

if(isset($_POST['submit6666'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $id = mysqli_real_escape_string($db, $_POST['classroom_id']);
    $day_new = mysqli_real_escape_string($db, $_POST['classroom_restriction_day']);
    $hour_new = mysqli_real_escape_string($db, $_POST['classroom_restriction_hour']);
    $sql = "INSERT INTO classroomrestrictions (idClassroomRestrictions, Classroom_idClassroom, Classroom_Timetable_idTimetable, Day, Hour) VALUES (NULL, '$id', '$timetable_id', '$day_new', '$hour_new');";
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

if(isset($_POST['submit7'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $id = mysqli_real_escape_string($db, $_POST['teacher_id']);
    $name_new = mysqli_real_escape_string($db, $_POST['teacher_name']);
    $conthours_new = mysqli_real_escape_string($db, $_POST['teacher_conthours']);
    $hpd_new = mysqli_real_escape_string($db, $_POST['teacher_hpd']);
    $dpw_new = mysqli_real_escape_string($db, $_POST['teacher_dpw']);
    if((strlen($name_new)>0)&&(strlen($name_new)<80)){
        $sql = "UPDATE teacher SET Name='$name_new', ContinuingHours='$conthours_new', HoursPerDay='$hpd_new', DaysPerWeek='$dpw_new' WHERE Timetable_idTimetable='$timetable_id' AND idTeacher='$id';";
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
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}

if(isset($_POST['delete7'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $id = mysqli_real_escape_string($db, $_POST['teacher_id']);
    $sql = "DELETE FROM teacher WHERE Timetable_idTimetable='$timetable_id' AND idTeacher='$id';";
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

if(isset($_POST['submit777'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $row0 = mysqli_fetch_row(mysqli_query($db, "SELECT idTeacher FROM teacher WHERE Timetable_idTimetable='$timetable_id' ORDER BY idTeacher DESC LIMIT 1;"));
    $id = $row0[0]+1;
    $name_new = mysqli_real_escape_string($db, $_POST['teacher_name']);
    $conthours_new = mysqli_real_escape_string($db, $_POST['teacher_conthours']);
    $hpd_new = mysqli_real_escape_string($db, $_POST['teacher_hpd']);
    $dpw_new = mysqli_real_escape_string($db, $_POST['teacher_dpw']);
    if((strlen($name_new)>0)&&(strlen($name_new)<80)){
        $sql = "INSERT INTO teacher (idTeacher, Timetable_idTimetable, Name, ContinuingHours, HoursPerDay, DaysPerWeek) VALUES ($id, $timetable_id, '$name_new', '$conthours_new', '$hpd_new', '$dpw_new');";
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
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}

if(isset($_POST['delete77'])) {
    $id = mysqli_real_escape_string($db, $_POST['teacher_restriction_id']);
    $sql = "DELETE FROM teacherrestrictions WHERE idTeacherRestrictions='$id';";
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

if(isset($_POST['submit7777'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $id = mysqli_real_escape_string($db, $_POST['teacher_id']);
    $day_new = mysqli_real_escape_string($db, $_POST['teacher_restriction_day']);
    $hour_new = mysqli_real_escape_string($db, $_POST['teacher_restriction_hour']);
    $sql = "INSERT INTO teacherrestrictions (idTeacherRestrictions, Teacher_idTeacher, Teacher_Timetable_idTimetable, Day, Hour) VALUES (NULL, '$id', '$timetable_id', '$day_new', '$hour_new');";
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

if(isset($_POST['submit8'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $id = mysqli_real_escape_string($db, $_POST['subject_id']);
    $colorgroup_new = mysqli_real_escape_string($db, $_POST['subject_colorgroup']);
    $printgroup_new = mysqli_real_escape_string($db, $_POST['subject_printgroup']);
    $code_new = mysqli_real_escape_string($db, $_POST['subject_code']);
    $name_new = mysqli_real_escape_string($db, $_POST['subject_name']);
    $students_new = mysqli_real_escape_string($db, $_POST['subject_students']);
    $hours_new = mysqli_real_escape_string($db, $_POST['subject_hours']);
    $av_new = mysqli_real_escape_string($db, $_POST['subject_availability']);
    $semister_new = mysqli_real_escape_string($db, $_POST['subject_semister']);
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
    if(((strlen($name_new)>0)&&(strlen($name_new)<120))&&((strlen($hours_new)>0)&&(strlen($hours_new)<20))&&((strlen($semister_new)>0)&&(strlen($semister_new)<30))&&($flag==1)){
        $sql = "UPDATE subject SET Colorgroup_idColorgroup='$colorgroup_new', Printgroup_idPrintgroup='$printgroup_new', Code='$code_new', Name='$name_new', Students='$students_new', Hours='$hours_new', Availability='$av_new', Semister='$semister_new' WHERE Timetable_idTimetable='$timetable_id' AND idSubject='$id';";
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
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}

if(isset($_POST['delete8'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $id = mysqli_real_escape_string($db, $_POST['subject_id']);
    $sql = "DELETE FROM subject WHERE Timetable_idTimetable='$timetable_id' AND idSubject='$id';";
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

if(isset($_POST['submit888'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $row0 = mysqli_fetch_row(mysqli_query($db, "SELECT idSubject FROM subject WHERE Timetable_idTimetable='$timetable_id' ORDER BY idSubject DESC LIMIT 1;"));
    $id = $row0[0]+1;
    $colorgroup_new = mysqli_real_escape_string($db, $_POST['subject_colorgroup']);
    $printgroup_new = mysqli_real_escape_string($db, $_POST['subject_printgroup']);
    $code_new = mysqli_real_escape_string($db, $_POST['subject_code']);
    $name_new = mysqli_real_escape_string($db, $_POST['subject_name']);
    $students_new = mysqli_real_escape_string($db, $_POST['subject_students']);
    $hours_new = mysqli_real_escape_string($db, $_POST['subject_hours']);
    $av_new = mysqli_real_escape_string($db, $_POST['subject_availability']);
    $semister_new = mysqli_real_escape_string($db, $_POST['subject_semister']);
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
    if(($flag==1)&&((strlen($name_new)>0)&&(strlen($name_new)<120))&&((strlen($hours_new)>0)&&(strlen($hours_new)<20))&&((strlen($semister_new)>0)&&(strlen($semister_new)<30))){
        $sql = "INSERT INTO subject (idSubject, Timetable_idTimetable, Colorgroup_idColorgroup, Printgroup_idPrintgroup, Code, Name, Students, Hours, Availability, Semister) VALUES ('$id','$timetable_id','$colorgroup_new','$printgroup_new','$code_new','$name_new','$students_new','$hours_new','$av_new','$semister_new');";
        $result = mysqli_query($db, $sql);
        if($result){
            echo "<br /><center>Change done successfully</center>";

            $result0 = mysqli_query($db, "INSERT INTO subject_has_teacher (Subject_idSubject, Subject_Timetable_idTimetable, Teacher_idTeacher) VALUES ('$id','$timetable_id',1);");

            $result00 = mysqli_query($db, "SELECT idClassroom FROM classroom WHERE Timetable_idTimetable='$timetable_id'");
            $count_classr = mysqli_num_rows($result00);
            $classr_array = array();
            for($i=0;$i<$count_classr;$i++){
                $classr_array[$i] = mysqli_fetch_row($result00);
            }
            for($i=0;$i<$count_classr;$i++){
                $tttemp = $classr_array[$i][0];
                $result000 = mysqli_query($db, "INSERT INTO subject_has_classroom_preference (idSubject_has_Classroom_Preference, Subject_idSubject, Subject_Timetable_idTimetable, idClassroom) VALUES (NULL,'$id','$timetable_id','$tttemp');");
            }

            $N = (strlen($hours_new)+1)/2;
            $v1 = 88;
            $v2 = 0;
            for($i=0;$i<$N;$i++){
                $sql = "INSERT INTO timeclassroom (idTimeClassroom, Subject_idSubject, Subject_Timetable_idTimetable, Time, Classroom) VALUES (NULL, '$id', '$timetable_id', '$v1','$v2');";
                $result = mysqli_query($db, $sql);
                $v2++;
            }

            echo "<br /><center>At the end of the editing page, edit the new entry</center><br />";
            echo "<center><a href = 'edit.php'><div>Go to editing</div></a></center>";
        }
        else
        {
            echo "<br /><center>Change failed</center>";
            echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
        }    
    }
    else
    {
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}

if(isset($_POST['submit888_1'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $s_id = mysqli_real_escape_string($db, $_POST['subject_id']);
    $t_id = mysqli_real_escape_string($db, $_POST['teacher_id']);
    $sql = "INSERT INTO subject_has_teacher (Subject_idSubject, Subject_Timetable_idTimetable, Teacher_idTeacher) VALUES ('$s_id', '$timetable_id', '$t_id');";
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

if(isset($_POST['submit888_2'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $dur = mysqli_real_escape_string($db, $_POST['subject_duration']);
    $id = mysqli_real_escape_string($db, $_POST['subject_id']);
    $sql = "SELECT Hours FROM subject WHERE Timetable_idTimetable='$timetable_id' AND idSubject='$id';";
    $result = mysqli_query($db, $sql);
    if($result){
        $hourss = mysqli_fetch_assoc($result);
        $hours = $hourss["Hours"];
        $hours = $hours . "+" . $dur;
        $sql2 = "UPDATE subject SET Hours='$hours' WHERE Timetable_idTimetable='$timetable_id' AND idSubject='$id';";
        $result2 = mysqli_query($db, $sql2);
        if($result2){
            $sql3 = "INSERT INTO timeclassroom (idTimeClassroom, Subject_idSubject, Subject_Timetable_idTimetable, Time, Classroom) VALUES (NULL, '$id', '$timetable_id', '88','0');";
            $result3 = mysqli_query($db, $sql3);
            if($result3){
                echo "<br /><center>Change done successfully</center>";
                echo "<br /><center>At the end of the editing page, edit the new entry</center><br />";
                echo "<center><a href = 'edit.php'><div>Go to editing</div></a></center>";
            }
            else{
                echo "<br /><center>Change failed</center>";
                echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
            }
        }
        else
        {
            echo "<br /><center>Change failed</center>";
            echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
        }   
    }
    else
    {
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }    
}

if(isset($_POST['submit888_3'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $c_id = mysqli_real_escape_string($db, $_POST['subject_classroompreference']);
    $s_id = mysqli_real_escape_string($db, $_POST['subject_id']);
    $sql = "INSERT INTO subject_has_classroom_preference (idSubject_has_Classroom_Preference, Subject_idSubject, Subject_Timetable_idTimetable, idClassroom) VALUES (NULL, '$s_id', '$timetable_id', '$c_id');";
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

if(isset($_POST['submit888_4'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $id = mysqli_real_escape_string($db, $_POST['subject_id']);
    $day_new = mysqli_real_escape_string($db, $_POST['subject_restriction_day']);
    $hour_new = mysqli_real_escape_string($db, $_POST['subject_restriction_hour']);
    $sql = "INSERT INTO subjectrestrictions (idSubjectRestrictions, Subject_idSubject, Subject_Timetable_idTimetable, Day, Hour) VALUES (NULL, '$id', '$timetable_id', '$day_new', '$hour_new');";
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

if(isset($_POST['delete88_1'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $s_id = mysqli_real_escape_string($db, $_POST['subject_id']);
    $t_id = mysqli_real_escape_string($db, $_POST['teacher_id']);
    $sql = "DELETE FROM subject_has_teacher WHERE Subject_Timetable_idTimetable='$timetable_id' AND Subject_idSubject='$s_id' AND Teacher_idTeacher='$t_id';";
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

if(isset($_POST['delete88_2'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $s_id = mysqli_real_escape_string($db, $_POST['tc_subject_id']);
    $id = mysqli_real_escape_string($db, $_POST['tc_id']);
    $sql = "DELETE FROM timeclassroom WHERE idTimeClassroom='$id';";
    $result = mysqli_query($db, $sql);
    if($result){
        echo "<br /><center>Change done successfully</center>";
        echo "<br /><center>Please edit the Hours slot</center>";
        $result0 = mysqli_query($db, "SELECT Hours FROM subject WHERE Timetable_idTimetable='$timetable_id' AND idSubject='$s_id';");
        $old = mysqli_fetch_row($result0)[0];
        echo "<br /><center>Last Entry: " . $old . "</center>";
        echo "<br /><center>New Entry: <form action='save8822.php' method='post'><input type='radio' name='sub_id' value='$s_id' checked='1'><input type='radio' name='old' value='$old' checked='1'><input type='text' name='n_hours' placeholder='example: 2+2'><input type='submit' name='save88_22' value='Save'></form></center>";   
    }
    else
    {
        echo "<br /><center>Change failed</center>";
    }
}

if(isset($_POST['delete88_3'])) {
    $id = mysqli_real_escape_string($db, $_POST['shcp_id']);
    $sql = "DELETE FROM subject_has_classroom_preference WHERE idSubject_has_classroom_Preference='$id';";
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

if(isset($_POST['delete88_4'])) {
    $id = mysqli_real_escape_string($db, $_POST['subject_restr_id']);
    $sql = "DELETE FROM subjectrestrictions WHERE idSubjectRestrictions='$id';";
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

if(isset($_POST['submit9'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $cg_id = mysqli_real_escape_string($db, $_POST['cgroup_id']);
    $cg_name = mysqli_real_escape_string($db, $_POST['cgroup_name']);
    $cg_mult = mysqli_real_escape_string($db, $_POST['cgroup_multiplier']);
    if((strlen($cg_name)>0)&&(strlen($cg_name)<80)){
        $sql = "UPDATE cgroup SET Name='$cg_name', Multiplier='$cg_mult' WHERE idCgroup='$cg_id' AND Timetable_idTimetable='$timetable_id';";
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
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}

if(isset($_POST['delete9'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $cg_id = mysqli_real_escape_string($db, $_POST['cgroup_id']);
    $sql = "DELETE FROM cgroup WHERE idCgroup='$cg_id' AND Timetable_idTimetable='$timetable_id';";
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

if(isset($_POST['delete99'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $cg_id = mysqli_real_escape_string($db, $_POST['cgroup_id']);
    $s_id = mysqli_real_escape_string($db, $_POST['subject_id']);
    $sql = "DELETE FROM cgroup_has_subject WHERE Cgroup_idCgroup='$cg_id' AND Subject_idSubject='$s_id' AND Subject_Timetable_idTimetable='$timetable_id';";
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

if(isset($_POST['submit999'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $row0 = mysqli_fetch_row(mysqli_query($db, "SELECT idCgroup FROM cgroup WHERE Timetable_idTimetable='$timetable_id' ORDER BY idCgroup DESC LIMIT 1;"));
    $id = $row0[0]+1;
    $name = mysqli_real_escape_string($db, $_POST['cgroup_name']);
    $multiplier = mysqli_real_escape_string($db, $_POST['cgroup_multiplier']);
    if((strlen($name)>0)&&(strlen($name)<80)){
        $sql = "INSERT INTO cgroup (idCgroup, Timetable_idTimetable, Name, Multiplier) VALUES ('$id','$timetable_id','$name','$multiplier');";
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
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}

if(isset($_POST['submit9999'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $cg_id = mysqli_real_escape_string($db, $_POST['cgroup_id']);
    $s_id = mysqli_real_escape_string($db, $_POST['subject_id']);
    $sql = "INSERT INTO cgroup_has_subject (Cgroup_idCgroup, Subject_idSubject, Subject_Timetable_idTimetable) VALUES ('$cg_id','$s_id','$timetable_id');";
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

if(isset($_POST['submit10'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $dg_id = mysqli_real_escape_string($db, $_POST['dgroup_id']);
    $dg_name = mysqli_real_escape_string($db, $_POST['dgroup_name']);
    if((strlen($dg_name)>0)&&(strlen($dg_name)<80)){
        $sql = "UPDATE dgroup SET Name='$dg_name' WHERE idDgroup='$dg_id' AND Timetable_idTimetable='$timetable_id';";
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
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}

if(isset($_POST['delete10'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $dg_id = mysqli_real_escape_string($db, $_POST['dgroup_id']);
    $sql = "DELETE FROM dgroup WHERE idDgroup='$dg_id' AND Timetable_idTimetable='$timetable_id';";
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

if(isset($_POST['delete10_10'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $dg_id = mysqli_real_escape_string($db, $_POST['dgroup_id']);
    $s_id = mysqli_real_escape_string($db, $_POST['subject_id']);
    $sql = "DELETE FROM dgroup_has_subject WHERE Dgroup_idDgroup='$dg_id' AND Subject_idSubject='$s_id' AND Subject_Timetable_idTimetable='$timetable_id';";
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

if(isset($_POST['submit1010'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $row0 = mysqli_fetch_row(mysqli_query($db, "SELECT idDgroup FROM dgroup WHERE Timetable_idTimetable='$timetable_id' ORDER BY idDgroup DESC LIMIT 1;"));
    $id = $row0[0]+1;
    $name = mysqli_real_escape_string($db, $_POST['dgroup_name']);
    if((strlen($name)>0)&&(strlen($name)<80)){
        $sql = "INSERT INTO dgroup (idDgroup, Timetable_idTimetable, Name) VALUES ('$id','$timetable_id','$name');";
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
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}

if(isset($_POST['submit101010'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $dg_id = mysqli_real_escape_string($db, $_POST['dgroup_id']);
    $s_id = mysqli_real_escape_string($db, $_POST['subject_id']);
    $sql = "INSERT INTO dgroup_has_subject (Dgroup_idDgroup, Subject_idSubject, Subject_Timetable_idTimetable) VALUES ('$dg_id','$s_id','$timetable_id');";
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

if(isset($_POST['submit11'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $id = mysqli_real_escape_string($db, $_POST['colorgroup_id']);
    $name = mysqli_real_escape_string($db, $_POST['colorgroup_name']);
    $color = mysqli_real_escape_string($db, $_POST['colorgroup_color']);
    $flag = 0;
    for($i=0;$i<6;$i++){
        if(($color[$i]=='0')||($color[$i]=='1')||($color[$i]=='2')||($color[$i]=='3')||($color[$i]=='4')||($color[$i]=='5')||($color[$i]=='6')||($color[$i]=='7')||($color[$i]=='8')||($color[$i]=='9')||($color[$i]=='A')||($color[$i]=='B')||($color[$i]=='C')||($color[$i]=='D')||($color[$i]=='E')||($color[$i]=='F')){
            $flag = 1;
        }
        else{
            $flag=0;
            break;
        }
    }
    if((strlen($name)>0)&&(strlen($name)<80)&&(strlen($color)==6)&&($flag==1)){
        $sql = "UPDATE colorgroup SET Name='$name', Color='$color' WHERE Timetable_idTimetable='$timetable_id' AND idColorgroup='$id';";
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
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}

if(isset($_POST['delete11'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $id = mysqli_real_escape_string($db, $_POST['colorgroup_id']);
    $sql = "DELETE FROM colorgroup WHERE Timetable_idTimetable='$timetable_id' AND idColorgroup='$id';";
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

if(isset($_POST['submit1111'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $row0 = mysqli_fetch_row(mysqli_query($db, "SELECT idColorgroup FROM colorgroup WHERE Timetable_idTimetable='$timetable_id' ORDER BY idColorgroup DESC LIMIT 1;"));
    $id = $row0[0]+1;
    $name = mysqli_real_escape_string($db, $_POST['colorgroup_name']);
    $color = mysqli_real_escape_string($db, $_POST['colorgroup_color']);
    $flag = 0;
    for($i=0;$i<6;$i++){
        if(($color[$i]=='0')||($color[$i]=='1')||($color[$i]=='2')||($color[$i]=='3')||($color[$i]=='4')||($color[$i]=='5')||($color[$i]=='6')||($color[$i]=='7')||($color[$i]=='8')||($color[$i]=='9')||($color[$i]=='A')||($color[$i]=='B')||($color[$i]=='C')||($color[$i]=='D')||($color[$i]=='E')||($color[$i]=='F')){
            $flag = 1;
        }
        else{
            $flag=0;
            break;
        }
    }
    if((strlen($name)>0)&&(strlen($name)<80)&&(strlen($color)==6)&&($flag==1)){
        $sql = "INSERT INTO colorgroup (idColorgroup, Timetable_idTimetable, Name, Color) VALUES ('$id','$timetable_id','$name','$color');";
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
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}

if(isset($_POST['submit12'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $id = mysqli_real_escape_string($db, $_POST['printgroup_id']);
    $name = mysqli_real_escape_string($db, $_POST['printgroup_name']);
    $number = mysqli_real_escape_string($db, $_POST['printgroup_color']);
    if((strlen($name)>0)&&(strlen($name)<80)){
        $sql = "UPDATE printgroup SET Name='$name', Number='$number' WHERE Timetable_idTimetable='$timetable_id' AND idPrintgroup='$id';";
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
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}

if(isset($_POST['delete12'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $id = mysqli_real_escape_string($db, $_POST['printgroup_id']);
    $sql = "DELETE FROM printgroup WHERE Timetable_idTimetable='$timetable_id' AND idPrintgroup='$id';";
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

if(isset($_POST['submit1212'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $row0 = mysqli_fetch_row(mysqli_query($db, "SELECT idPrintgroup FROM printgroup WHERE Timetable_idTimetable='$timetable_id' ORDER BY idPrintgroup DESC LIMIT 1;"));
    $id = $row0[0]+1;
    $name = mysqli_real_escape_string($db, $_POST['printgroup_name']);
    $number = mysqli_real_escape_string($db, $_POST['printgroup_color']);
    if((strlen($name)>0)&&(strlen($name)<80)){
        $sql = "INSERT INTO printgroup (idPrintgroup, Timetable_idTimetable, Name, Number) VALUES ('$id','$timetable_id','$name','$number');";
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
        echo "<br /><center>Change failed</center>";
        echo "<center><a href = 'configure.php'><div>Return to configuring</div></a></center>";
    }
}

if(isset($_POST['delete13'])) {
    $id = mysqli_real_escape_string($db, $_POST['equal_id']);
    $sql = "DELETE FROM equal WHERE idEqual='$id';";
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

if(isset($_POST['submit1313'])) {
    $timetable_id = $_SESSION['id_timetable'];
    $s1 = mysqli_real_escape_string($db, $_POST['equal_1']);
    $s2 = mysqli_real_escape_string($db, $_POST['equal_2']);
    $sql = "INSERT INTO equal (idEqual, Subject_Timetable_idTimetable, Subject_idSubject1, idSubject2) VALUES (NULL,'$timetable_id','$s1','$s2');";
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

?>