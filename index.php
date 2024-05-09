<?php

require './config.php';
$path = $_SERVER['DOCUMENT_ROOT'].'/uploads/shapefiles/'.'20240509115813';
$directories = glob($path . '/*' , GLOB_ONLYDIR);
$files = [];
foreach ($directories as $dir){
    array_push($files,glob($dir."/*.shp")[0]);
}

foreach ($files as $key) {
    $temp = explode("/",explode(".",$key)[0]);
    $scope = $temp[sizeof($temp) - 2];
    $table = "tbl_".$scope;
    shell_exec("ogr2ogr -f PostgreSQL PG:\"dbname='".$_ENV['DB_NAME']."' host='".$_ENV['DB_HOST']."' port='".$_ENV['DB_PORT']."' user='".$_ENV['DB_USER']."' password='".$_ENV['DB_PASSWORD']."'\" $key -lco GEOMETRY_NAME=geom -lco FID=gid -lco SPATIAL_INDEX=GIST -nlt PROMOTE_TO_MULTI -nln $table -overwrite");
    $res = pg_query("INSERT INTO shape (file_key,geom) VALUES ( $scope, (SELECT geom FROM $table))");
    $res = pg_query("DROP TABLE $table;");
    $res = pg_query("CREATE OR REPLACE VIEW view_".$scope." AS SELECT geom FROM shape WHERE file_key = '$scope';");
    
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://localhost:8080/geoserver/rest/workspaces/van/datastores/vanit/featuretypes.xml',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'<?xml version="1.0" encoding="UTF-8"?>
    <featureType>
        <name>view_'.$scope.'</name>
        <nativeName>view_'.$scope.'</nativeName>
    </featureType>',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Basic YWRtaW46Z2Vvc2VydmVy',
        'Content-Type: application/xml'
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
}





//"ogr2ogr -f PostgreSQL PG:dbname='".$_ENV['DB_NAME'].' host='$_ENV['DB_HOST']' port='$_ENV['DB_PORT']' user='$_ENV['DB_USER']' password='$_ENV['DB_PASSWORD'] $key -lco GEOMETRY_NAME=geom -lco FID=gid -lco SPATIAL_INDEX=GIST -nlt PROMOTE_TO_MULTI -nln main_roads_2 -overwrite"
