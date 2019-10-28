
<!--
http://www.w3schools.com/php/php_file_upload.asp
-->

<?php
include('menu.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Upload fct file</title>
    </head>
    <body>
        <div id="uploadpage">
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <h3>Select fct to upload</h3>
                <input type="file" name="fileToUpload" id="fileToUpload">
                <br /><br />
                <input type="submit" value="Upload fct file" name="submit">
            </form>
        </div>    
    </body>
</html>