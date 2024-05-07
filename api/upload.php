<?php
require '../controllers/data.php';

$fetch = new Data();

$method =  $_SERVER['REQUEST_METHOD'];

$endpoint = $_SERVER['PATH_INFO'];

header('Content-Type: application/json');

switch($method){
    case 'GET':
        if($endpoint === '/maps'){
            $data = $fetch->getData();
            echo json_encode($data);
        }
        elseif(preg_match('/^\/maps\/(\d+)$/',$endpoint,$matches)){
            $data = $fetch->getDataWithId($matches[1])[0];
            if($data){     
                echo json_encode(["status"=>"Success","data"=>$data]);
            }
            else{
                http_response_code(404);
                echo json_encode(["status"=>"Error","message"=>"No data found !"]);
            }
            
        }
        else{
            http_response_code(204);
            echo json_encode(["status"=>"Error","message"=>"Access Denied"]);
        }
        break;
    case 'PUT':
        if(preg_match('/^\/maps\/(\d+)$/',$endpoint,$matches)){
            $data = json_decode(file_get_contents('php://input'),true);
            $res = $fetch->putData($matches[1],$data);
            if(!$res){
                http_response_code(500);
                echo json_encode(["status"=>"Error","message"=>"Something went wrong !"]);
            }
            else{
                echo json_encode(["status"=>"Success","message"=>"Data Updated."]);
            }
        }
        else{
            http_response_code(204);
            echo json_encode(["status"=>"Error","message"=>"Access Denied"]);
        }
        break;
    case 'POST':
        if($endpoint === '/maps'){
            $data = json_decode(file_get_contents('php://input'),true);
            $res = $fetch->writeData($data);
            if(!$res){
                http_response_code(500);
                echo json_encode(["status"=>"Error","message"=>"Something went wrong !"]);
            }
            else{
                
                echo json_encode(["status"=>"Success","message"=>"Data Uploaded."]);
            }
        }
        else{
            http_response_code(204);
            echo json_encode(["status"=>"Error","message"=>"Access Denied"]);
        }
        break;
    default:
        http_response_code(204);
        echo json_encode(["status"=>"Error","message"=>"Access Denied"]);
}
