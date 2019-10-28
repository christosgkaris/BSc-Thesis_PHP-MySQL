
<?php
include('menu.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <script type="text/javascript" src="redips-drag-min.js"></script>
        <script type="text/javascript" src="script.js"></script>
        <script type="text/javascript" src="jquery-1.12.1.min.js"></script>
        <title>Edit</title>
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
        $sql = "SELECT * FROM timetable WHERE Name='$selected_val';";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_assoc($result);
        $idtimetable = $row["idTimetable"];
        $days = $row["Days"];
        $hours = $row["Hours"]; 
        
        $_SESSION['id_timetable'] = $idtimetable;
        echo "<br /><br /><center>You have selected: " . $_SESSION['selected_timetable'] . "</center><br />";
        
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
            $timetable_array[$i][7] = $tc_array[$i][0];
        }
        
        
        // Make the table
        $columns = $count_classrooms+1;
        $dayc = -1;
        echo '<br /><br />';
        
        echo "<div id='redips-drag'>";
            foreach($daynames_array as $dayn)
            {
                $dayc++;
                $tableid="table" . $dayc;

                    echo '<table>';

                        echo "<tr>";
                            echo "<th colspan='$columns' class='redips-mark'>";
                                echo "<b>$dayn</b>";
                            echo "</th>";
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th width class='redips-mark'>";
                                echo "Ώρα / Αίθουσα";
                            echo "</th>";
                            for($i=0;$i<$count_classrooms;$i++){
                                echo "<th width=11% class='redips-mark'>";
                                    echo $classrooms_array[$i*2+1];
                                echo "</th>";
                            }
                        echo "</tr>";

                        for($i=0;$i<$count_hournames;$i++){
                            echo "<tr width align=center>";
                                echo "<td class='redips-mark'>";
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
            
            echo '<p align=center>'; echo 'Temp data'; echo '</p>';
            echo '<table id=temptable>';
                echo "<tr>"; 
                    echo "<td id=88_0>"; echo "</td>"; echo "<td id=88_1>"; echo "</td>"; echo "<td id=88_2>"; echo "</td>"; echo "<td id=88_3>"; echo "</td>"; echo "<td id=88_4>"; echo "</td>"; echo "<td id=88_5>"; echo "</td>"; echo "<td id=88_6>"; echo "</td>";
                echo "</tr>";
                echo "<tr>"; 
                    echo "<td id=89_0 class='redips-mark'>"; echo "</td>"; echo "<td id=89_1 class='redips-mark'>"; echo "</td>"; echo "<td id=89_2 class='redips-mark'>"; echo "</td>"; echo "<td id=89_3 class='redips-mark'>"; echo "</td>"; echo "<td id=89_4 class='redips-mark'>"; echo "</td>"; echo "<td id=89_5 class='redips-mark'>"; echo "</td>"; echo "<td id=89_6 class='redips-mark'>"; echo "</td>";
                echo "</tr>";    
                echo "<tr>"; 
                    echo "<td id=90_0 class='redips-mark'>"; echo "</td>"; echo "<td id=90_1 class='redips-mark'>"; echo "</td>"; echo "<td id=90_2 class='redips-mark'>"; echo "</td>"; echo "<td id=90_3 class='redips-mark'>"; echo "</td>"; echo "<td id=90_4 class='redips-mark'>"; echo "</td>"; echo "<td id=90_5 class='redips-mark'>"; echo "</td>"; echo "<td id=90_6 class='redips-mark'>"; echo "</td>"; 
                echo "</tr>";    
                echo "<tr>"; 
                    echo "<td id=91_0 class='redips-mark'>"; echo "</td>"; echo "<td id=91_1 class='redips-mark'>"; echo "</td>"; echo "<td id=91_2 class='redips-mark'>"; echo "</td>"; echo "<td id=91_3 class='redips-mark'>"; echo "</td>"; echo "<td id=91_4 class='redips-mark'>"; echo "</td>"; echo "<td id=91_5 class='redips-mark'>"; echo "</td>"; echo "<td id=91_6 class='redips-mark'>"; echo "</td>"; 
                echo "</tr>";
                echo "<tr>"; 
                    echo "<td id=92_0 class='redips-mark'>"; echo "</td>"; echo "<td id=92_1 class='redips-mark'>"; echo "</td>"; echo "<td id=92_2 class='redips-mark'>"; echo "</td>"; echo "<td id=92_3 class='redips-mark'>"; echo "</td>"; echo "<td id=92_4 class='redips-mark'>"; echo "</td>"; echo "<td id=92_5 class='redips-mark'>"; echo "</td>"; echo "<td id=92_6 class='redips-mark'>"; echo "</td>"; 
                echo "</tr>";
                echo "<tr>"; 
                    echo "<td id=93_0 class='redips-mark'>"; echo "</td>"; echo "<td id=93_1 class='redips-mark'>"; echo "</td>"; echo "<td id=93_2 class='redips-mark'>"; echo "</td>"; echo "<td id=93_3 class='redips-mark'>"; echo "</td>"; echo "<td id=93_4 class='redips-mark'>"; echo "</td>"; echo "<td id=93_5 class='redips-mark'>"; echo "</td>"; echo "<td id=93_6 class='redips-mark'>"; echo "</td>"; 
                echo "</tr>";
                echo "<tr>"; 
                    echo "<td id=94_0 class='redips-mark'>"; echo "</td>"; echo "<td id=94_1 class='redips-mark'>"; echo "</td>"; echo "<td id=94_2 class='redips-mark'>"; echo "</td>"; echo "<td id=94_3 class='redips-mark'>"; echo "</td>"; echo "<td id=94_4 class='redips-mark'>"; echo "</td>"; echo "<td id=94_5 class='redips-mark'>"; echo "</td>"; echo "<td id=94_6 class='redips-mark'>"; echo "</td>"; 
                echo "</tr>";
                echo "<tr>"; 
                    echo "<td id=95_0 class='redips-mark'>"; echo "</td>"; echo "<td id=95_1 class='redips-mark'>"; echo "</td>"; echo "<td id=95_2 class='redips-mark'>"; echo "</td>"; echo "<td id=95_3 class='redips-mark'>"; echo "</td>"; echo "<td id=95_4 class='redips-mark'>"; echo "</td>"; echo "<td id=95_5 class='redips-mark'>"; echo "</td>"; echo "<td id=95_6 class='redips-mark'>"; echo "</td>"; 
                echo "</tr>";
                echo "<tr>"; 
                    echo "<td id=96_0 class='redips-mark'>"; echo "</td>"; echo "<td id=96_1 class='redips-mark'>"; echo "</td>"; echo "<td id=96_2 class='redips-mark'>"; echo "</td>"; echo "<td id=96_3 class='redips-mark'>"; echo "</td>"; echo "<td id=96_4 class='redips-mark'>"; echo "</td>"; echo "<td id=96_5 class='redips-mark'>"; echo "</td>"; echo "<td id=96_6 class='redips-mark'>"; echo "</td>"; 
                echo "</tr>";
                echo "<tr>"; 
                    echo "<td id=97_0 class='redips-mark'>"; echo "</td>"; echo "<td id=97_1 class='redips-mark'>"; echo "</td>"; echo "<td id=97_2 class='redips-mark'>"; echo "</td>"; echo "<td id=97_3 class='redips-mark'>"; echo "</td>"; echo "<td id=97_4 class='redips-mark'>"; echo "</td>"; echo "<td id=97_5 class='redips-mark'>"; echo "</td>"; echo "<td id=97_6 class='redips-mark'>"; echo "</td>"; 
                echo "</tr>";
                echo "<tr>"; 
                    echo "<td id=98_0 class='redips-mark'>"; echo "</td>"; echo "<td id=98_1 class='redips-mark'>"; echo "</td>"; echo "<td id=98_2 class='redips-mark'>"; echo "</td>"; echo "<td id=98_3 class='redips-mark'>"; echo "</td>"; echo "<td id=98_4 class='redips-mark'>"; echo "</td>"; echo "<td id=98_5 class='redips-mark'>"; echo "</td>"; echo "<td id=98_6 class='redips-mark'>"; echo "</td>";  
                echo "</tr>";
                echo "<tr>"; 
                    echo "<td id=99_0 class='redips-mark'>"; echo "</td>"; echo "<td id=99_1 class='redips-mark'>"; echo "</td>"; echo "<td id=99_2 class='redips-mark'>"; echo "</td>"; echo "<td id=99_3 class='redips-mark'>"; echo "</td>"; echo "<td id=99_4 class='redips-mark'>"; echo "</td>"; echo "<td id=99_5 class='redips-mark'>"; echo "</td>"; echo "<td id=99_6 class='redips-mark'>"; echo "</td>"; 
                echo "</tr>";
            echo '</table>';
            echo '<br /><br /><br /><br /><br /><br /><br />';
            
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
                    y[0].innerHTML = ("id:") + js_timetable_array[i][7] + ("<br>") + js_timetable_array[i][2] + ("<br>") + js_timetable_array[i][4] + ("<br>") + js_timetable_array[i][5] + ("<input type='checkbox' onclick='checksame();'/>") + ("<br>");
                    y[0].style.backgroundColor = ("#") + js_timetable_array[i][6];
                }
            }
            
            
            // Function that checks the same checkboxes
            function checksame(){
                var div1 = [], div2, div3, cb, i;
                var tmp1, tmp2, tmp3, tmp2_n, tmp3_n, tmp4, div4, m1, m2;
                
                // Find the one selected checkbox
                // collect DIV elements from drag region
		div1 = document.getElementsByTagName("div");
		// loop through collected DIV elements
		for (i = 0; i < div1.length; i++) {
			// locate checkbox inside DIV element
			cb = div1[i].getElementsByTagName('input');
			// if checkbox inside DIV element is checked
			if (cb.length > 0 && cb[0].checked === true) {
				// save reference of DIV element to the div2
				div2 = div1[i];
			}
		}
                
                // Check if the above and below are the same lecture
                tmp1 = div2.id;
                tmp2 = tmp1.substring(0,2);
                tmp3 = tmp1.substring(4,5);
                tmp2_n = parseInt(tmp2);
                tmp3_n = parseInt(tmp3);
                m1 = tmp2_n % <?php echo $hours; ?>;
                m2 = <?php echo $hours; ?> - m1;
                for(i=tmp2_n-m1 ; i<=tmp2_n+m2 ; i++){
                    tmp4 = i.toString();
                    if(i<10 && i>=0){
                        tmp4 = "0".concat(tmp4);
                    }
                    tmp4 = tmp4.concat("__",tmp3);
                    if(document.contains(document.getElementById(tmp4))){
                        div3 = document.getElementById(tmp4);
                        div4 = div3.getElementsByTagName('input');
                        if (div4.length > 0 && div4[0].checked === false && div3.innerHTML===div2.innerHTML){
                            div4[0].checked = true;
                        }
                    }
                }
                
                // Make all the other checkboxes of schedule disabled
                // collect DIV elements from drag region
		div1 = document.getElementsByTagName("div");
                // loop through collected DIV elements
                for (i = 0; i < div1.length; i++) {
                    cb = div1[i].getElementsByTagName('input');
			// if checkbox inside DIV element is checked
			if (cb.length > 0 && cb[0].checked === false) {
                            // disable button
                            cb[0].disabled = true;
			}
                } 
            }
            
        </script>
        
        <?php
            // Make start and end
            echo "start!!end<br />";
            echo "<div id='start_end'></div>";
            echo "<div id='hours_c'>$hours</div>";
        ?>
        
        <script type='text/javascript'>
            var tid = <?php echo $idtimetable ?>;
            var v1 = "", v2 = "";
            var datab = [];
            var timer = setInterval(function(){ 
                v2=document.getElementById('start_end').innerHTML;
                if(v1!==v2){
                    v1=v2;
                    datab[0]=v1.substring(0, 2); 
                    datab[1]=v1.substring(3, 4); 
                    datab[2]=v1.substring(5, 7); 
                    datab[3]=v1.substring(8, 9); 
                    console.log(datab);
                    //to the database
                    $.post("tcupdate.php",
                    {
                      timetableid: tid,
                      data0: datab[0],
                      data1: datab[1],
                      data2: datab[2],
                      data3: datab[3]
                    },
                    function(data,status){
                        console.log("Data: " + data + "\nStatus: " + status);
                    });
                }
            }, 1000);               
        </script>

    </body>
</html>