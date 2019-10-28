
<?php
include('config.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>View timetable</title>
        <meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="stylesheet2.css"/>
    </head>
    <body>
        <?php
        if(isset($_GET['submit'])) {
            $selected_val = $_GET['Name'];
        }
        else{
            header("location: view.php");
        }

        // Get idtimetable, days, hours
        $sql = "SELECT * FROM timetable WHERE Name='$selected_val';";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_assoc($result);
        $idtimetable = $row["idTimetable"];
        $days = $row["Days"];
        $hours = $row["Hours"]; 
        
        echo "<br /><br /><br /><center>" . $selected_val . "</center><br />";
        
        // Get classroom ids and names
        $sql = "SELECT idClassroom, Name FROM classroom WHERE Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_classrooms = mysqli_num_rows($result);
        $classrooms_array = array();
        for($i=0;$i<$count_classrooms;$i++){
            $classrooms = mysqli_fetch_assoc($result);
            array_push($classrooms_array, $classrooms["idClassroom"], $classrooms["Name"]);
        }
        
        // Get day names
        $sql = "SELECT Name FROM dayname WHERE Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_daynames = mysqli_num_rows($result);
        $daynames_array = array();
        for($i=0;$i<$count_daynames;$i++){
            $daynames = mysqli_fetch_assoc($result);
            array_push($daynames_array, $daynames["Name"]);
        }
        
        // Get hour names
        $sql = "SELECT Name FROM hourname WHERE Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_hournames = mysqli_num_rows($result);
        $hournames_array = array();
        for($i=0;$i<$count_hournames;$i++){
            $hournames = mysqli_fetch_assoc($result);
            array_push($hournames_array, $hournames["Name"]);
        }
        
        
        // Get sql table subject
        $sql = "SELECT idSubject, Name, Hours, Semister FROM subject WHERE Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_subjects = mysqli_num_rows($result);
        $subjects_array = array();
        for($i=0;$i<$count_subjects;$i++){
            $subjects_array[$i] = mysqli_fetch_row($result);
        }
        
        // Get sql table subjectidsubject,teacher.name
        $sql = "SELECT subject_has_teacher.Subject_idSubject, teacher.Name " . 
               "FROM subject_has_teacher INNER JOIN teacher " . 
               "ON subject_has_teacher.Subject_Timetable_idTimetable='$idtimetable' " . 
               "AND teacher.Timetable_idTimetable='$idtimetable' " . 
               "AND subject_has_teacher.Teacher_idTeacher=teacher.idTeacher;";
        $result = mysqli_query($db, $sql);
        $count_subjtchr = mysqli_num_rows($result);
        $subjtchr_array = array();
        for($i=0;$i<$count_subjtchr;$i++){
            $subjtchr_array[$i] = mysqli_fetch_row($result);
        }
        
        // Get sql table TimeClassroom
        $sql = "SELECT Subject_idSubject, Time, Classroom FROM timeclassroom WHERE Subject_Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_tc = mysqli_num_rows($result);
        $tc_array = array();
        for($i=0;$i<$count_tc;$i++){
            $tc_array[$i] = mysqli_fetch_row($result);
        }
        
        // Get sql table idsubject,color
        $sql = "SELECT subject.idSubject, colorgroup.Color " . 
               "FROM subject INNER JOIN colorgroup " . 
               "ON subject.Timetable_idTimetable='$idtimetable' " . 
               "AND colorgroup.Timetable_idTimetable='$idtimetable' " . 
               "AND subject.Colorgroup_idColorgroup=colorgroup.idColorgroup;";
        $result = mysqli_query($db, $sql);
        $count_clr = mysqli_num_rows($result);
        $clr_array = array();
        for($i=0;$i<$count_clr;$i++){
            $clr_array[$i] = mysqli_fetch_row($result);
        }
        
        
        // Make the four arrays one array
        $timetable_array = array();
        for($i=0;$i<$count_tc;$i++){
            $timetable_array[$i][0] = $tc_array[$i][1];
            $timetable_array[$i][1] = $tc_array[$i][2];
            for($j=0;$j<$count_subjects;$j++){
                if($subjects_array[$j][0]===$tc_array[$i][0]){
                    $timetable_array[$i][2] = $subjects_array[$j][1];
                    break;
                }
            }
            $hhours = $subjects_array[$j][2]; //2+2
            $timetable_array[$i][3] = $hhours[0];  //2
            $subjects_array[$j][2] = substr($subjects_array[$j][2],2,20);  //2
            $timetable_array[$i][4] = $subjects_array[$j][3];  //1o - YM
            $timetable_array[$i][5] = "";
            for($k=0;$k<$count_subjtchr;$k++){
                if($subjtchr_array[$k][0]===$tc_array[$i][0]){
                    $timetable_array[$i][5] = $timetable_array[$i][5] . $subjtchr_array[$k][1] . "<br />";
                }
            }
            for($l=0;$l<$count_clr;$l++){
                $timetable_array[$i][6] = "FFFFFF";
                if($clr_array[$l][0]===$tc_array[$i][0]){
                    $timetable_array[$i][6] = $clr_array[$l][1];
                    break;
                }
            }
        }
        
        
        // Make the table
        $columns = $count_classrooms+1;
        $dayc = -1;
        echo '<br /><br />';
        
        echo "<div>";
            foreach($daynames_array as $dayn)
            {
                $dayc++;
                $tableid="table" . $dayc;

                    echo '<table>';

                        echo "<tr>";
                            echo "<th colspan='$columns'>";
                                echo "<b>$dayn</b>";
                            echo "</th>";
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th width>";
                                echo "Ώρα / Αίθουσα";
                            echo "</th>";
                            for($i=0;$i<$count_classrooms;$i++){
                                echo "<th width=11%>";
                                    echo $classrooms_array[$i*2+1];
                                echo "</th>";
                            }
                        echo "</tr>";

                        for($i=0;$i<$count_hournames;$i++){
                            echo "<tr width align=center>";
                                echo "<td>";
                                    echo "<b>$hournames_array[$i]</b>";
                                echo "</td>";
                                for($j=0;$j<$count_classrooms;$j++){
                                    $rowid = $dayc*$hours+$i;
                                    $colid = $classrooms_array[$j*2];
                                    if($rowid<10){
                                        $cellid = "0" . $rowid . "_" . $colid;
                                    }
                                    else{
                                        $cellid = $rowid . "_" . $colid;
                                    }
                                    echo "<td id=$cellid>";
                                        //echo $cellid;
                                    echo "</td>";
                                }
                            echo "</tr>";
                        }

                    echo '</table>';

                echo '<br /><br /><br /><br /><br /><br /><br />';
            }
            
        echo "</div>";
       
        ?>
        
        
        <script type='text/javascript'>
            var js_timetable_array = <?php echo json_encode($timetable_array); ?>;
            var i,j,cid,tmp1,tmp2,tmp3,div,cid2;
            
            // Fill the timetable
            for(i=0;i<<?php echo $count_tc; ?>;i++){
                for(j=0;j<js_timetable_array[i][3];j++){
                    tmp1 = parseInt(js_timetable_array[i][0]);
                    tmp2 = tmp1 + j;
                    tmp3 = tmp2.toString();
                    if(tmp2<10){
                       tmp3 = "0".concat(tmp3);
                    }
                    cid = tmp3.concat("_",js_timetable_array[i][1]);
                    cid2 = tmp3.concat("__",js_timetable_array[i][1]);
                    div = document.createElement("div");
                    div.setAttribute('id', cid2);
                    div.setAttribute('class', 'redips-drag');
                    var myElement = document.getElementById(cid);
                    myElement.appendChild(div);
                    var y = myElement.getElementsByTagName("div");
                    y[0].innerHTML = js_timetable_array[i][2] + ("<br>") + js_timetable_array[i][4] + ("<br>") + js_timetable_array[i][5];
                    y[0].style.backgroundColor = ("#") + js_timetable_array[i][6];
                }
            }            
        </script>
        
    </body>
</html>