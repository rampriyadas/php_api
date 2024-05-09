<?php
require_once '../config.php';
class ShapeFile{
    public function sendToPostgres(){
        
    }
    public function getDataWithId($id){
        $res = pg_query("SELECT * FROM maps WHERE id=".$id);
        $data = [];
        while ($row_users = pg_fetch_object($res)) { $data[] = $row_users; }
        return $data;
    }
    public function putData($id,$data){
        $name = $data['name'];
        $res = pg_query("UPDATE maps SET name = '".$name."' WHERE id = $id;");
        return $res;
    }
    public function writeData($data){
        $name = $data['name'];
        $res = pg_query("INSERT INTO maps(name)VALUES ('".$name."');");
        return $res;
    }
}

