<?php 

require_once(__DIR__ . '/excel/vendor/autoload.php');

class excel{
 
    public function read_file($file){
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        // Store data from the activeSheet to the varibale in the form of Array
        $data = array(1,$sheet->toArray(null,true,true,true));
        $data_real = $data[1] ;
        return $data_real ; 
    }

}

?>