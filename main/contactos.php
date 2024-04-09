<?php
header('HTTP/1.1 200');
header('Access-Control-Allow-Origin:*');
header('content-type: application/json; charset=utf-8');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

include_once '../controllers/CONTACTOS.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if(!isset($_GET['instruction'])){echo "There is not GET petition!"; return;}

    if($_GET['instruction']=='select'){
       echo json_encode(CONTACTOS::SELECT());
    }

    if($_GET['instruction']=='search'){
       $contactos = $_GET; 
       unset($contactos["instruction"]); 
       echo json_encode(CONTACTOS::SEARCH($contactos));
    }

    if($_GET['instruction']=='delete'){
       $contactos = $_GET; 
       unset($contactos["instruction"]); 
       echo json_encode(CONTACTOS::DELETE($contactos));
    }

}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(!isset($_POST['instruction'])){echo "There is not POST petition!"; return;}

    if($_POST['instruction']=='insert'){
       $contactos = $_POST; 
       unset($contactos["instruction"]); 
       echo json_encode(CONTACTOS::INSERT($contactos));
    }

    if($_POST['instruction']=='update'){
       $contactos = $_POST; 
       unset($contactos["instruction"]); 
       echo json_encode(CONTACTOS::UPDATE($contactos));
    }
}


