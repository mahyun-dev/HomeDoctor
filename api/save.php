
<?php
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	echo json_encode(['error' => 'Method Not Allowed']);
	exit;
}

$model_name = trim($_POST['model_name'] ?? '');
$image_raw = $_POST['image_raw'] ?? '';
$language = $_POST['language'] ?? 'ko';

// 입력값 검증 및 보안 처리
if (!$model_name || !$image_raw || !in_array($language, ['ko', 'en'])) {
	http_response_code(400);
	echo json_encode(['error' => 'Invalid input']);
	exit;
}
$model_name = htmlspecialchars(strip_tags($model_name));
$image_raw = preg_replace('/[^A-Za-z0-9+\/\=]/', '', $image_raw); // base64만 허용

try {
	$stmt = $pdo->prepare('INSERT INTO devices_v1 (model_name, image_raw, language) VALUES (?, ?, ?)');
	$stmt->execute([$model_name, $image_raw, $language]);
	echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
} catch (PDOException $e) {
	http_response_code(500);
	echo json_encode(['error' => 'DB Error']);
}
