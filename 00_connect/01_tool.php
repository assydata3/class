<?php 

class tool_support {
   
    public function list_folder_file($folder){
        $list = array(); $i =  0 ; 
        $dir    = $folder;
        $files1 = scandir($dir);
        $r=0 ; 
        while(isset($files1[$r])){
            $txt = $files1[$r];
            $path_check = $folder.'/'.$txt;
            if(!is_file($path_check) and ($txt <> '..') and ($txt <> '.')){
                //echo $txt.'<br>';
                $i++; 
                $list[$i] = $txt;  
            }
            $list['count'] = $i ; 
        $r++; 
        }
        return $list ; 
    }


}





?>