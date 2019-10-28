
<?php
include('menu.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Download</title>
    </head>
    <body>
        <?php
        if(isset($_POST['submit'])) {
            $selected_val = $_POST['Name'];
            $_SESSION['selected_timetable'] = $selected_val;
        }
        else{
            if($_SESSION['selected_timetable'] == ""){
                header("location: main.php");
            }
            else{
                $selected_val = $_SESSION['selected_timetable'];
            }
        }
                
        // Get idtimetable, days, hours
        $selected_val = $_SESSION['selected_timetable'];
        $sql = "SELECT idTimetable FROM timetable WHERE Name='$selected_val';";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_assoc($result);
        $idtimetable = $row["idTimetable"];
        
        $_SESSION['id_timetable'] = $idtimetable;
        
        $text = "";
        
        //DAYS
        $text = $text . "DAYS ";
        $result = mysqli_query($db, "SELECT Days FROM timetable WHERE idTimetable='$idtimetable';");
        $text = $text . mysqli_fetch_row($result)[0];
        
        //HOURS
        $text = $text . "\r\n" . "HOURS ";
        $result = mysqli_query($db, "SELECT Hours FROM timetable WHERE idTimetable='$idtimetable';");
        $text = $text . mysqli_fetch_row($result)[0];
        
        //PROBLEMNAME
        $text = $text . "\r\n" . "problemname('";
        $result = mysqli_query($db, "SELECT Name FROM timetable WHERE idTimetable='$idtimetable';");
        $text = $text . mysqli_fetch_row($result)[0];
        $text = $text . "').";
        
        //DAYNAME
        $result = mysqli_query($db, "SELECT idDayname, Name FROM dayname WHERE Timetable_idTimetable='$idtimetable';");
        $count_dayn = mysqli_num_rows($result);
        $dayn_array = array();
        for($i=0;$i<$count_dayn;$i++){
            $dayn_array[$i] = mysqli_fetch_row($result);
        }
        for($i=0;$i<$count_dayn;$i++){
            $text = $text . "\r\n" . "dayname(";
            $text = $text . $dayn_array[$i][0];
            $text = $text . ",'";
            $text = $text . $dayn_array[$i][1];
            $text = $text . "').";
        }
        
        //HOURNAME
        $result = mysqli_query($db, "SELECT idHourname, Name FROM hourname WHERE Timetable_idTimetable='$idtimetable';");
        $count_hourn = mysqli_num_rows($result);
        $hourn_array = array();
        for($i=0;$i<$count_hourn;$i++){
            $hourn_array[$i] = mysqli_fetch_row($result);
        }
        for($i=0;$i<$count_hourn;$i++){
            $text = $text . "\r\n" . "hourname(";
            $text = $text . $hourn_array[$i][0];
            $text = $text . ",'";
            $text = $text . $hourn_array[$i][1];
            $text = $text . "').";
        }
        
        //CLASSROOM
        $result = mysqli_query($db, "SELECT idClassroom, Name, Capacity FROM classroom WHERE Timetable_idTimetable='$idtimetable';");
        $count_classr = mysqli_num_rows($result);
        $classr_array = array();
        for($i=0;$i<$count_classr;$i++){
            $classr_array[$i] = mysqli_fetch_row($result);
        }
        $result = mysqli_query($db, "SELECT Classroom_idClassroom, Day, Hour FROM classroomrestrictions WHERE Classroom_Timetable_idTimetable='$idtimetable';");
        $count_classr_r = mysqli_num_rows($result);
        $classr_r_array = array();
        for($i=0;$i<$count_classr_r;$i++){
            $classr_r_array[$i] = mysqli_fetch_row($result);
        }
        for($i=0;$i<$count_classr;$i++){
            $text = $text . "\r\n" . "classroom(";
            $text = $text . $classr_array[$i][0];
            $text = $text . ",'";
            $text = $text . $classr_array[$i][1];
            $text = $text . "',";
            $text = $text . $classr_array[$i][2];
            $text = $text . ",[";
            for($j=0;$j<$count_classr_r;$j++){
                if($classr_array[$i][0]==$classr_r_array[$j][0]){
                    $text = $text . "na(" . $classr_r_array[$j][1] . "," . $classr_r_array[$j][2] . "),";
                }
            }
            $len = strlen($text);
            if($text[$len-1] == ","){
                $text[$len-1] = "]";
            }
            else{
                $text[$len] = "]";
            }
            $text = $text . ").";
        }
        
        //TEACHER
        $result = mysqli_query($db, "SELECT idTeacher, Name, ContinuingHours, HoursPerDay, DaysPerWeek FROM teacher WHERE Timetable_idTimetable='$idtimetable' ORDER BY Name;");
        $count_teac = mysqli_num_rows($result);
        $teac_array = array();
        for($i=0;$i<$count_teac;$i++){
            $teac_array[$i] = mysqli_fetch_row($result);
        }
        $result = mysqli_query($db, "SELECT Teacher_idTeacher, Day, Hour FROM teacherrestrictions WHERE Teacher_Timetable_idTimetable='$idtimetable';");
        $count_teac_r = mysqli_num_rows($result);
        $teac_r_array = array();
        for($i=0;$i<$count_teac_r;$i++){
            $teac_r_array[$i] = mysqli_fetch_row($result);
        }
        for($i=0;$i<$count_teac;$i++){
            $text = $text . "\r\n" . "teacher(";
            $text = $text . $teac_array[$i][0];
            $text = $text . ",'";
            $text = $text . $teac_array[$i][1];
            $text = $text . "',[";
            for($j=0;$j<$count_teac_r;$j++){
                if($teac_array[$i][0]==$teac_r_array[$j][0]){
                    $text = $text . "na(" . $teac_r_array[$j][1] . "," . $teac_r_array[$j][2] . "),";
                }
            }
            $len = strlen($text);
            if($text[$len-1] == ","){
                $text[$len-1] = "]";
            }
            else{
                $text[$len] = "]";
            }
            $text = $text . ",";
            $text = $text . $teac_array[$i][2];
            $text = $text . ",";
            $text = $text . $teac_array[$i][3];
            $text = $text . ",";
            $text = $text . $teac_array[$i][4];
            $text = $text . ").";
        }
        
        //SUBJECT
        $result = mysqli_query($db, "SELECT * FROM subject WHERE Timetable_idTimetable='$idtimetable' ORDER BY Code;");
        $count_subj = mysqli_num_rows($result);
        $subj_array = array();
        for($i=0;$i<$count_subj;$i++){
            $subj_array[$i] = mysqli_fetch_row($result);
        }
        $result = mysqli_query($db, "SELECT Subject_idSubject, Teacher_idTeacher FROM subject_has_teacher WHERE Subject_Timetable_idTimetable='$idtimetable';");
        $count_subj_has_t = mysqli_num_rows($result);
        $subj_has_t_array = array();
        for($i=0;$i<$count_subj_has_t;$i++){
            $subj_has_t_array[$i] = mysqli_fetch_row($result);
        }
        $result = mysqli_query($db, "SELECT Subject_idSubject, Time, Classroom FROM timeclassroom WHERE Subject_Timetable_idTimetable='$idtimetable';");
        $count_timeclassr = mysqli_num_rows($result);
        $timeclassr_array = array();
        for($i=0;$i<$count_timeclassr;$i++){
            $timeclassr_array[$i] = mysqli_fetch_row($result);
        }
        $result = mysqli_query($db, "SELECT Subject_idSubject, idClassroom FROM subject_has_classroom_preference WHERE Subject_Timetable_idTimetable='$idtimetable';");
        $count_shcpref = mysqli_num_rows($result);
        $shcpref_array = array();
        for($i=0;$i<$count_shcpref;$i++){
            $shcpref_array[$i] = mysqli_fetch_row($result);
        }
        $result = mysqli_query($db, "SELECT Subject_idSubject, Day, Hour FROM subjectrestrictions WHERE Subject_Timetable_idTimetable='$idtimetable';");
        $count_subj_r = mysqli_num_rows($result);
        $subj_r_array = array();
        for($i=0;$i<$count_subj_r;$i++){
            $subj_r_array[$i] = mysqli_fetch_row($result);
        }
        for($i=0;$i<$count_subj;$i++){
            $text = $text . "\r\n" . "subject(";
            $text = $text . $subj_array[$i][0];
            $text = $text . ",'";
            $text = $text . $subj_array[$i][4];
            $text = $text . "','";
            $text = $text . $subj_array[$i][5];
            $text = $text . "',";
            $text = $text . $subj_array[$i][6];
            $text = $text . ",";
            $text = $text . $subj_array[$i][7];
            $text = $text . ",[";
            $c1 = 0;
            for($j=0;$j<$count_subj_has_t;$j++){
                if($subj_array[$i][0]==$subj_has_t_array[$j][0]){
                    if($c1>=1){
                        $text = $text . ",";
                    }
                    $text = $text . $subj_has_t_array[$j][1];
                    $c1++;
                }
            }
            $text = $text . "],";
            $text = $text . $subj_array[$i][8];
            $text = $text . ",[";
            for($j=0;$j<$count_timeclassr;$j++){
                if($subj_array[$i][0]==$timeclassr_array[$j][0]){
                    $text = $text . $timeclassr_array[$j][1] . " " . $timeclassr_array[$j][2] . " ";
                }
            }
            $len = strlen($text);
            if($subj_array[$i][8]==3){
               $text[$len-1] = "]";
            }
            else{
               $text[$len] = "]";
            }
            $text = $text . ",[";
            for($j=0;$j<$count_shcpref;$j++){
                if($subj_array[$i][0]==$shcpref_array[$j][0]){
                    $text = $text . $shcpref_array[$j][1] . " ";
                }
            }
            $len = strlen($text);
            $text[$len-1] = "]";
            $text = $text . ",'";
            
            $temsem = "";
            for($k=0;$k<strlen($subj_array[$i][9]);$k++){
                if($subj_array[$i][9][$k]=="'")
                {
                    $temsem = $temsem . "\'";
                }
                else{
                    $temsem = $temsem . $subj_array[$i][9][$k];
                }
            }
            $text = $text . $temsem;
            
            $text = $text . "',[";
            for($j=0;$j<$count_subj_r;$j++){
                if($subj_array[$i][0]==$subj_r_array[$j][0]){
                    $text = $text . "na(" . $subj_r_array[$j][1] . "," . $subj_r_array[$j][2] . "),";
                }
            }
            $len = strlen($text);
            if($text[$len-1] == ","){
                $text[$len-1] = "]";
            }
            else{
                $text[$len] = "]";
            }
            $text = $text . ").";
        }
        
        //CGROUP
        $result = mysqli_query($db, "SELECT * FROM cgroup WHERE Timetable_idTimetable='$idtimetable' ORDER BY Name;");
        $count_cgr = mysqli_num_rows($result);
        $cgr_array = array();
        for($i=0;$i<$count_cgr;$i++){
            $cgr_array[$i] = mysqli_fetch_row($result);
        }
        $result = mysqli_query($db, "SELECT * FROM cgroup_has_subject WHERE Subject_Timetable_idTimetable='$idtimetable';");
        $count_cgr_has_s = mysqli_num_rows($result);
        $cgr_has_s_array = array();
        for($i=0;$i<$count_cgr_has_s;$i++){
            $cgr_has_s_array[$i] = mysqli_fetch_row($result);
        }
        for($i=0;$i<$count_cgr;$i++){
            $text = $text . "\r\n" . "cgroup(";
            $text = $text . $cgr_array[$i][0] . ",'";
            
            $temcg = "";
            for($k=0;$k<strlen($cgr_array[$i][2]);$k++){
                if($cgr_array[$i][2][$k]=="'")
                {
                    $temcg = $temcg . "\'";
                }
                else{
                    $temcg = $temcg . $cgr_array[$i][2][$k];
                }
            }
            
            $text = $text . $temcg . "',[";
            for($j=0;$j<$count_cgr_has_s;$j++){
                if($cgr_array[$i][0]==$cgr_has_s_array[$j][0]){
                    $text = $text . $cgr_has_s_array[$j][1] . ",";
                }
            }
            $len = strlen($text);
            $text[$len-1] = "]";
            $text = $text . "," . $cgr_array[$i][3] . ").";            
        }
        
        //DGROUP
        $result = mysqli_query($db, "SELECT * FROM dgroup WHERE Timetable_idTimetable='$idtimetable' ORDER BY Name;");
        $count_dgr = mysqli_num_rows($result);
        $dgr_array = array();
        for($i=0;$i<$count_dgr;$i++){
            $dgr_array[$i] = mysqli_fetch_row($result);
        }
        $result = mysqli_query($db, "SELECT * FROM dgroup_has_subject WHERE Subject_Timetable_idTimetable='$idtimetable';");
        $count_dgr_has_s = mysqli_num_rows($result);
        $dgr_has_s_array = array();
        for($i=0;$i<$count_dgr_has_s;$i++){
            $dgr_has_s_array[$i] = mysqli_fetch_row($result);
        }
        for($i=0;$i<$count_dgr;$i++){
            $text = $text . "\r\n" . "dgroup(";
            $text = $text . $dgr_array[$i][0] . ",'";
            
            $temdg = "";
            for($k=0;$k<strlen($dgr_array[$i][2]);$k++){
                if($dgr_array[$i][2][$k]=="'")
                {
                    $temdg = $temdg . "\'";
                }
                else{
                    $temdg = $temdg . $dgr_array[$i][2][$k];
                }
            }
            
            $text = $text . $temdg . "',[";
            for($j=0;$j<$count_dgr_has_s;$j++){
                if($dgr_array[$i][0]==$dgr_has_s_array[$j][0]){
                    $text = $text . $dgr_has_s_array[$j][1] . ",";
                }
            }
            $len = strlen($text);
            $text[$len-1] = "]";
            $text = $text . ").";            
        }
        
        //COLORGROUP
        $result = mysqli_query($db, "SELECT * FROM colorgroup WHERE Timetable_idTimetable='$idtimetable';");
        $count_clrgr = mysqli_num_rows($result);
        $clrgr_array = array();
        for($i=0;$i<$count_clrgr;$i++){
            $clrgr_array[$i] = mysqli_fetch_row($result);
        }
        for($i=0;$i<$count_clrgr;$i++){
            $text = $text . "\r\n" . "colorgroup(";
            $text = $text . $clrgr_array[$i][0] . ",'";
            $text = $text . $clrgr_array[$i][2] . "',[";
            for($j=0;$j<$count_subj;$j++){
                if($clrgr_array[$i][0]==$subj_array[$j][2]){
                    $text = $text . $subj_array[$j][0] . ",";
                }
            }
            $len = strlen($text);
            $text[$len-1] = "]";
            $text = $text . ",'" . $clrgr_array[$i][3] . "'";
            $text = $text . ").";
        }
        
        //PRINTGROUP
        $result = mysqli_query($db, "SELECT * FROM printgroup WHERE Timetable_idTimetable='$idtimetable';");
        $count_prntgr = mysqli_num_rows($result);
        $prntgr_array = array();
        for($i=0;$i<$count_prntgr;$i++){
            $prntgr_array[$i] = mysqli_fetch_row($result);
        }
        for($i=0;$i<$count_prntgr;$i++){
            $text = $text . "\r\n" . "printgroup(";
            $text = $text . $prntgr_array[$i][0] . ",'";
            $text = $text . $prntgr_array[$i][2] . "',[";
            for($j=0;$j<$count_subj;$j++){
                if($prntgr_array[$i][0]==$subj_array[$j][3]){
                    $text = $text . $subj_array[$j][0] . ",";
                }
            }
            $len = strlen($text);
            $text[$len-1] = "]";
            $text = $text . "," . $prntgr_array[$i][3];
            $text = $text . ").";
        }
        
        //EQUAL
        $result = mysqli_query($db, "SELECT * FROM equal WHERE Subject_Timetable_idTimetable='$idtimetable';");
        $count_eq = mysqli_num_rows($result);
        $eq_array = array();
        for($i=0;$i<$count_eq;$i++){
            $eq_array[$i] = mysqli_fetch_row($result);
        }
        for($i=0;$i<$count_eq;$i++){
            $text = $text . "\r\n" . "equal(";
            $text = $text . $eq_array[$i][2] . ",";
            $text = $text . $eq_array[$i][3] . ").";
        }
        $text = $text . "\r\n";
                              
        //$text = iconv(mb_detect_encoding($text, mb_detect_order(), true),"ISO-8859-7", $text);
        //$text = iconv(mb_detect_encoding($text, mb_detect_order(), true),"Windows-1253", $text);
        $text = iconv("UTF-8","ISO-8859-7", $text);
        //$text = iconv("UTF-8","Windows-1253", $text);
	$file = fopen("uploads/test.fct","w");  
        fwrite($file,$text);
        fclose($file);
        
        echo "<br /><br /><br /><br /><br /><br /><br />";
        echo "<center>Click on the link below to download the fct file</center>";
        echo "<br />";
        ?>
                
        <div id="menu" align="center">
            <ul><li><a href="uploads/test.fct" download>Download</a></li></ul>
        </div>
        
    </body>
</html>