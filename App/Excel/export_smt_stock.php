<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



$host       = 'assydata3' ; 
$user_login = 'SuperAdmin' ; 
$pass_login =  '123456' ; 
$connect_mp  = mysqli_connect($host,$user_login,$pass_login,'mp_app');


$result = mysqli_query($connect_mp,"SELECT model, shelf_code , input_type FROM `qr_smt_layout_summary` WHERE (model <> '') ORDER BY `model` , `input_type` DESC") ; 
$i = 0 ; 
$total = $stt = array() ; 
$model_no = $model_stt = 0 ; 
$model_name = '' ; 


while($row = mysqli_fetch_assoc($result)){
    $model_current = $row['model'] ; 
    
    
    

    if($model_name <> $model_current){
       
        $model_stt++ ; 
        $model_no = 1 ;
        $model_name = $model_current ; 

        $total[$model_stt][$model_no]['model']      = $model_name  ; 
        $total[$model_stt][$model_no]['shelf_code'] = $row['shelf_code']  ; 
        $stt[$model_stt]                            = $model_no ; 
        
       }
    else{ 			
      
        $model_no ++ ;

        $total[$model_stt][$model_no]['model']     = $model_name  ; 
        $total[$model_stt][$model_no]['shelf_code'] = $row['shelf_code']  ; 
        $stt[$model_stt]                           = $model_no ; 
        
   
    }



}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet()->setTitle('SMT STOCK');;


$rowCount = 1; 
$sheet->setCellValue('A'.$rowCount,'SUMMARY INVENTORY SMT STOCK');
$sheet->getStyle('A1')->getFont()->setSize(24);

$rowCount = 3; 
$sheet->setCellValue('A'.$rowCount,'STT');
$sheet->setCellValue('B'.$rowCount,'Model');
$sheet->setCellValue('C'.$rowCount,'No');
$sheet->setCellValue('D'.$rowCount,'Position');
$sheet->setCellValue('E'.$rowCount,'Count');

$sheet->setCellValue('F'.$rowCount,'Count Person');
$sheet->setCellValue('G'.$rowCount,'Check Person');
$sheet->setCellValue('H'.$rowCount,'Total');

$sheet->getColumnDimension("B")->setAutoSize(true);
$sheet->getColumnDimension("C")->setAutoSize(true);
$sheet->getColumnDimension("D")->setAutoSize(true);
$sheet->getColumnDimension("E")->setAutoSize(true);
$sheet->getColumnDimension("F")->setAutoSize(true);
$sheet->getColumnDimension("G")->setAutoSize(true);
$sheet->getColumnDimension("H")->setAutoSize(true);


$s = 0 ; 
for($u=1;$u<=$model_stt;$u++){
    $number = $stt[$u] ; 
    $rowCount++ ; 
    for($k=1;$k<=$number;$k++){
        $s++ ; 
        $rowCount++ ; 
        $sheet->setCellValue('A'.$rowCount,$s);
        $sheet->setCellValue('B'.$rowCount,$total[$u][$k]['model']);
        $sheet->setCellValue('C'.$rowCount,$k);
        $sheet->setCellValue('D'.$rowCount,$total[$u][$k]['shelf_code']);
    }
    
}


// $result = mysqli_query($connect_mp,"SELECT model, shelf_code , input_type FROM `qr_smt_layout_summary` WHERE (model <> '') ORDER BY `model` , `input_type` DESC") ; 
// $model_no = $model_stt = 0 ; 
// $model_name = '' ; 
// while($data = mysqli_fetch_assoc($result) ){
//     print_r($data) ; 
//     $model_current = $data['model'] ; 
//     $shelf_code    = $data['shelf_code'] ; 
//     // echo "$model_current - $shelf_code <br>" ; 
//     if($model_name <> $model_current){
//         $model_no++ ; 
//         $model_stt = 1 ; 
//         $model_name = $model_current ; 
//         $rowCount++ ; 
//     }
//     else{ 			
//         $model_no++ ; 
//         $model_stt++ ; }
//     $rowCount++ ; 
//     $sheet->setCellValue('A'.$rowCount,$model_no);
//     $sheet->setCellValue('B'.$rowCount,$data['model']);
//     $sheet->setCellValue('C'.$rowCount,$model_stt);
//     $sheet->setCellValue('D'.$rowCount,$data['shelf_code']);
        


// }




### OUTPUT FILE EXCEL 
$writer = new Xlsx($spreadsheet);

## Save File to URL 
// $writer->save('hello_world.xlsx');

### Download file
$fileName = 'STOCK_SMT_LAYOUT.xlsx' ; 
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
$writer->save('php://output');




?>