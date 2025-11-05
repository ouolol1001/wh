<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include_once "db.php";

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);
$id = $_GET['id'] ?? null;

// 支持前端用 _method 模拟 PUT / DELETE
if ($method === 'POST' && isset($data['_method'])) {
    $method = strtoupper($data['_method']);
}

// CORS 预检请求
if ($method === "OPTIONS") {
    http_response_code(200);
    exit;
}

switch($method) {
    // ✅ GET：获取所有学生
    case 'GET':
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM api WHERE id=?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_assoc() ?: ['error' => 'Student not found']);
        } else {
            $result = $conn->query("SELECT * FROM api");
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        }
        break;

    // ✅ POST：新增学生
    case 'POST':
        $name = $data['name'] ?? '';
        $age = $data['age'] ?? 0;

        $stmt = $conn->prepare("INSERT INTO api (name, age) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $age);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Student added successfully', 'id' => $stmt->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add student']);
        }
        break;

    // ✅ PUT：更新学生
    case 'PUT':
        $id = $data['id'] ?? null;
        $name = $data['name'] ?? '';
        $age = $data['age'] ?? 0;

        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing student id']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE api SET name=?, age=? WHERE id=?");
        $stmt->bind_param("sii", $name, $age, $id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Student updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update student']);
        }
        break;

    // ✅ DELETE：删除学生
    case 'DELETE':
        $id = $data['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing student id']);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM api WHERE id=?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Student deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete student']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}
?>
