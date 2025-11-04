<?php

header("Content-Type: application/json");

include_once "db.php";

$method = $_SERVER['REQUEST_METHOD'];
$resource = $_GET['resource'] ?? null;
$id = $_GET['id'] ?? null;

// if($resource !== "students"){
//     http_response_code(404);
//     echo json_encode(["error"=>"Resource not found"]);
//     exit();
// }

switch($method){
    case 'GET':
        if($id){
            $stmt = $conn->prepare("SELECT * FROM api WHERE id=?");
            $stmt->bind_param('i',$id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_assoc() ?: ['error' => 'api database not found']);
        }else{
            $result = $conn->query("SELECT * FROM api");
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        }
        break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);

            $stmt = $conn->prepare("INSERT INTO api (name, age) VALUES (?, ?)");
            $stmt->bind_param("si", $data['name'], $data['age']);

            if ($stmt->execute()) {
                echo json_encode([
                    'message' => 'API post successfully',
                    'id' => $stmt->insert_id
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to create API']);
            }
            break;

            case 'PUT':
                if($id){
                    http_response_code(405);
                    echo json_encode(['error' => 'Missing api']);
                    exit();
                }

                $stmt = json_decode(file_get_contents('php://input'),true);
                $stmt = $conn->prepare('UPDATE api SET name = ? , age = ? WHERE id = ?');
                if($stmt->execute()){
                    echo json_encode(['message' => 'api update successfully']);
                }else{
                    http_response_code(500);
                    echo json_encode(['error' => 'failed to update']);
                }
                break;

}

?>