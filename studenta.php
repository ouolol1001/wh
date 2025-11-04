<?php

header("Content-Type: application/json");

include_once "db.php";

$method = $_SERVER['REQUEST_METHOD'];
$resource = $_GET['resource'] ?? null;
$id = $_GET['id'] ?? null;

if($resource !== "students"){
    http_response_code(404);
    echo json_encode(["error"=>"Resource not found"]);
    exit();
}

switch($method){
    case 'GET':
        if($id){
            $stmt = $conn->prepare("SELECT * FROM api WHERE id=?");
            $stmt->bind_param('i',$id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo_json_encode($result->fetch_assoc() ?: ['error' => 'api database not found']);
        }
}

?>