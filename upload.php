
<!--
http://www.w3schools.com/php/php_file_open.asp
http://stackoverflow.com/questions/7979567/php-convert-any-string-to-utf-8-without-knowing-the-original-character-set-or
http://php.net/manual/en/function.unset.php
-->

<?php
ini_set("max_execution_time", 0);
include('menu.php');

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

// Check if file already exists
if (file_exists($target_file)) {
    echo "<br /><br /><center><b>Sorry, file already exists. </b></center>";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 50000) {
    echo "<br /><br /><center><b>Sorry, your file is too large. </b></center>";
    $uploadOk = 0;
}
// Allow certain file formats
if ($imageFileType != "fct") {
    echo "<br /><br /><center><b>Sorry, only fct files are allowed. </b></center>";
    $uploadOk = 0;
}


// Check if $uploadOk is set to 0 by an error, if everything is ok, try to upload file
if ($uploadOk) {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "<br /><br /><center><b>The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded. </b></center>";
        
        // Open file and copy its content to the variable $text
        chmod($target_file, 0777);
        $myfile = fopen($target_file, "r") or die("Unable to open file!");
        //$text = fread($myfile, filesize("uploads/" . basename($_FILES["fileToUpload"]["name"])));
        $text = fread($myfile, filesize($target_file));
        fclose($myfile);
        //$text = iconv(mb_detect_encoding($text, mb_detect_order(), true),"UTF-8", $text);
        $text = iconv("ISO-8859-7","UTF-8", $text);
                
        // Get the DAYS
        $line = substr($text, 0, 6);  //DAYS 5
        $days = substr($line, 5, 1);  //5
        //echo $days . "<br \>";
        $text = substr($text, 7, strlen($text));
        
        // Get the HOURS
        $line = substr($text, 0, strpos($text,'p')-2);  //HOURS 12
        $hours = substr($line, 6, strlen($line)-6);  //12
        //echo $hours . "<br \>";
        $text = substr($text, strpos($text,'p'), strlen($text));
        
        // Get id of timetable
        $row0 = mysqli_fetch_row(mysqli_query($db, "SELECT idTimetable FROM timetable ORDER BY idTimetable DESC LIMIT 1;"));
        $id_timetable = $row0[0]+1;
        
        // Get the rest variables
        while(strlen($text)>0) {
            $line = substr($text, 0, strpos($text,').')+2);
            
            // Get the PROBLEMNAME
            if(substr($line, 0, 5) == "probl")
            {
                $problemname = substr($line, 13, strlen($line)-16);  //Χειμερινό εξάμηνο 2015-16
                //echo $problemname . "<br \>";
                
                $result0 = mysqli_query($db, "SELECT Name FROM timetable;");
                $count_tname = mysqli_num_rows($result0);
                for($i=0;$i<$count_tname;$i++){
                    if($problemname == mysqli_fetch_row($result0)[0]){
                        $problemname = $problemname . "_new";
                    }
                }
                
                $sql = "INSERT INTO timetable ".
                       "(idTimetable,Admin_idAdmin,Days,Hours,Name) ".
                       "VALUES ('$id_timetable',1,'$days','$hours','$problemname');";
                $result = mysqli_query($db, $sql);    
            }
            
            // Get the DAYNAMEs
            if(substr($line, 0, 5) == "dayna")
            {
                $dayname_id = substr($line, 8, 1);  //1
                //echo $dayname_id . "--";
                $dayname_name = substr($line, strpos($line, ",")+2, strpos($line, ")")-strpos($line, ",")-3);  //Δευτέρα
                //echo $dayname_name . "<br \>";
                $sql = "INSERT INTO dayname ".
                       "(idDayname,Timetable_idTimetable,Name) ".
                       "VALUES ('$dayname_id','$id_timetable','$dayname_name');";
                $result = mysqli_query($db, $sql);
            }
            
            // Get the HOURNAMEs
            if(substr($line, 0, 5) == "hourn")
            {
                $hourname_id = substr($line, 9, strpos($line, ",")-9); //1
                //echo $hourname_id . "--";
                $hourname_name = substr($line, strpos($line, ",")+2, strpos($line, ")")-strpos($line, ",")-3);  //9:00-10:00
                //echo $hourname_name . "<br \>";
                $sql = "INSERT INTO hourname ".
                       "(idHourname,Timetable_idTimetable,Name) ".
                       "VALUES ('$hourname_id','$id_timetable','$hourname_name');";
                $result = mysqli_query($db, $sql);
            }
            
            // Get the CLASSROOMs
            if(substr($line, 0, 5) == "class")
            {
                $classroom_id = substr($line, 10, strpos($line, ",")-10);  //1
                //echo $classroom_id . "--";
                $t6_1 = substr($line, strpos($line, ",")+2, 100);  //Aμφιθέατρο',500,[na(2,3),na(4,5)]).

                $classroom_name = substr($t6_1, 0, strpos($t6_1, ",")-1);  //Aμφιθέατρο
                //echo $classroom_name . "--";
                $t6_2 = substr($t6_1, strpos($t6_1, "'")+2, 100);  //500,[na(2,3),na(4,5)]).

                $classroom_cap = substr($t6_2, 0, strpos($t6_2, ","));  //500
                //echo $classroom_cap . "<br \>";
                $t6_3 = substr($t6_2, strpos($t6_2, ",")+1, 100);  //[na(2,3),na(4,5)]).
                
                $sql = "INSERT INTO classroom ".
                       "(idClassroom,Timetable_idTimetable,Name,Capacity) ".
                       "VALUES ('$classroom_id','$id_timetable','$classroom_name','$classroom_cap');";
                $result = mysqli_query($db, $sql);
                
                if($t6_3!="[])."){
                    while(strlen($t6_3)!==3){
                        $t6_44_1 = substr($t6_3,strpos($t6_3, "(")+1,strpos($t6_3, ",")-strpos($t6_3, "(")-1);
                        $t6_44_2 = substr($t6_3,strpos($t6_3, ",")+1,strpos($t6_3, ")")-strpos($t6_3, ",")-1);
                        $t6_3 = substr($t6_3,strpos($t6_3, ")")+1,100);
                        $t6_3[0] = "[";
                        $sql = "INSERT INTO classroomrestrictions ".
                           "(idClassroomRestrictions,Classroom_idClassroom,Classroom_Timetable_idTimetable,Day,Hour) ".
                           "VALUES (NULL,'$classroom_id','$id_timetable','$t6_44_1','$t6_44_2');";
                        $result = mysqli_query($db, $sql);
                    }
                }
            }
            
            // Get the TEACHERs
            if(substr($line, 0, 5) == "teach")
            {
                $teacher_id = substr($line, 8, strpos($line, ",")-8);  //50
                //echo $teacher_id . "--";
                $t7_1 = substr($line, strpos($line, ",")+2, 100);  //_',[],0,0,0).

                $teacher_name = substr($t7_1, 0, strpos($t7_1, ",")-1);  //-
                //echo $teacher_name . "<br \>";
                $t7_2 = substr($t7_1, strpos($t7_1, "'")+2, 100);  //[],0,0,0).
                
                $sql = "INSERT INTO teacher ".
                       "(idTeacher,Timetable_idTimetable,Name,ContinuingHours,HoursPerDay,DaysPerWeek) ".
                       "VALUES ('$teacher_id','$id_timetable','$teacher_name',0,0,0);";
                $result = mysqli_query($db, $sql);
                
                if(strlen($t7_2)>13){
                    while(strlen($t7_2)>12){
                        $t7_33_1 = substr($t7_2,strpos($t7_2, "(")+1,strpos($t7_2, ",")-strpos($t7_2, "(")-1);
                        $t7_33_2 = substr($t7_2,strpos($t7_2, ",")+1,strpos($t7_2, ")")-strpos($t7_2, ",")-1);
                        $t7_2 = substr($t7_2,strpos($t7_2, ")")+1,100);
                        $t7_2[0] = "[";
                        $sql = "INSERT INTO teacherrestrictions ".
                           "(idTeacherRestrictions,Teacher_idTeacher,Teacher_Timetable_idTimetable,Day,Hour) ".
                           "VALUES (NULL,'$teacher_id','$id_timetable','$t7_33_1','$t7_33_2');";
                        $result = mysqli_query($db, $sql);
                    }
                }
                
                $t7_4 = substr($t7_2,strpos($t7_2, ",")+1,100);  //0,0,0).
                $t7_5_1 = substr($t7_4,0,strpos($t7_4, ","));
                $t7_44 = substr($t7_4,strpos($t7_4, ",")+1,100);
                $t7_5_2 = substr($t7_44,0,strpos($t7_44, ","));
                $t7_444 = substr($t7_44,strpos($t7_44, ",")+1,100);
                $t7_5_3 = substr($t7_444,0,strpos($t7_444, ")"));
                if(($t7_5_1!=0)||($t7_5_2!=0)||($t7_5_3!=0)){
                    $result = mysqli_query($db, "UPDATE teacher SET ContinuingHours='$t7_5_1', HoursPerDay='$t7_5_2', DaysPerWeek='$t7_5_3' WHERE Timetable_idTimetable='$id_timetable' AND idTeacher='$teacher_id';");
                }
            }
            
            // Get the SUBJECTs
            if(substr($line, 0, 5) == "subje")
            {
                $subject_id = substr($line, 8, strpos($line, ",")-8);  //5
                //echo $subject_id . "--";
                $t8_1 = substr($line, strpos($line, ",")+2, 1000);  //Κ02','Λογική Σχεδίαση',1,2+2,[5],3,[26 1 36 1],[1],'1ο - ΥΜ',[]).

                $subject_code = substr($t8_1, 0, strpos($t8_1, "'"));  //Κ02
                //echo $subject_code . "--";
                $t8_2 = substr($t8_1, strpos($t8_1, "'")+3, 1000);  //Λογική Σχεδίαση',1,2+2,[5],3,[26 1 36 1],[1],'1ο - ΥΜ',[]).

                $subject_name = substr($t8_2, 0, strpos($t8_2, "'"));  //Λογική Σχεδίαση
                //echo $subject_name . "--";
                $t8_3 = substr($t8_2, strpos($t8_2, "'")+2, 1000);  //1,2+2,[5],3,[26 1 36 1],[1],'1ο - ΥΜ',[]).

                $subject_st = substr($t8_3, 0, strpos($t8_3, ","));  //1
                //echo $subject_st . "--";
                $t8_4 = substr($t8_3, strpos($t8_3, ",")+1, 1000);  //2+2,[5],3,[26 1 36 1],[1],'1ο - ΥΜ',[]).

                $subject_hrs = substr($t8_4, 0, strpos($t8_4,","));  //2+2
                //echo $subject_hrs . "--";
                $t8_5 = substr($t8_4, strpos($t8_4, ",")+1, 1000);  //[5],3,[26 1 36 1],[1],'1ο - ΥΜ',[]).

                $t8_6 = substr($t8_5, strpos($t8_5, "]")+2, 1000);  //3,[26 1 36 1],[1],'1ο - ΥΜ',[]).
                $subject_t_ids = substr($t8_5, 1, strpos($t8_5,"]"));  //5]
                $subject_t_ids[strlen($subject_t_ids)-1]=",";  //5,
                $subject_t_ids_array = array();
                while(strlen($subject_t_ids) > 1){
                    $temp = substr($subject_t_ids , 0, strpos($subject_t_ids, ","));
                    array_push($subject_t_ids_array, $temp);
                    $subject_t_ids = substr($subject_t_ids, strpos($subject_t_ids, ",")+1, 1000);
                }
                //foreach($subject_t_ids_array as $i){
                //    echo $i . "--";
                //}

                $subject_av = substr($t8_6, 0, strpos($t8_6, ","));  //3
                //echo $subject_av . "--";
                $t8_7 = substr($t8_6, strpos($t8_6, ",")+1, 1000);  //[26 1 36 1],[1],'1ο - ΥΜ',[]).

                $t8_8 = substr($t8_7, strpos($t8_7, ",")+1, 1000);  //[1],'1ο - ΥΜ',[]).
                $subject_sch = substr($t8_7, 1, strpos($t8_7,"]"));  //26 1 36 1]
                $subject_sch[strlen($subject_sch)-1]=" ";  //26 1 36 1_
                $subject_sch_array = array();
                while(strlen($subject_sch) > 1){
                    $temp = substr($subject_sch , 0, strpos($subject_sch, " "));
                    array_push($subject_sch_array, $temp);
                    $subject_sch = substr($subject_sch, strpos($subject_sch, " ")+1, 1000);
                }
                //foreach($subject_sch_array as $i){
                //    echo $i . "--";
                //}

                $t8_9 = substr($t8_8, strpos($t8_8, ",")+2, 1000);  //1ο - ΥΜ',[]).
                $subject_pref = substr($t8_8, 1, strpos($t8_8,"]"));  //1]
                $subject_pref[strlen($subject_pref)-1]=" ";  //1_
                $subject_pref_array = array();
                while(strlen($subject_pref) > 1){
                    $temp = substr($subject_pref , 0, strpos($subject_pref, " "));
                    array_push($subject_pref_array, $temp);
                    $subject_pref = substr($subject_pref, strpos($subject_pref, " ")+1, 1000);
                }
                //foreach($subject_pref_array as $i){
                //    echo $i . "--";
                //}
                
                $subject_sem = substr($t8_9, 0, strpos($t8_9, "',"));  //1ο - ΥΜ
                //echo $subject_sem . "<br \>";                
                $t8_10 = substr($t8_9, strpos($t8_9, ",")+1, 1000);  //[]).
                
                $sql = "INSERT INTO subject ".
                       "(idSubject,Timetable_idTimetable,Colorgroup_idColorgroup,Printgroup_idPrintgroup,Code,Name,Students,Hours,Availability,Semister) ".
                       "VALUES ('$subject_id','$id_timetable',NULL,NULL,'$subject_code','$subject_name','$subject_st','$subject_hrs','$subject_av','$subject_sem');";
                $result = mysqli_query($db, $sql);
                
                foreach($subject_t_ids_array as $i){
                    $sql = "INSERT INTO subject_has_teacher ".
                       "(Subject_idSubject,Subject_Timetable_idTimetable,Teacher_idTeacher) ".
                       "VALUES ('$subject_id','$id_timetable','$i');";
                    $result = mysqli_query($db, $sql);
                }
                
                $i=0;
                $j=1;
                while($i<count($subject_sch_array)){
                    $sql = "INSERT INTO timeclassroom ".
                       "(idTimeClassroom,Subject_idSubject,Subject_Timetable_idTimetable,Time,Classroom) ".
                       "VALUES (NULL,'$subject_id','$id_timetable','$subject_sch_array[$i]','$subject_sch_array[$j]');";
                    $result = mysqli_query($db, $sql);
                    $i=$i+2;
                    $j=$j+2;
                }
                
                foreach($subject_pref_array as $i){
                    $sql = "INSERT INTO subject_has_classroom_preference ".
                       "(idSubject_has_Classroom_Preference,Subject_idSubject,Subject_Timetable_idTimetable,idClassroom) ".
                       "VALUES (NULL,'$subject_id','$id_timetable','$i');";
                    $result = mysqli_query($db, $sql);
                }
                
                if($t8_10!="[])."){
                    while(strlen($t8_10)!==3){
                        $t8_11_1 = substr($t8_10,strpos($t8_10, "(")+1,strpos($t8_10, ",")-strpos($t8_10, "(")-1);
                        $t8_11_2 = substr($t8_10,strpos($t8_10, ",")+1,strpos($t8_10, ")")-strpos($t8_10, ",")-1);
                        $t8_10 = substr($t8_10,strpos($t8_10, ")")+1,100);
                        $t8_10[0] = "[";
                        $sql = "INSERT INTO subjectrestrictions ".
                           "(idSubjectRestrictions,Subject_idSubject,Subject_Timetable_idTimetable,Day,Hour) ".
                           "VALUES (NULL,'$subject_id','$id_timetable','$t8_11_1','$t8_11_2');";
                        $result = mysqli_query($db, $sql);
                    }
                }              
            }
            
            // Get the CGROUPs
            if(substr($line, 0, 5) == "cgrou")
            {
                $cgroup_id = substr($line, 7, strpos($line, ",")-7);  //36
                //echo $cgroup_id . "--";
                $t9_1 = substr($line, strpos($line, ",")+1, 1000);  //'Γ\' Έτος - Β - Ε5β',[42,39,16,153,22,27,165],50).

                $cgroup_name = substr($t9_1, 1, strpos($t9_1, ",")-2);  //Γ\' Έτος - Β - Ε5β
                //$cgroup_name = stripslashes(substr($t9_1, 1, strpos($t9_1, ",")-2));  //Γ' Έτος - Β - Ε5β
                //echo $cgroup_name . "--";
                $t9_2 = substr($t9_1, strpos($t9_1, ",")+2, 1000);  //42,39,16,153,22,27,165],50).

                $t9_3 = substr($t9_2, strpos($t9_2,"]")+2, 1000);  //50).
                $cgroup_ids = substr($t9_2, 0, strpos($t9_2,"]"));  //42,39,16,153,22,27,165
                $cgroup_ids[strlen($cgroup_ids)]=",";  //42,39,16,153,22,27,165,
                $cgroup_ids_array = array();
                while(strlen($cgroup_ids) > 1){
                    $temp = substr($cgroup_ids , 0, strpos($cgroup_ids, ","));
                    array_push($cgroup_ids_array, $temp);
                    $cgroup_ids = substr($cgroup_ids, strpos($cgroup_ids, ",")+1, 1000);
                }
                //foreach($cgroup_ids_array as $i){
                //    echo $i . "--";
                //}

                $cgroup_w = substr($t9_3, 0, strpos($t9_3, ")"));  //50
                //echo $cgroup_w . "<br \>";
                
                $sql = "INSERT INTO cgroup ".
                       "(idCgroup,Timetable_idTimetable,Name,Multiplier) ".
                       "VALUES ('$cgroup_id','$id_timetable','$cgroup_name','$cgroup_w');";
                $result = mysqli_query($db, $sql);
                
                foreach($cgroup_ids_array as $i){
                    $sql = "INSERT INTO cgroup_has_subject ".
                       "(Cgroup_idCgroup,Subject_idSubject,Subject_Timetable_idTimetable) ".
                       "VALUES ($cgroup_id,'$i','$id_timetable');";
                    $result = mysqli_query($db, $sql);
                }

            }
            
            // Get the COLORGROUPs
            if(substr($line, 0, 5) == "color")
            {
                $colorgroup_id = substr($line, 11, strpos($line, ",")-11);  //2
                //echo $colorgroup_id . "--";
                $t10_1 = substr($line, strpos($line, ",")+2, 1000);  //ΠΠΣ - 2ο έτος',[115,8,9,11,155,13],'FFFF80').

                $colorgroup_name = substr($t10_1, 0, strpos($t10_1, "'"));  //ΠΠΣ - 2ο έτος
                //echo $colorgroup_name . "--";
                $t10_2 = substr($t10_1, strpos($t10_1, ",")+2, 1000);  //115,8,9,11,155,13],'FFFF80').

                $t10_3 = substr($t10_2, strpos($t10_2,"]")+3, 1000);  //FFFF80').
                $colorgroup_ids = substr($t10_2, 0, strpos($t10_2,"]"));  //115,8,9,11,155,13
                $colorgroup_ids[strlen($colorgroup_ids)]=",";  //115,8,9,11,155,13,
                $colorgroup_ids_array = array();
                while(strlen($colorgroup_ids) > 1){
                    $temp = substr($colorgroup_ids , 0, strpos($colorgroup_ids, ","));
                    array_push($colorgroup_ids_array, $temp);
                    $colorgroup_ids = substr($colorgroup_ids, strpos($colorgroup_ids, ",")+1, 1000);
                }
                //foreach($colorgroup_ids_array as $i){
                //    echo $i . "--";
                //}

                $colorgroup_color = substr($t10_3, 0, strpos($t10_3, "'"));  //FFFF80
                //echo $colorgroup_color . "<br \>";
                
                $sql = "INSERT INTO colorgroup ".
                       "(idColorgroup,Timetable_idTimetable,Name,Color) ".
                       "VALUES ('$colorgroup_id','$id_timetable','$colorgroup_name','$colorgroup_color');";
                $result = mysqli_query($db, $sql);

                foreach($colorgroup_ids_array as $i){
                    $sql = "UPDATE subject SET Colorgroup_idColorgroup='$colorgroup_id' WHERE idSubject='$i' AND Timetable_idTimetable='$id_timetable';";
                    $result = mysqli_query($db, $sql);
                }
                
            }
            
            // Get the PRINTGROUPs
            if(substr($line, 0, 5) == "print")
            {
                $printgroup_id = substr($line, 11, strpos($line, ",")-11);  //2
                //echo $printgroup_id . "--";
                $t11_1 = substr($line, strpos($line, ",")+2, 1000);  //ΠΠΣ/ΠΜΣ',[174,60,37,58,125],20).

                $printgroup_name = substr($t11_1, 0, strpos($t11_1, "'"));  //ΠΠΣ/ΠΜΣ
                //echo $printgroup_name . "--";
                $t11_2 = substr($t11_1, strpos($t11_1, ",")+2, 1000);  //174,60,37,58,125],20).

                $t11_3 = substr($t11_2, strpos($t11_2,"]")+2, 1000);  //20).
                $printgroup_ids = substr($t11_2, 0, strpos($t11_2,"]"));  //174,60,37,58,125
                $printgroup_ids[strlen($printgroup_ids)]=",";  //174,60,37,58,125,
                $printgroup_ids_array = array();
                while(strlen($printgroup_ids) > 1){
                    $temp = substr($printgroup_ids , 0, strpos($printgroup_ids, ","));
                    array_push($printgroup_ids_array, $temp);
                    $printgroup_ids = substr($printgroup_ids, strpos($printgroup_ids, ",")+1, 1000);
                }
                //foreach($printgroup_ids_array as $i){
                //    echo $i . "--";
                //}

                $printgroup_num = substr($t11_3, 0, strpos($t11_3, ")"));  //20
                //echo $printgroup_num . "<br \>";
                
                $sql = "INSERT INTO printgroup ".
                       "(idPrintgroup,Timetable_idTimetable,Name,Number) ".
                       "VALUES ('$printgroup_id','$id_timetable','$printgroup_name','$printgroup_num');";
                $result = mysqli_query($db, $sql);

                foreach($printgroup_ids_array as $i){
                    $sql = "UPDATE subject SET Printgroup_idPrintgroup='$printgroup_id' WHERE idSubject='$i' AND Timetable_idTimetable='$id_timetable';";
                    $result = mysqli_query($db, $sql);
                }
                
            }
            
            // Get the DGROUPs
            if(substr($line, 0, 5) == "dgrou")
            {
                $dgroup_id = substr($line, 7, strpos($line, ",")-7);  //36
                //echo $dgroup_id . "--";
                $t12_1 = substr($line, strpos($line, ",")+1, 1000);  //'Γ\' Έτος - Β - Ε5β',[42,39,16,153,22,27,165]).

                $dgroup_name = stripslashes(substr($t12_1, 1, strpos($t12_1, ",")-2));  //Γ' Έτος - Β - Ε5β
                //echo $dgroup_name . "--";
                $t12_2 = substr($t12_1, strpos($t12_1, ",")+2, 1000);  //42,39,16,153,22,27,165]).

                $dgroup_ids = substr($t12_2, 0, strpos($t12_2,"]"));  //42,39,16,153,22,27,165
                $dgroup_ids[strlen($dgroup_ids)]=",";  //42,39,16,153,22,27,165,
                $dgroup_ids_array = array();
                while(strlen($dgroup_ids) > 1){
                    $temp = substr($dgroup_ids , 0, strpos($dgroup_ids, ","));
                    array_push($dgroup_ids_array, $temp);
                    $dgroup_ids = substr($dgroup_ids, strpos($dgroup_ids, ",")+1, 1000);
                }
                //foreach($dgroup_ids_array as $i){
                //    echo $i . "--";
                //}
                echo "<br \>";
                
                $sql = "INSERT INTO dgroup ".
                       "(idDgroup,Timetable_idTimetable,Name) ".
                       "VALUES ('$dgroup_id','$id_timetable','$dgroup_name');";
                $result = mysqli_query($db, $sql);
                
                foreach($dgroup_ids_array as $i){
                    $sql = "INSERT INTO dgroup_has_subject ".
                       "(Dgroup_idDgroup,Subject_idSubject,Subject_Timetable_idTimetable) ".
                       "VALUES ($dgroup_id,'$i','$id_timetable');";
                    $result = mysqli_query($db, $sql);
                }
                
            }
            
            // Get the EQUALs
            if(substr($line, 0, 5) == "equal")
            {
                $equal_id1 = substr($line, 6, strpos($line, ",")-6);  //303
                //echo $equal_id1 . "--";
                $t13_1 = substr($line, strpos($line, ",")+1, 100);  //302).

                $equal_id2 = substr($t13_1, 0, strpos($t13_1, ")"));  //302
                //echo $equal_id2 . "<br \>";
                
                $sql = "INSERT INTO equal ".
                       "(idEqual,Subject_Timetable_idTimetable,Subject_idSubject1,idSubject2) ".
                       "VALUES (NULL,'$id_timetable','$equal_id1','$equal_id2');";
                $result = mysqli_query($db, $sql);
                
            }
            
            $text = substr($text, strpos($text,').')+4, strlen($text));
        }
        
    } 
    else{
        echo "<br /><br /><center><b>Sorry, there was an error uploading your file. </b></center>";
    }
    
    unlink($target_file);
    
}

?>