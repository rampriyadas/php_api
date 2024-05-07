<?php

require './config.php';

$res = pg_query("SELECT * FROM maps");
$data = [];
while ($row_users = pg_fetch_object($res)) {

   $data[] = $row_users;


}
echo json_encode($data);
