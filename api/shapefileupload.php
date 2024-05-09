<?php
require '../controllers/shapefile.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
   if(isset($_FILES['file']) && !empty($_FILES['file']) && is_array($_FILES['file'])){
       $zip = new ZipArchive();
       $zip_file_name = date("YmdHis");
       if($zip->open("../uploads/zip/".$zip_file_name.".zip", ZipArchive::CREATE) === true){
           foreach($_FILES['file']['tmp_name'] as $key => $tmpName){
               $name = rand(10,100).date("YmdHis");
               $zip->addFile($tmpName, $name.".zip");
               $zip2 = new ZipArchive();
               $zip2->open($tmpName);
               $shapefiles_dir = '../uploads/shapefiles/'.$zip_file_name.'/'.$name; 
               $zip2->extractTo($shapefiles_dir);
               $zip2->close();
           }
           $zip->close();
       }else{
           echo "Couldn't create Zip Archive";
       }     
   }
   echo json_encode("Okay");
}
?>
