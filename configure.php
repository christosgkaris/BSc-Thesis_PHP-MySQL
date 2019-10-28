
<?php
include('menu.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <script type="text/javascript" src="jquery-1.12.1.min.js"></script>
        <title>Configure</title>
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
        $name = $row["Name"];
        $viewable = $row["Viewable"];
        
        $_SESSION['id_timetable'] = $idtimetable;
        
        // Get Daynames
        $sql = "SELECT * FROM dayname WHERE Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_daynames = mysqli_num_rows($result);
        $daynames_array = array();
        for($i=0;$i<$count_daynames;$i++){
            $daynames_array[$i] = mysqli_fetch_row($result);
        }
        
        // Get Hournames
        $sql = "SELECT * FROM hourname WHERE Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_hournames = mysqli_num_rows($result);
        $hournames_array = array();
        for($i=0;$i<$count_hournames;$i++){
            $hournames_array[$i] = mysqli_fetch_row($result);
        }
        
        // Get Classrooms
        $sql = "SELECT * FROM classroom WHERE Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_classrooms = mysqli_num_rows($result);
        $classrooms_array = array();
        for($i=0;$i<$count_classrooms;$i++){
            $classrooms_array[$i] = mysqli_fetch_row($result);
        }
        // Get ClassroomRestrictions
        $sql = "SELECT * FROM classroomrestrictions WHERE Classroom_Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_classroom_restrictions = mysqli_num_rows($result);
        $classroom_restrictions_array = array();
        for($i=0;$i<$count_classroom_restrictions;$i++){
            $classroom_restrictions_array[$i] = mysqli_fetch_row($result);
        }
        
        // Get Teachers
        $sql = "SELECT * FROM teacher WHERE Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_teachers = mysqli_num_rows($result);
        $teachers_array = array();
        for($i=0;$i<$count_teachers;$i++){
            $teachers_array[$i] = mysqli_fetch_row($result);
        }
        // Get TeacherRestrictions
        $sql = "SELECT * FROM teacherrestrictions WHERE Teacher_Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_teacher_restrictions = mysqli_num_rows($result);
        $teacher_restrictions_array = array();
        for($i=0;$i<$count_teacher_restrictions;$i++){
            $teacher_restrictions_array[$i] = mysqli_fetch_row($result);
        }
        
        // Get Subjects
        $sql = "SELECT * FROM subject WHERE Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_subjects = mysqli_num_rows($result);
        $subjects_array = array();
        for($i=0;$i<$count_subjects;$i++){
            $subjects_array[$i] = mysqli_fetch_row($result);
        }
        // Get Subject_has_Teacher
        $sql = "SELECT subject_has_teacher.Subject_idSubject, teacher.idTeacher, teacher.Name " . 
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
        // Get TimeClassroom
        $sql = "SELECT * FROM timeclassroom WHERE Subject_Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_tc = mysqli_num_rows($result);
        $tc_array = array();
        for($i=0;$i<$count_tc;$i++){
            $tc_array[$i] = mysqli_fetch_row($result);
        }
        // Get Subject_has_Classroom_Preference
        $sql = "SELECT * FROM subject_has_classroom_preference WHERE Subject_Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_shcp = mysqli_num_rows($result);
        $shcp_array = array();
        for($i=0;$i<$count_shcp;$i++){
            $shcp_array[$i] = mysqli_fetch_row($result);
        }
        // Get SubjectRestrictions
        $sql = "SELECT * FROM subjectrestrictions WHERE Subject_Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_subject_restrictions = mysqli_num_rows($result);
        $subject_restrictions_array = array();
        for($i=0;$i<$count_subject_restrictions;$i++){
            $subject_restrictions_array[$i] = mysqli_fetch_row($result);
        }
        
        // Get Cgroup
        $sql = "SELECT * FROM cgroup WHERE Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_cgroup = mysqli_num_rows($result);
        $cgroup_array = array();
        for($i=0;$i<$count_cgroup;$i++){
            $cgroup_array[$i] = mysqli_fetch_row($result);
        }
        // Get Cgroup_has_subject
        $sql = "SELECT cgroup_has_subject.Cgroup_idCgroup, cgroup_has_subject.Subject_idSubject, cgroup_has_subject.Subject_Timetable_idTimetable, subject.Name " . 
               "FROM cgroup_has_subject INNER JOIN subject " . 
               "ON cgroup_has_subject.Subject_Timetable_idTimetable='$idtimetable' " . 
               "AND subject.Timetable_idTimetable='$idtimetable' " . 
               "AND cgroup_has_subject.Subject_idSubject=subject.idSubject;";
        $result = mysqli_query($db, $sql);
        $count_cgroup_has_subj = mysqli_num_rows($result);
        $cgroup_has_subj_array = array();
        for($i=0;$i<$count_cgroup_has_subj;$i++){
            $cgroup_has_subj_array[$i] = mysqli_fetch_row($result);
        }
        
        // Get Dgroup
        $sql = "SELECT * FROM dgroup WHERE Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_dgroup = mysqli_num_rows($result);
        $dgroup_array = array();
        for($i=0;$i<$count_dgroup;$i++){
            $dgroup_array[$i] = mysqli_fetch_row($result);
        }
        // Get Dgroup_has_subject
        $sql = "SELECT dgroup_has_subject.Dgroup_idDgroup, dgroup_has_subject.Subject_idSubject, dgroup_has_subject.Subject_Timetable_idTimetable, subject.Name " . 
               "FROM dgroup_has_subject INNER JOIN subject " . 
               "ON dgroup_has_subject.Subject_Timetable_idTimetable='$idtimetable' " . 
               "AND subject.Timetable_idTimetable='$idtimetable' " . 
               "AND dgroup_has_subject.Subject_idSubject=subject.idSubject;";
        $result = mysqli_query($db, $sql);
        $count_dgroup_has_subj = mysqli_num_rows($result);
        $dgroup_has_subj_array = array();
        for($i=0;$i<$count_dgroup_has_subj;$i++){
            $dgroup_has_subj_array[$i] = mysqli_fetch_row($result);
        }
        
        // Get Colorgroup
        $sql = "SELECT * FROM colorgroup WHERE Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_colorgroup = mysqli_num_rows($result);
        $colorgroup_array = array();
        for($i=0;$i<$count_colorgroup;$i++){
            $colorgroup_array[$i] = mysqli_fetch_row($result);
        }
        
        // Get Printgroup
        $sql = "SELECT * FROM printgroup WHERE Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_printgroup = mysqli_num_rows($result);
        $printgroup_array = array();
        for($i=0;$i<$count_printgroup;$i++){
            $printgroup_array[$i] = mysqli_fetch_row($result);
        }
        
        // Get Equal
        $sql = "SELECT * FROM equal WHERE Subject_Timetable_idTimetable='$idtimetable';";
        $result = mysqli_query($db, $sql);
        $count_equal = mysqli_num_rows($result);
        $equal_array = array();
        for($i=0;$i<$count_equal;$i++){
            $equal_array[$i] = mysqli_fetch_row($result);
        }
                
        ?>
        
        <br /><center><p>You have selected: <?php echo $_SESSION['selected_timetable'] ?></p></center><br /><br /><br />
        
        <!-- Configure VIEWABLE -->
        <form method="post" action="changes.php">
            Viewable (0/1): 
            <input type="text" name="viewable" size=1 value="<?php echo $viewable;?>">
            <input type="submit" name="submit0" value="Save">
        </form>
        <br /><br />
        
        <!-- Configure DAYS -->
        <form method="post" action="changes.php">
            Days: 
            <input type="text" name="days" size=1 value="<?php echo $days;?>">
        </form>
        <br /><br />
        
        <!-- Configure HOURS -->
        <form method="post" action="changes.php">
            Hours: 
            <input type="text" name="hours" size=1 value="<?php echo $hours;?>">
        </form>
        <br /><br />
        
        <!-- Configure problemname -->
        <form method="post" action="changes.php">
            Name: 
            <input type="text" name="name" size=50 value="<?php echo $name;?>">
            <input type="submit" name="submit3" value="Save">
        </form>
        <br /><br /><br /><br />
        
        <p>Dayname configuration area: Name</p>
        <script type='text/javascript'>
            var js_daynames_array = <?php echo json_encode($daynames_array); ?>;
            var i;
            var N = <?php echo $count_daynames;?>;
            
            for(i=0;i<N;i++){        
                $form4 = $("<form></form>");
                $form4.attr('action', "changes.php");
                $form4.attr('method', "post");
                $form4.append('<input type="radio" name="dayname_id" value="" checked>');
                $form4[0][0].value=js_daynames_array[i][0];
                $form4[0][0].style.display = "none";
                $form4.append(" Dayname with ID: " + js_daynames_array[i][0] + "--->");
                $form4.append('<input type="text" name="dayname_name" size=20 value="">');
                $form4[0][1].value=js_daynames_array[i][2];
                $form4.append('<input type="submit" name="submit4" value="Save">');
                $form4.append('<input type="submit" name="delete4" value="Delete">');
                $('body').append($form4);
            }
            $div4 = $("<div id='div4'></div>");
            $('body').append($div4);
            $button4 = $("<button id='add4'>Add Dayname</button>");
            $('body').append($button4);
        </script>
        <br /><br /><br /><br /><br />
        
        <p>Hourname configuration area: Name</p>
        <script type='text/javascript'>
            var js_hournames_array = <?php echo json_encode($hournames_array); ?>;
            var i;
            var N = <?php echo $count_hournames;?>;
            
            for(i=0;i<N;i++){        
                $form5 = $("<form></form>");
                $form5.attr('action', "changes.php");
                $form5.attr('method', "post");
                $form5.append('<input type="radio" name="hourname_id" value="" checked>');
                $form5[0][0].value=js_hournames_array[i][0];
                $form5[0][0].style.display = "none";
                $form5.append(" Hourname with ID: " + js_hournames_array[i][0] + "--->");
                $form5.append('<input type="text" name="hourname_name" size=20 value="">');
                $form5[0][1].value=js_hournames_array[i][2];
                $form5.append('<input type="submit" name="submit5" value="Save">');
                $form5.append('<input type="submit" name="delete5" value="Delete">');
                $('body').append($form5);
            }
            $div5 = $("<div id='div5'></div>");
            $('body').append($div5);
            $button5 = $("<button id='add5'>Add Hourname</button>");
            $('body').append($button5);
        </script>
        <br /><br /><br /><br /><br />
        
        <p>Classroom configuration area: Name, Capacity</p>
        <script type='text/javascript'>
            var js_classrooms_array = <?php echo json_encode($classrooms_array); ?>;
            var js_classroom_restrictions_array = <?php echo json_encode($classroom_restrictions_array); ?>;
            var i,j,divid;
            var N = <?php echo $count_classrooms;?>;
            var M = <?php echo $count_classroom_restrictions;?>;
            
            for(i=0;i<N;i++){        
                $form6 = $("<form></form>");
                $form6.attr('action', "changes.php");
                $form6.attr('method', "post");
                $form6.append('<input type="radio" name="classroom_id" value="" checked>');
                $form6[0][0].value=js_classrooms_array[i][0];
                $form6[0][0].style.display = "none";
                $form6.append(" Classroom with ID: " + js_classrooms_array[i][0] + "--->");
                $form6.append('<input type="text" name="classroom_name" size=20 value="">');
                $form6[0][1].value=js_classrooms_array[i][2];
                $form6.append('<input type="text" name="classroom_capacity" size=2 value="">');
                $form6[0][2].value=js_classrooms_array[i][3];
                $form6.append('<input type="submit" name="submit6" value="Save">');
                $form6.append('<input type="submit" name="delete6" value="Delete">');
                divid = "restr6" + js_classrooms_array[i][0];
                var myDivElement = $('<div>', {id:divid});
                myDivElement.appendTo($form6);
                $('body').append($form6);
                for(j=0;j<M;j++){
                    if(js_classrooms_array[i][0]===js_classroom_restrictions_array[j][1]){
                        $form66 = $("<form></form>");
                        $form66.attr('action', "changes.php");
                        $form66.attr('method', "post");
                        $form66.append('<input type="radio" name="classroom_restriction_id" value="" checked>');
                        $form66[0][0].value=js_classroom_restrictions_array[j][0];
                        $form66[0][0].style.display = "none";
                        $form66.append('<input type="radio" name="classroom_id" value="" checked>');
                        $form66[0][1].value=js_classroom_restrictions_array[j][1];
                        $form66[0][1].style.display = "none";
                        $form66.append(" --- Classroom has restriction for day and hour--->");
                        $form66.append('<input type="text" name="classroom_restriction_day" size=2 value="">');
                        $form66[0][2].value=js_classroom_restrictions_array[j][3];
                        $form66.append('<input type="text" name="classroom_restriction_hour" size=2 value="">');
                        $form66[0][3].value=js_classroom_restrictions_array[j][4];
                        $form66.append('<input type="submit" name="delete66" value="Delete">');
                        myDivElement.append($form66);
                    }
                } 
            }
            $div6 = $("<div id='div6'></div>");
            $('body').append($div6);
            $button6 = $("<button id='add6'>Add Classroom</button>");
            $('body').append($button6);
            $('body').append('<input type="submit" name="addrestr6" value="Add Classroom Restriction" id="add66">');
        </script>
        <br /><br /><br /><br /><br />
        
        <p>Teacher configuration area: Name, Continuing Hours, Hours Pre Day, Days Per Week</p>
        <script type='text/javascript'>
            var js_teachers_array = <?php echo json_encode($teachers_array); ?>;
            var js_teacher_restrictions_array = <?php echo json_encode($teacher_restrictions_array); ?>;
            var i,j,divid;
            var N = <?php echo $count_teachers;?>;
            var M = <?php echo $count_teacher_restrictions;?>;
        
            for(i=0;i<N;i++){
                $form7 = $("<form></form>");
                $form7.attr('action', "changes.php");
                $form7.attr('method', "post");
                $form7.append('<input type="radio" name="teacher_id" value="" checked>');
                $form7[0][0].value=js_teachers_array[i][0];
                $form7[0][0].style.display = "none";
                $form7.append(" Teacher with ID: " + js_teachers_array[i][0] + "--->");
                $form7.append('<input type="text" name="teacher_name" size=20 value="">');
                $form7[0][1].value=js_teachers_array[i][2];
                $form7.append('<input type="text" name="teacher_conthours" size=2 value="">');
                $form7[0][2].value=js_teachers_array[i][3];
                $form7.append('<input type="text" name="teacher_hpd" size=2 value="">');
                $form7[0][3].value=js_teachers_array[i][4];
                $form7.append('<input type="text" name="teacher_dpw" size=2 value="">');
                $form7[0][4].value=js_teachers_array[i][5];
                $form7.append('<input type="submit" name="submit7" value="Save">');
                $form7.append('<input type="submit" name="delete7" value="Delete">');
                divid = "restr7" + js_teachers_array[i][0];
                var myDivElement = $('<div>', {id:divid});
                myDivElement.appendTo($form7);
                $('body').append($form7);
                for(j=0;j<M;j++){
                    if(js_teachers_array[i][0]===js_teacher_restrictions_array[j][1]){
                        $form77 = $("<form></form>");
                        $form77.attr('action', "changes.php");
                        $form77.attr('method', "post");
                        $form77.append('<input type="radio" name="teacher_restriction_id" value="" checked>');
                        $form77[0][0].value=js_teacher_restrictions_array[j][0];
                        $form77[0][0].style.display = "none";
                        $form77.append('<input type="radio" name="teacher_id" value="" checked>');
                        $form77[0][1].value=js_teacher_restrictions_array[j][1];
                        $form77[0][1].style.display = "none";
                        $form77.append(" --- Teacher has restriction for day and hour--->");
                        $form77.append('<input type="text" name="teacher_restriction_day" size=2 value="">');
                        $form77[0][2].value=js_teacher_restrictions_array[j][3];
                        $form77.append('<input type="text" name="teacher_restriction_hour" size=2 value="">');
                        $form77[0][3].value=js_teacher_restrictions_array[j][4];
                        $form77.append('<input type="submit" name="delete77" value="Delete">');
                        myDivElement.append($form77);
                    }
                }
            }
            $div7 = $("<div id='div7'></div>");
            $('body').append($div7);
            $button7 = $("<button id='add7'>Add Teacher</button>");
            $('body').append($button7);
            $('body').append('<input type="submit" name="addrestr7" value="Add Teacher Restriction" id="add77">');
        </script>
        <br /><br /><br /><br /><br />
        
        <p>Subject configuration area: Colorgroup, Printgroup, Code, Name, Students, Hours, Availability, Description</p>
        <script type='text/javascript'>
            var js_subjects_array = <?php echo json_encode($subjects_array); ?>;
            var js1 = <?php echo json_encode($subjtchr_array); ?>;
            var js2 = <?php echo json_encode($tc_array); ?>;
            var js3 = <?php echo json_encode($shcp_array); ?>;
            var js4 = <?php echo json_encode($subject_restrictions_array); ?>;
            var i,j,k,l,m,divid1,divid2,divid3,divid4;
            var N = <?php echo $count_subjects;?>;
            var M1 = <?php echo $count_subjtchr;?>;
            var M2 = <?php echo $count_tc;?>;
            var M3 = <?php echo $count_shcp;?>;
            var M4 = <?php echo $count_subject_restrictions;?>;
            
            for(i=0;i<N;i++){
                $form8 = $("<form></form>");
                $form8.attr('action', "changes.php");
                $form8.attr('method', "post");
                $form8.append('<input type="radio" name="subject_id" value="" checked>');
                $form8[0][0].value=js_subjects_array[i][0];
                $form8[0][0].style.display = "none";
                $form8.append(" Subject with ID: " + js_subjects_array[i][0] + "--->");
                $form8.append('<input type="text" name="subject_colorgroup" size=2 value="">');
                $form8[0][1].value=js_subjects_array[i][2];
                $form8.append('<input type="text" name="subject_printgroup" size=2 value="">');
                $form8[0][2].value=js_subjects_array[i][3];
                $form8.append('<input type="text" name="subject_code" size=15 value="">');
                $form8[0][3].value=js_subjects_array[i][4];
                $form8.append('<input type="text" name="subject_name" size=90 value="">');
                $form8[0][4].value=js_subjects_array[i][5];
                $form8.append('<input type="text" name="subject_students" size=2 value="">');
                $form8[0][5].value=js_subjects_array[i][6];
                $form8.append('<input type="text" name="subject_hours" size=14 value="">');
                $form8[0][6].value=js_subjects_array[i][7];
                $form8.append('<input type="text" name="subject_availability" size=2 value="">');
                $form8[0][7].value=js_subjects_array[i][8];
                $form8.append('<input type="text" name="subject_semister" size=35 value="">');
                $form8[0][8].value=js_subjects_array[i][9];
                $form8.append('<input type="submit" name="submit8" value="Save">');
                $form8.append('<input type="submit" name="delete8" value="Delete">');
                divid1 = "restr8_1_" + js_subjects_array[i][0];
                var myDivElement1 = $('<div>', {id:divid1});
                myDivElement1.appendTo($form8);
                divid2 = "restr8_2_" + js_subjects_array[i][0];
                var myDivElement2 = $('<div>', {id:divid2});
                myDivElement2.appendTo($form8);
                divid3 = "restr8_3_" + js_subjects_array[i][0];
                var myDivElement3 = $('<div>', {id:divid3});
                myDivElement3.appendTo($form8);
                divid4 = "restr8_4_" + js_subjects_array[i][0];
                var myDivElement4 = $('<div>', {id:divid4});
                myDivElement4.appendTo($form8);
                $('body').append($form8);
                for(j=0;j<M1;j++){
                    if(js_subjects_array[i][0]===js1[j][0]){
                        $form88_1 = $("<form></form>");
                        $form88_1.attr('action', "changes.php");
                        $form88_1.attr('method', "post");
                        $form88_1.append('<input type="radio" name="subject_id" value="" checked>');
                        $form88_1[0][0].value=js1[j][0];
                        $form88_1[0][0].style.display = "none";
                        $form88_1.append('<input type="radio" name="teacher_id" value="" checked>');
                        $form88_1[0][1].value=js1[j][1];
                        $form88_1[0][1].style.display = "none";
                        $form88_1.append(" * Subject has teacher: ");
                        $form88_1.append('<input type="text" name="teacher_name" size=20 value="" checked>');
                        $form88_1[0][2].value=js1[j][2];
                        $form88_1.append('<input type="submit" name="delete88_1" value="Delete">');
                        myDivElement1.append($form88_1);
                    }
                }
                for(k=0;k<M2;k++){
                    if(js_subjects_array[i][0]===js2[k][1]){
                        $form88_2 = $("<form></form>");
                        $form88_2.attr('action', "changes.php");
                        $form88_2.attr('method', "post");
                        $form88_2.append('<input type="radio" name="tc_id" value="" checked>');
                        $form88_2[0][0].value=js2[k][0];
                        $form88_2[0][0].style.display = "none";
                        $form88_2.append('<input type="radio" name="tc_subject_id" value="" checked>');
                        $form88_2[0][1].value=js2[k][1];
                        $form88_2[0][1].style.display = "none";
                        $form88_2.append(" *** Subject is provided in time and classroom: ");
                        $form88_2.append('<input type="text" name="tc_time" size=2 value="">');
                        $form88_2[0][2].value=js2[k][3];
                        $form88_2.append('<input type="text" name="tc_classroom" size=2 value="">');
                        $form88_2[0][3].value=js2[k][4];
                        $form88_2.append('<input type="submit" name="delete88_2" value="Delete">');
                        myDivElement2.append($form88_2);
                    }
                }
                for(l=0;l<M3;l++){
                    if(js_subjects_array[i][0]===js3[l][1]){
                        $form88_3 = $("<form></form>");
                        $form88_3.attr('action', "changes.php");
                        $form88_3.attr('method', "post");
                        $form88_3.append('<input type="radio" name="shcp_id" value="" checked>');
                        $form88_3[0][0].value=js3[l][0];
                        $form88_3[0][0].style.display = "none";
                        $form88_3.append('<input type="radio" name="subject_id" value="" checked>');
                        $form88_3[0][1].value=js3[l][1];
                        $form88_3[0][1].style.display = "none";
                        $form88_3.append(" ***** Subject has classroom preference: ");
                        $form88_3.append('<input type="text" name="classroom_id" size=2 value="">');
                        $form88_3[0][2].value=js3[l][3];
                        $form88_3.append('<input type="submit" name="delete88_3" value="Delete">');
                        myDivElement3.append($form88_3);
                    }
                }
                for(m=0;m<M4;m++){
                    if(js_subjects_array[i][0]===js4[m][1]){
                        $form88_4 = $("<form></form>");
                        $form88_4.attr('action', "changes.php");
                        $form88_4.attr('method', "post");
                        $form88_4.append('<input type="radio" name="subject_restr_id" value="" checked>');
                        $form88_4[0][0].value=js4[m][0];
                        $form88_4[0][0].style.display = "none";
                        $form88_4.append('<input type="radio" name="subject_id" value="" checked>');
                        $form88_4[0][1].value=js4[m][1];
                        $form88_4[0][1].style.display = "none";
                        $form88_4.append(" ******* Subject has restriction for day and hour--->");
                        $form88_4.append('<input type="text" name="subject_restriction_day" size=2 value="">');
                        $form88_4[0][2].value=js4[m][3];
                        $form88_4.append('<input type="text" name="subject_restriction_hour" size=2 value="">');
                        $form88_4[0][3].value=js4[m][4];
                        $form88_4.append('<input type="submit" name="delete88_4" value="Delete">');
                        myDivElement4.append($form88_4);
                    }
                }
                $('body').append("<br />");
            }
            $div8 = $("<div id='div8'></div>");
            $('body').append($div8);
            $('body').append('<input type="submit" name="add8" value="Add Subject" id="add8">');
            $('body').append('<input type="submit" name="add8_1" value="Add Teacher to Subject" id="add8_1">');
            $('body').append('<input type="submit" name="add8_2" value="Add Time/Classroom" id="add8_2">');
            $('body').append('<input type="submit" name="add8_3" value="Add Classroom Preference" id="add8_3">');
            $('body').append('<input type="submit" name="add8_4" value="Add Subject Restriction" id="add8_4">');
        </script>
        <br /><br /><br /><br /><br />
        
        <p>Cgroup configuration area: Name, Multiplier</p>
        <script type='text/javascript'>
            var js_cgroup_array = <?php echo json_encode($cgroup_array); ?>;
            var js_cgroup_has_subj_array = <?php echo json_encode($cgroup_has_subj_array); ?>;
            var i,j,divid;
            var N = <?php echo $count_cgroup;?>;
            var M = <?php echo $count_cgroup_has_subj;?>;
            
            for(i=0;i<N;i++){        
                $form9 = $("<form></form>");
                $form9.attr('action', "changes.php");
                $form9.attr('method', "post");
                $form9.append('<input type="radio" name="cgroup_id" value="" checked>');
                $form9[0][0].value=js_cgroup_array[i][0];
                $form9[0][0].style.display = "none";
                $form9.append(" Cgroup with ID: " + js_cgroup_array[i][0] + "--->");
                $form9.append('<input type="text" name="cgroup_name" size=20 value="">');
                $form9[0][1].value=js_cgroup_array[i][2];
                $form9.append('<input type="text" name="cgroup_multiplier" size=2 value="">');
                $form9[0][2].value=js_cgroup_array[i][3];
                $form9.append('<input type="submit" name="submit9" value="Save">');
                $form9.append('<input type="submit" name="delete9" value="Delete">');
                divid = "cont9_" + js_cgroup_array[i][0];
                var myDivElement = $('<div>', {id:divid});
                myDivElement.appendTo($form9);
                $('body').append($form9);
                for(j=0;j<M;j++){
                    if(js_cgroup_array[i][0]===js_cgroup_has_subj_array[j][0]){
                        $form99 = $("<form></form>");
                        $form99.attr('action', "changes.php");
                        $form99.attr('method', "post");
                        $form99.append('<input type="radio" name="cgroup_id" value="" checked>');
                        $form99.append("* Subject: ");
                        $form99[0][0].value=js_cgroup_has_subj_array[j][0];
                        $form99[0][0].style.display = "none";
                        $form99.append('<input type="text" name="subject_id" size=2 value="">');
                        $form99[0][1].value=js_cgroup_has_subj_array[j][1];
                        $form99.append('<input type="text" name="subject_name" size=90 value="">');
                        $form99[0][2].value=js_cgroup_has_subj_array[j][3];
                        $form99.append('<input type="submit" name="delete99" value="Delete">');
                        myDivElement.append($form99);
                    }
                }
                $('body').append("<br />");
            }
            $div9 = $("<div id='div9'></div>");
            $('body').append($div9);
            $button9 = $("<button id='add9'>Add Cgroup</button>");
            $('body').append($button9);
            $('body').append('<input type="submit" name="add99" value="Add Subject to Cgroup" id="add99">');
        </script>
        <br /><br /><br /><br /><br />
        
        <p>Dgroup configuration area: Name</p>
        <script type='text/javascript'>
            var js_dgroup_array = <?php echo json_encode($dgroup_array); ?>;
            var js_dgroup_has_subj_array = <?php echo json_encode($dgroup_has_subj_array); ?>;
            var i,j,divid;
            var N = <?php echo $count_dgroup;?>;
            var M = <?php echo $count_dgroup_has_subj;?>;
            
            for(i=0;i<N;i++){        
                $form10 = $("<form></form>");
                $form10.attr('action', "changes.php");
                $form10.attr('method', "post");
                $form10.append('<input type="radio" name="dgroup_id" value="" checked>');
                $form10[0][0].value=js_dgroup_array[i][0];
                $form10[0][0].style.display = "none";
                $form10.append(" Dgroup with ID: " + js_dgroup_array[i][0] + "--->");
                $form10.append('<input type="text" name="dgroup_name" size=20 value="">');
                $form10[0][1].value=js_dgroup_array[i][2];
                $form10.append('<input type="submit" name="submit10" value="Save">');
                $form10.append('<input type="submit" name="delete10" value="Delete">');
                divid = "cont10_" + js_dgroup_array[i][0];
                var myDivElement = $('<div>', {id:divid});
                myDivElement.appendTo($form10);
                $('body').append($form10);
                for(j=0;j<M;j++){
                    if(js_dgroup_array[i][0]===js_dgroup_has_subj_array[j][0]){
                        $form10_10 = $("<form></form>");
                        $form10_10.attr('action', "changes.php");
                        $form10_10.attr('method', "post");
                        $form10_10.append('<input type="radio" name="dgroup_id" value="" checked>');
                        $form10_10.append("* Subject: ");
                        $form10_10[0][0].value=js_dgroup_has_subj_array[j][0];
                        $form10_10[0][0].style.display = "none";
                        $form10_10.append('<input type="text" name="subject_id" size=2 value="">');
                        $form10_10[0][1].value=js_dgroup_has_subj_array[j][1];
                        $form10_10.append('<input type="text" name="subject_name" size=90 value="">');
                        $form10_10[0][2].value=js_dgroup_has_subj_array[j][3];
                        $form10_10.append('<input type="submit" name="delete10_10" value="Delete">');
                        myDivElement.append($form10_10);
                    }
                }
                $('body').append("<br />");
            }
            $div10 = $("<div id='div10'></div>");
            $('body').append($div10);
            $button10 = $("<button id='add10'>Add Dgroup</button>");
            $('body').append($button10);
            $('body').append('<input type="submit" name="add10_10" value="Add Subject to Dgroup" id="add10_10">');
        </script>
        <br /><br /><br /><br /><br />
        
        <p>Colorgroup configuration area: Name, Color</p>
        <script type='text/javascript'>
            var js_colorgroup_array = <?php echo json_encode($colorgroup_array); ?>;
            var i;
            var N = <?php echo $count_colorgroup;?>;
            
            for(i=0;i<N;i++){        
                $form11 = $("<form></form>");
                $form11.attr('action', "changes.php");
                $form11.attr('method', "post");
                $form11.append('<input type="radio" name="colorgroup_id" value="" checked>');
                $form11[0][0].value=js_colorgroup_array[i][0];
                $form11[0][0].style.display = "none";
                $form11.append(" Colorgroup with ID: " + js_colorgroup_array[i][0] + "--->");
                $form11.append('<input type="text" name="colorgroup_name" placeholder="Name" size=20 value="">');
                $form11[0][1].value=js_colorgroup_array[i][2];
                $form11.append('<input type="text" name="colorgroup_color" placeholder="Color" size=20 value="">');
                $form11[0][2].value=js_colorgroup_array[i][3];
                $form11.append('<input type="submit" name="submit11" value="Save">');
                $form11.append('<input type="submit" name="delete11" value="Delete">');
                $('body').append($form11);
            }
            $div11 = $("<div id='div11'></div>");
            $('body').append($div11);
            $button11 = $("<button id='add11'>Add Colorgroup</button>");
            $('body').append($button11);
        </script>
        <br /><br /><br /><br /><br />
        
        <p>Printgroup configuration area: Name, Number</p>
        <script type='text/javascript'>
            var js_printgroup_array = <?php echo json_encode($printgroup_array); ?>;
            var i;
            var N = <?php echo $count_printgroup;?>;
            
            for(i=0;i<N;i++){        
                $form12 = $("<form></form>");
                $form12.attr('action', "changes.php");
                $form12.attr('method', "post");
                $form12.append('<input type="radio" name="printgroup_id" value="" checked>');
                $form12[0][0].value=js_printgroup_array[i][0];
                $form12[0][0].style.display = "none";
                $form12.append(" Printgroup with ID: " + js_printgroup_array[i][0] + "--->");
                $form12.append('<input type="text" name="printgroup_name" placeholder="Name" size=20 value="">');
                $form12[0][1].value=js_printgroup_array[i][2];
                $form12.append('<input type="text" name="printgroup_color" placeholder="Number" size=6 value="">');
                $form12[0][2].value=js_printgroup_array[i][3];
                $form12.append('<input type="submit" name="submit12" value="Save">');
                $form12.append('<input type="submit" name="delete12" value="Delete">');
                $('body').append($form12);
            }
            $div12 = $("<div id='div12'></div>");
            $('body').append($div12);
            $button12 = $("<button id='add12'>Add Printgroup</button>");
            $('body').append($button12);
        </script>
        <br /><br /><br /><br /><br />
        
        <p>Equal configuration area: Subject 1, Subject 2</p>
        <script type='text/javascript'>
            var js_equal_array = <?php echo json_encode($equal_array); ?>;
            var js_subjects_array = <?php echo json_encode($subjects_array); ?>;
            var i,j,s;
            var N = <?php echo $count_equal;?>;
            
            for(i=0;i<N;i++){        
                $form13 = $("<form></form>");
                $form13.attr('action', "changes.php");
                $form13.attr('method', "post");
                $form13.append('<input type="radio" name="equal_id" value="" checked>');
                $form13[0][0].value=js_equal_array[i][0];
                $form13[0][0].style.display = "none";
                $form13.append(" Equal with ID: " + js_equal_array[i][0] + "--->");
                $form13.append('<input type="text" name="equal_1" placeholder="Subject ID 1" size=80 value="">');
                for(j=0;j<500;j++){
                    if(js_equal_array[i][2]===js_subjects_array[j][0]){
                        s = js_subjects_array[j][5];
                        break;
                    }
                }
                $form13[0][1].value=js_equal_array[i][2] + "-" +s;
                $form13.append('<input type="text" name="equal_2" placeholder="Subject ID 2" size=80 value="">');
                for(j=0;j<500;j++){
                    if(js_equal_array[i][3]===js_subjects_array[j][0]){
                        s = js_subjects_array[j][5];
                        break;
                    }
                }
                $form13[0][2].value=js_equal_array[i][3] + "-" +s;
                $form13.append('<input type="submit" name="delete13" value="Delete">');
                $('body').append($form13);
            }
            $div13 = $("<div id='div13'></div>");
            $('body').append($div13);
            $button13 = $("<button id='add13'>Add Equal</button>");
            $('body').append($button13);
        </script>
        <br /><br /><br /><br /><br />
        
        <script>
            $(document).ready(function(){
                $("#add4").click(function(){
                    $form44 = $("<form></form>");
                    $form44.attr('action', "changes.php");
                    $form44.attr('method', "post");
                    $form44.append(" Dayname with name: ");
                    $form44.append('<input type="text" name="dayname_name" placeholder="Name" size=20 value="">');
                    $form44.append('<input type="submit" name="submit44" value="Add New Dayname">');
                    $("#div4").append($form44);
                });
            });
            
            $(document).ready(function(){
                $("#add5").click(function(){
                    $form55 = $("<form></form>");
                    $form55.attr('action', "changes.php");
                    $form55.attr('method', "post");
                    $form55.append(" Hourname with name: ");
                    $form55.append('<input type="text" name="hourname_name" placeholder="Name" size=20 value="">');
                    $form55.append('<input type="submit" name="submit55" value="Add New Hourname">');
                    $("#div5").append($form55);
                });
            });
            
            $(document).ready(function(){
                $("#add6").click(function(){
                    $form666 = $("<form></form>");
                    $form666.attr('action', "changes.php");
                    $form666.attr('method', "post");
                    $form666.append(" Classroom with name and capacity: ");
                    $form666.append('<input type="text" name="classroom_name" placeholder="Name" size=20 value="">');
                    $form666.append('<input type="text" name="classroom_capacity" placeholder="#" size=2 value="">');
                    $form666.append('<input type="submit" name="submit666" value="Add New Classroom">');
                    $("#div6").append($form666);
                });
            });
            
            $(document).ready(function(){
                $("#add66").click(function(){
                    $form6666 = $("<form></form>");
                    $form6666.attr('action', "changes.php");
                    $form6666.attr('method', "post");
                    $form6666.append(" --- Restriction for classroom with ID: ");
                    
                    <?php
                        $temp0 = "<select name='classroom_id'>";
                        $tt = $_SESSION['id_timetable'];
                        $result0 = mysqli_query($db, "SELECT idClassroom, Name FROM classroom WHERE Timetable_idTimetable='$tt' ORDER BY idClassroom;");
                        while ($row0 = mysqli_fetch_array($result0)) {
                            $temp0 = $temp0 . "<option>" . $row0['idClassroom'] . "-" . $row0['Name'] . "</option>";
                        }
                        $temp0 = $temp0 . "</select>";
                    ?>
                    $temp00 = "<?php echo $temp0; ?>";
                    $form6666.append($temp00);
                    
                    $form6666.append(" for day and hour--->");
                                        
                    <?php
                        $temp1 = "<select name='classroom_restriction_day'>";
                        $i=1;
                        $tt = $_SESSION['id_timetable'];
                        $d=mysqli_fetch_array(mysqli_query($db, "SELECT Days FROM timetable WHERE idTimetable='$tt';"));
                        while ($i<=$d['Days'] ) {
                            $temp1 = $temp1 . "<option>" . $i . "</option>";
                            $i++;
                        }
                        $temp1 = $temp1 . "</select>";
                    ?>
                    $temp11 = "<?php echo $temp1; ?>";
                    $form6666.append($temp11);
                    
                    <?php
                        $temp2 = "<select name='classroom_restriction_hour'>";
                        $i=1;
                        $tt = $_SESSION['id_timetable'];
                        $h=mysqli_fetch_array(mysqli_query($db, "SELECT Hours FROM timetable WHERE idTimetable='$tt';"));
                        while ($i<=$h['Hours'] ) {
                            $temp2 = $temp2 . "<option>" . $i . "</option>";
                            $i++;
                        }
                        $temp2 = $temp2 . "</select>";
                    ?>
                    $temp22 = "<?php echo $temp2; ?>";
                    $form6666.append($temp22);
                    
                    $form6666.append('<input type="submit" name="submit6666" value="Add New Classroom Restriction">');
                    $("#div6").append($form6666);
                });
            });
            
            $(document).ready(function(){
                $("#add7").click(function(){
                    $form777 = $("<form></form>");
                    $form777.attr('action', "changes.php");
                    $form777.attr('method', "post");
                    $form777.append(" Teacher with name, ch, hpd, dpw: ");
                    $form777.append('<input type="text" name="teacher_name" placeholder="Name" size=20 value="">');
                    $form777.append('<input type="text" name="teacher_conthours" placeholder="CH" size=2 value="">');
                    $form777.append('<input type="text" name="teacher_hpd" placeholder="HPD" size=2 value="">');
                    $form777.append('<input type="text" name="teacher_dpw" placeholder="DPW" size=2 value="">');
                    $form777.append('<input type="submit" name="submit777" value="Add New Teacher">');
                    $("#div7").append($form777);
                });
            });
            
            $(document).ready(function(){
                $("#add77").click(function(){
                    $form7777 = $("<form></form>");
                    $form7777.attr('action', "changes.php");
                    $form7777.attr('method', "post");
                    $form7777.append(" --- Restriction for teacher with ID: ");
                    
                    <?php
                        $temp0 = "<select name='teacher_id'>";
                        $tt = $_SESSION['id_timetable'];
                        $result0 = mysqli_query($db, "SELECT idTeacher, Name FROM teacher WHERE Timetable_idTimetable='$tt' ORDER BY Name;");
                        while ($row0 = mysqli_fetch_array($result0)) {
                            $temp0 = $temp0 . "<option>" . $row0['idTeacher'] . "-" . $row0['Name'] . "</option>";
                        }
                        $temp0 = $temp0 . "</select>";
                    ?>
                    $temp00 = "<?php echo $temp0; ?>";
                    $form7777.append($temp00);
                    
                    $form7777.append(" for day and hour--->");
                    
                    <?php
                        $temp1 = "<select name='teacher_restriction_day'>";
                        $i=1;
                        $tt = $_SESSION['id_timetable'];
                        $d=mysqli_fetch_array(mysqli_query($db, "SELECT Days FROM timetable WHERE idTimetable='$tt';"));
                        while ($i<=$d['Days'] ) {
                            $temp1 = $temp1 . "<option>" . $i . "</option>";
                            $i++;
                        }
                        $temp1 = $temp1 . "</select>";
                    ?>
                    $temp11 = "<?php echo $temp1; ?>";
                    $form7777.append($temp11);
                    
                    <?php
                        $temp2 = "<select name='teacher_restriction_hour'>";
                        $i=1;
                        $tt = $_SESSION['id_timetable'];
                        $h=mysqli_fetch_array(mysqli_query($db, "SELECT Hours FROM timetable WHERE idTimetable='$tt';"));
                        while ($i<=$h['Hours'] ) {
                            $temp2 = $temp2 . "<option>" . $i . "</option>";
                            $i++;
                        }
                        $temp2 = $temp2 . "</select>";
                    ?>
                    $temp22 = "<?php echo $temp2; ?>";
                    $form7777.append($temp22);
                    
                    $form7777.append('<input type="submit" name="submit7777" value="Add New Teacher Restriction">');
                    $("#div7").append($form7777);
                });
            });
            
            $(document).ready(function(){
                $("#add8").click(function(){
                    $form888 = $("<form></form>");
                    $form888.attr('action', "changes.php");
                    $form888.attr('method', "post");
                    $form888.append("Subject with colorgroup, printgroup, code, name, students, hours, availability, description: ");
                                        
                    <?php
                        $temp0 = "<select name='subject_colorgroup'>";
                        $tt = $_SESSION['id_timetable'];
                        $result0 = mysqli_query($db, "SELECT idColorgroup, Name FROM colorgroup WHERE Timetable_idTimetable='$tt' ORDER BY idColorgroup;");
                        while ($row0 = mysqli_fetch_array($result0)) {
                            $temp0 = $temp0 . "<option>" . $row0['idColorgroup'] . "-" . $row0['Name'] . "</option>";
                        }
                        $temp0 = $temp0 . "</select>";
                    ?>
                    $temp00 = "<?php echo $temp0; ?>";
                    $form888.append($temp00);
                    
                    <?php
                        $temp0 = "<select name='subject_printgroup'>";
                        $tt = $_SESSION['id_timetable'];
                        $result0 = mysqli_query($db, "SELECT idPrintgroup, Name FROM printgroup WHERE Timetable_idTimetable='$tt' ORDER BY idPrintgroup;");
                        while ($row0 = mysqli_fetch_array($result0)) {
                            $temp0 = $temp0 . "<option>" . $row0['idPrintgroup'] . "-" . $row0['Name'] . "</option>";
                        }
                        $temp0 = $temp0 . "</select>";
                    ?>
                    $temp00 = "<?php echo $temp0; ?>";
                    $form888.append($temp00);
                    
                    $form888.append('<input type="text" name="subject_code" placeholder="Code" size=12 value="">');
                    $form888.append('<input type="text" name="subject_name" placeholder="Name" size=90 value="">');
                    $form888.append('<input type="text" name="subject_students" placeholder="Students" size=6 value="">');
                    $form888.append('<input type="text" name="subject_hours" placeholder="Hours" size=14 value="">');
                    $form888.append('<input type="text" name="subject_availability" placeholder="Availability(0,1,3)" size=14 value="">');
                    $form888.append('<input type="text" name="subject_semister" placeholder="Description" size=35 value="">');
                    $form888.append('<input type="submit" name="submit888" value="Add New Subject">');
                    $("#div8").append($form888);
                });
            });
            
            $(document).ready(function(){
                $("#add8_1").click(function(){
                    $form888_1 = $("<form></form>");
                    $form888_1.attr('action', "changes.php");
                    $form888_1.attr('method', "post");
                    $form888_1.append("Add Teacher with ID: ");
                                        
                    <?php
                        $temp0 = "<select name='teacher_id'>";
                        $tt = $_SESSION['id_timetable'];
                        $result0 = mysqli_query($db, "SELECT idTeacher, Name FROM teacher WHERE Timetable_idTimetable='$tt' ORDER BY Name;");
                        while ($row0 = mysqli_fetch_array($result0)) {
                            $temp0 = $temp0 . "<option>" . $row0['idTeacher'] . "-" . $row0['Name'] . "</option>";
                        }
                        $temp0 = $temp0 . "</select>";
                    ?>
                    $temp00 = "<?php echo $temp0; ?>";
                    $form888_1.append($temp00);
                    
                    $form888_1.append(" to Subject with ID: ");
                                        
                    <?php
                        $temp0 = "<select name='subject_id'>";
                        $tt = $_SESSION['id_timetable'];
                        $result0 = mysqli_query($db, "SELECT idSubject, Name FROM subject WHERE Timetable_idTimetable='$tt' ORDER BY Name;");
                        while ($row0 = mysqli_fetch_array($result0)) {
                            $temp0 = $temp0 . "<option>" . $row0['idSubject'] . "-" . $row0['Name'] . "</option>";
                        }
                        $temp0 = $temp0 . "</select>";
                    ?>
                    $temp00 = "<?php echo $temp0; ?>";
                    $form888_1.append($temp00);
                    
                    $form888_1.append('<input type="submit" name="submit888_1" value="Add New Teacher to Subject">');
                    $("#div8").append($form888_1);
                });
            });
            
            $(document).ready(function(){
                $("#add8_2").click(function(){
                    $form888_2 = $("<form></form>");
                    $form888_2.attr('action', "changes.php");
                    $form888_2.attr('method', "post");
                    $form888_2.append("Add Time/Classroom for duration: ");
                                        
                    <?php
                        $temp2 = "<select name='subject_duration'>";
                        $i=1;
                        $tt = $_SESSION['id_timetable'];
                        $h=mysqli_fetch_array(mysqli_query($db, "SELECT Hours FROM timetable WHERE idTimetable='$tt';"));
                        while ($i<=$h['Hours'] ) {
                            $temp2 = $temp2 . "<option>" . $i . "</option>";
                            $i++;
                        }
                        $temp2 = $temp2 . "</select>";
                    ?>
                    $temp22 = "<?php echo $temp2; ?>";
                    $form888_2.append($temp22);
                    
                    $form888_2.append(" for Subject with ID: ");
                                        
                    <?php
                        $temp0 = "<select name='subject_id'>";
                        $tt = $_SESSION['id_timetable'];
                        $result0 = mysqli_query($db, "SELECT idSubject, Name FROM subject WHERE Timetable_idTimetable='$tt' ORDER BY Name;");
                        while ($row0 = mysqli_fetch_array($result0)) {
                            $temp0 = $temp0 . "<option>" . $row0['idSubject'] . "-" . $row0['Name'] . "</option>";
                        }
                        $temp0 = $temp0 . "</select>";
                    ?>
                    $temp00 = "<?php echo $temp0; ?>";
                    $form888_2.append($temp00);
                    
                    $form888_2.append('<input type="submit" name="submit888_2" value="Add New Time/Classroom">');                    
                    $("#div8").append($form888_2);
                });
            });
            
            $(document).ready(function(){
                $("#add8_3").click(function(){
                    $form888_3 = $("<form></form>");
                    $form888_3.attr('action', "changes.php");
                    $form888_3.attr('method', "post");
                    $form888_3.append("Add Classroom Preference, insert the classroom's ID: ");
                                        
                    <?php
                        $temp0 = "<select name='subject_classroompreference'>";
                        $tt = $_SESSION['id_timetable'];
                        $result0 = mysqli_query($db, "SELECT idClassroom, Name FROM classroom WHERE Timetable_idTimetable='$tt' ORDER BY Name;");
                        while ($row0 = mysqli_fetch_array($result0)) {
                            $temp0 = $temp0 . "<option>" . $row0['idClassroom'] . "-" . $row0['Name'] . "</option>";
                        }
                        $temp0 = $temp0 . "</select>";
                    ?>
                    $temp00 = "<?php echo $temp0; ?>";
                    $form888_3.append($temp00);
                    
                    $form888_3.append(" for Subject with ID: ");
                                        
                    <?php
                        $temp0 = "<select name='subject_id'>";
                        $tt = $_SESSION['id_timetable'];
                        $result0 = mysqli_query($db, "SELECT idSubject, Name FROM subject WHERE Timetable_idTimetable='$tt' ORDER BY Name;");
                        while ($row0 = mysqli_fetch_array($result0)) {
                            $temp0 = $temp0 . "<option>" . $row0['idSubject'] . "-" . $row0['Name'] . "</option>";
                        }
                        $temp0 = $temp0 . "</select>";
                    ?>
                    $temp00 = "<?php echo $temp0; ?>";
                    $form888_3.append($temp00);
                    
                    $form888_3.append('<input type="submit" name="submit888_3" value="Add New Classroom Preference">');                    
                    $("#div8").append($form888_3);
                });
            });
            
            $(document).ready(function(){
                $("#add8_4").click(function(){
                    $form888_4 = $("<form></form>");
                    $form888_4.attr('action', "changes.php");
                    $form888_4.attr('method', "post");
                    $form888_4.append(" --- Restriction for subject with ID: ");
                    
                    <?php
                        $temp0 = "<select name='subject_id'>";
                        $tt = $_SESSION['id_timetable'];
                        $result0 = mysqli_query($db, "SELECT idSubject, Name FROM subject WHERE Timetable_idTimetable='$tt' ORDER BY Name;");
                        while ($row0 = mysqli_fetch_array($result0)) {
                            $temp0 = $temp0 . "<option>" . $row0['idSubject'] . "-" . $row0['Name'] . "</option>";
                        }
                        $temp0 = $temp0 . "</select>";
                    ?>
                    $temp00 = "<?php echo $temp0; ?>";
                    $form888_4.append($temp00);
                    
                    $form888_4.append(" for day and hour--->");
                                        
                    <?php
                        $temp1 = "<select name='subject_restriction_day'>";
                        $i=1;
                        $tt = $_SESSION['id_timetable'];
                        $d=mysqli_fetch_array(mysqli_query($db, "SELECT Days FROM timetable WHERE idTimetable='$tt';"));
                        while ($i<=$d['Days'] ) {
                            $temp1 = $temp1 . "<option>" . $i . "</option>";
                            $i++;
                        }
                        $temp1 = $temp1 . "</select>";
                    ?>
                    $temp11 = "<?php echo $temp1; ?>";
                    $form888_4.append($temp11);
                    
                    <?php
                        $temp2 = "<select name='subject_restriction_hour'>";
                        $i=1;
                        $tt = $_SESSION['id_timetable'];
                        $h=mysqli_fetch_array(mysqli_query($db, "SELECT Hours FROM timetable WHERE idTimetable='$tt';"));
                        while ($i<=$h['Hours'] ) {
                            $temp2 = $temp2 . "<option>" . $i . "</option>";
                            $i++;
                        }
                        $temp2 = $temp2 . "</select>";
                    ?>
                    $temp22 = "<?php echo $temp2; ?>";
                    $form888_4.append($temp22);
                    
                    $form888_4.append('<input type="submit" name="submit888_4" value="Add New Subject Restriction">');
                    $("#div8").append($form888_4);
                });
            });
            
            $(document).ready(function(){
                $("#add9").click(function(){
                    $form999 = $("<form></form>");
                    $form999.attr('action', "changes.php");
                    $form999.attr('method', "post");
                    $form999.append(" Cgroup with name: ");
                    $form999.append('<input type="text" name="cgroup_name" placeholder="Name" size=20 value="">');
                    $form999.append(" and multiplier: ");
                    $form999.append('<input type="text" name="cgroup_multiplier" placeholder="Multiplier" size=6 value="">');
                    $form999.append('<input type="submit" name="submit999" value="Add New Cgroup">');
                    $("#div9").append($form999);
                });
            });
            
            $(document).ready(function(){
                $("#add99").click(function(){
                    $form9999 = $("<form></form>");
                    $form9999.attr('action', "changes.php");
                    $form9999.attr('method', "post");
                    $form9999.append(" Cgroup with ID: ");
                                        
                    <?php
                        $temp0 = "<select name='cgroup_id'>";
                        $tt = $_SESSION['id_timetable'];
                        $result0 = mysqli_query($db, "SELECT idCgroup, Name FROM cgroup WHERE Timetable_idTimetable='$tt' ORDER BY Name;");
                        while ($row0 = mysqli_fetch_array($result0)) {
                            $temp0 = $temp0 . "<option>" . $row0['idCgroup'] . "-" . $row0['Name'] . "</option>";
                        }
                        $temp0 = $temp0 . "</select>";
                    ?>
                    $temp00 = "<?php echo $temp0; ?>";
                    $form9999.append($temp00);
                    
                    $form9999.append(" gets subject with ID: ");
                                        
                    <?php
                        $temp0 = "<select name='subject_id'>";
                        $tt = $_SESSION['id_timetable'];
                        $result0 = mysqli_query($db, "SELECT idSubject, Name FROM subject WHERE Timetable_idTimetable='$tt' ORDER BY Name;");
                        while ($row0 = mysqli_fetch_array($result0)) {
                            $temp0 = $temp0 . "<option>" . $row0['idSubject'] . "-" . $row0['Name'] . "</option>";
                        }
                        $temp0 = $temp0 . "</select>";
                    ?>
                    $temp00 = "<?php echo $temp0; ?>";
                    $form9999.append($temp00);
                    
                    $form9999.append('<input type="submit" name="submit9999" value="Add New Subject to Cgroup">');
                    $("#div9").append($form9999);
                });
            });
            
            $(document).ready(function(){
                $("#add10").click(function(){
                    $form1010 = $("<form></form>");
                    $form1010.attr('action', "changes.php");
                    $form1010.attr('method', "post");
                    $form1010.append(" Dgroup with name: ");
                    $form1010.append('<input type="text" name="dgroup_name" placeholder="Name" size=20 value="">');
                    $form1010.append('<input type="submit" name="submit1010" value="Add New Dgroup">');
                    $("#div10").append($form1010);
                });
            });
            
            $(document).ready(function(){
                $("#add10_10").click(function(){
                    $form101010 = $("<form></form>");
                    $form101010.attr('action', "changes.php");
                    $form101010.attr('method', "post");
                    $form101010.append(" Dgroup with ID: ");
                                        
                    <?php
                        $temp0 = "<select name='dgroup_id'>";
                        $tt = $_SESSION['id_timetable'];
                        $result0 = mysqli_query($db, "SELECT idDgroup, Name FROM dgroup WHERE Timetable_idTimetable='$tt' ORDER BY Name;");
                        while ($row0 = mysqli_fetch_array($result0)) {
                            $temp0 = $temp0 . "<option>" . $row0['idDgroup'] . "-" . $row0['Name'] . "</option>";
                        }
                        $temp0 = $temp0 . "</select>";
                    ?>
                    $temp00 = "<?php echo $temp0; ?>";
                    $form101010.append($temp00);
                    
                    $form101010.append(" gets subject with ID: ");
                                        
                    <?php
                        $temp0 = "<select name='subject_id'>";
                        $tt = $_SESSION['id_timetable'];
                        $result0 = mysqli_query($db, "SELECT idSubject, Name FROM subject WHERE Timetable_idTimetable='$tt' ORDER BY Name;");
                        while ($row0 = mysqli_fetch_array($result0)) {
                            $temp0 = $temp0 . "<option>" . $row0['idSubject'] . "-" . $row0['Name'] . "</option>";
                        }
                        $temp0 = $temp0 . "</select>";
                    ?>
                    $temp00 = "<?php echo $temp0; ?>";
                    $form101010.append($temp00);
                    
                    $form101010.append('<input type="submit" name="submit101010" value="Add New Subject to Dgroup">');
                    $("#div10").append($form101010);
                });
            });
            
            $(document).ready(function(){
                $("#add11").click(function(){
                    $form1111 = $("<form></form>");
                    $form1111.attr('action', "changes.php");
                    $form1111.attr('method', "post");
                    $form1111.append(" Colorgroup with name and color: ");
                    $form1111.append('<input type="text" name="colorgroup_name" placeholder="Name" size=20 value="">');
                    $form1111.append('<input type="text" name="colorgroup_color" placeholder="Color" size=20 value="">');
                    $form1111.append('<input type="submit" name="submit1111" value="Add New Colorgroup">');
                    $("#div11").append($form1111);
                });
            });
            
            $(document).ready(function(){
                $("#add12").click(function(){
                    $form1212 = $("<form></form>");
                    $form1212.attr('action', "changes.php");
                    $form1212.attr('method', "post");
                    $form1212.append(" Printgroup with name and number: ");
                    $form1212.append('<input type="text" name="printgroup_name" placeholder="Name" size=20 value="">');
                    $form1212.append('<input type="text" name="printgroup_color" placeholder="Number" size=6 value="">');
                    $form1212.append('<input type="submit" name="submit1212" value="Add New Printgroup">');
                    $("#div12").append($form1212);
                });
            });
            
            $(document).ready(function(){
                $("#add13").click(function(){
                    $form1313 = $("<form></form>");
                    $form1313.attr('action', "changes.php");
                    $form1313.attr('method', "post");
                    $form1313.append(" Equal subjects: ");
                                        
                    <?php
                        $temp0 = "<select name='equal_1'>";
                        $tt = $_SESSION['id_timetable'];
                        $result0 = mysqli_query($db, "SELECT idSubject, Name FROM subject WHERE Timetable_idTimetable='$tt' ORDER BY Name;");
                        while ($row0 = mysqli_fetch_array($result0)) {
                            $temp0 = $temp0 . "<option>" . $row0['idSubject'] . "-" . $row0['Name'] . "</option>";
                        }
                        $temp0 = $temp0 . "</select>";
                    ?>
                    $temp00 = "<?php echo $temp0; ?>";
                    $form1313.append($temp00);
                                        
                    <?php
                        $temp0 = "<select name='equal_2'>";
                        $tt = $_SESSION['id_timetable'];
                        $result0 = mysqli_query($db, "SELECT idSubject, Name FROM subject WHERE Timetable_idTimetable='$tt' ORDER BY Name;");
                        while ($row0 = mysqli_fetch_array($result0)) {
                            $temp0 = $temp0 . "<option>" . $row0['idSubject'] . "-" . $row0['Name'] . "</option>";
                        }
                        $temp0 = $temp0 . "</select>";
                    ?>
                    $temp0000 = "<?php echo $temp0; ?>";
                    $form1313.append($temp0000);
                    
                    $form1313.append('<input type="submit" name="submit1313" value="Add New Equal">');
                    $("#div13").append($form1313);
                });
            });
        </script>
    </body>
</html>