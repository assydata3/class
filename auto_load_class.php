<?php


spl_autoload_register(function ($class) {
    // Khi Class ko tồn tại --> Autoload sẽ cố gắng tìm name space tương ứng 
   
    $file = __DIR__ .DIRECTORY_SEPARATOR;
    // echo $file.'<br>'; 
    // echo $class.'<br>';


    //Cutting class name 
    $class_array = explode(DIRECTORY_SEPARATOR,$class); 
    $len_array   = count($class_array); 
    $lass_value  = $len_array - 1 ; 
    $lass_data   = $class_array[$lass_value]; 
    // echo "lass value : $lass_data<br>"; 
    $class_2 = str_replace($lass_data,'',$class); 
    $class_3 = substr($class_2,0,-1) ;   // cut text '\' 

    $file = $file . $class_3.'.php';
    // echo $file.'<br>'; 
    

    
    
    if(file_exists($file)){
        include($file);
    }
    
}); 




?>