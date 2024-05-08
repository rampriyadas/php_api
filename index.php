<?php

require './config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
   if(isset($_FILES['file']) && !empty($_FILES['file']) && is_array($_FILES['file'])){
       $zip = new ZipArchive();
       $zip_file_name = date("YmdHis").".zip";
       if($zip->open($zip_file_name, ZipArchive::CREATE) === true){
           foreach($_FILES['file']['tmp_name'] as $key => $tmpName){
               $file_name = $_FILES['file']['name'][$key];
               $zip->addFile($tmpName, rand(10,100).date("YmdHis").".zip");
           }
       
           $zip->close();
       }else{
           echo "Couldn't create Zip Archive";
       }
   }
   echo json_encode("Okay");
}
?>
<form action="/" method="post" enctype="multipart/form-data">
        <input type="file" name="file[]">
        <input type="file" name="file[]">
        <input type="submit" >
    </form>