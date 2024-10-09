<?php

header('Access-Control-Allow-Origin: *'); // 모든 도메인 허용
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); // 허용할 메서드 설정
header('Access-Control-Allow-Headers: Content-Type'); // 허용할 헤더 설정

// 데이터베이스 연결
$servername = "localhost";
$username = "root"; // 실제 사용자 이름으로 변경
$password = ""; // 실제 비밀번호로 변경
$dbname = "user_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 체크
if ($conn->connect_error) {
    die("연결 실패: " . $conn->connect_error);
}

// POST 요청 처리
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $detail_address = $_POST['detail-address'];

    // 비밀번호는 추가적으로 해싱 처리할 수 있습니다.
    $password = $_POST['password']; // 입력된 비밀번호
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL 삽입 쿼리
    $sql = "INSERT INTO users (username, name, email, phone, address, detail_address, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $username, $name, $email, $phone, $address, $detail_address, $hashed_password);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => '회원가입 성공!']);
    } else {
        echo json_encode(['success' => false, 'message' => '오류: ' . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
