<?php
header("Content-Type: application/json");

include '../db.php';

$request_method = $_SERVER["REQUEST_METHOD"];
$path = explode('/', $_SERVER['REQUEST_URI']);
$endpoint = end($path);

switch($request_method) {
    case 'GET':
        switch ($endpoint) {
            case 'gelar_sarjana':
                get_gelar_sarjana();
                break;
            case 'selain_sarjana':
                get_selain_sarjana();
                break;
            case 'jumlah_user_pelatihan':
                get_jumlah_user_pelatihan();
                break;
            case 'gaji_mentor':
                get_gaji_mentor();
                break;
            case 'data_user':
                get_data_user();
                break;
            default:
                header("HTTP/1.0 404 Not Found");
                echo json_encode(array("message" => "Endpoint not found"));
                break;
        }
        break;
    case 'POST':
        switch ($endpoint) {
            case 'user':
                create_user();
                break;
            default:
                header("HTTP/1.0 404 Not Found");
                echo json_encode(array("message" => "Endpoint not found"));
                break;
        }
        break;
    case 'PUT':
        switch ($endpoint) {
            case 'user':
                update_user();
                break;
            default:
                header("HTTP/1.0 404 Not Found");
                echo json_encode(array("message" => "Endpoint not found"));
                break;
        }
        break;
    case 'DELETE':
        switch ($endpoint) {
            case 'user':
                delete_user();
                break;
            default:
                header("HTTP/1.0 404 Not Found");
                echo json_encode(array("message" => "Endpoint not found"));
                break;
        }
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function get_gelar_sarjana() {
    global $conn;
    $query = "SELECT C.id, C.username, B.course, B.mentor, B.title 
              FROM usercourse A 
              LEFT JOIN courses B ON A.id_course=B.id 
              LEFT JOIN users C ON C.id=A.id_user 
              WHERE B.title LIKE 'S.%'";
    $result = $conn->query($query);
    $response = array();
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
    echo json_encode($response);
}

function get_selain_sarjana() {
    global $conn;
    $query = "SELECT C.id, C.username, B.course, B.mentor, B.title 
              FROM usercourse A 
              LEFT JOIN courses B ON A.id_course=B.id 
              LEFT JOIN users C ON C.id=A.id_user 
              WHERE B.title NOT LIKE 'S.%'";
    $result = $conn->query($query);
    $response = array();
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
    echo json_encode($response);
}

function get_data_user() {
    global $conn;
    $query = "SELECT * from users ";
    $result = $conn->query($query);
    $response = array();
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
    echo json_encode($response);
}

function get_jumlah_user_pelatihan() {
    global $conn;
    $query = "SELECT B.course, B.mentor, B.title, COUNT(*) AS jumlah_peserta 
              FROM usercourse A 
              LEFT JOIN courses B ON A.id_course = B.id 
              LEFT JOIN users C ON C.id = A.id_user 
              GROUP BY B.course, B.mentor, B.title";
    $result = $conn->query($query);
    $response = array();
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
    echo json_encode($response);
}

function get_gaji_mentor() {
    global $conn;
    $query = "SELECT B.mentor, COUNT(*) AS jumlah_peserta, COUNT(*) * 200000 AS total_fee 
              FROM usercourse A 
              LEFT JOIN courses B ON A.id_course = B.id 
              LEFT JOIN users C ON C.id = A.id_user 
              GROUP BY B.mentor";
    $result = $conn->query($query);
    $response = array();
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
    echo json_encode($response);
}

function create_user() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Contoh asumsi input data
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];
    
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $email, $password);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "User created successfully"));
    } else {
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode(array("message" => "Failed to create user"));
    }
}

function update_user() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Contoh asumsi input data
    $id = $data['id'];
    $username = $data['username'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    
    $query = "UPDATE users SET username=?, password=?, WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $username, $password, $id);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "User updated successfully"));
    } else {
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode(array("message" => "Failed to update user"));
    }
}

function delete_user() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Contoh asumsi input data
    $id = $data['id'];
    
    $query = "DELETE FROM users WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "User deleted successfully"));
    } else {
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode(array("message" => "Failed to delete user"));
    }
}
?>
