<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method ="POST" enctype="multipart/form-data">
        <input type ="file" name ="file"><br><br>
        <button type ="submit" name ="submit">Import</button>
    </form>

<?php 
require_once __DIR__ . '/App/excel.php';
require_once __DIR__ . '/00_connect/connect.php';
use Connect\connect\conn_db ; 

$connection = new conn_db() ; 
$conn = $connection -> conn_edp_data() ; 
 if(isset($_POST['submit'])){
    $file  = $_FILES['file']['tmp_name'];

    
    $excel = new excel() ; 
    $data_read = $excel-> read_file($file); 
    foreach($data_read as $test){
        // print_r($test) ; 
        echo '<br>'; 
        $ver         = $test['B'] ; 
        $date        = $test['C'] ; 
        $working_day = $test['D']; 
        $model       = $test['E']; 
        $model_code  = $test['F'] ; 
        $model_name  = $test['G'] ; 
        $model_group = $test['H'] ; 
        $qty         = $test['I'] ; 
        $pro_code    = $test['J'] ; 
        $pro_name    = $test['K'] ; 
        $pro_type    = $test['L'] ; 
        $unit_hour   = $test['M']; 
        $total_per   = $test['N']; 
        $daily_per   = $test['O'] ; 
        $type      = $test['P'] ; 
        if($type <> 'TYPE'){
            $sql_insert = "INSERT INTO edp VALUES(NULL,'$ver','$date','$working_day','$model','$model_code','$model_name','$model_group','$qty','$pro_code','$pro_name','$pro_type','$unit_hour','$total_per','$daily_per','$type')" ; 
            mysqli_query($conn,$sql_insert) ; 
        }


    }

 }

?>
</body>
</html>