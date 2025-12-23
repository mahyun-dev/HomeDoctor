
<?php
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	echo json_encode(['error' => 'Method Not Allowed']);
	exit;
}
$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
	http_response_code(400);
	echo json_encode(['error' => 'Invalid ID']);
	exit;
}
try {
	$stmt = $pdo->prepare('DELETE FROM devices_v1 WHERE id = ?');
	$stmt->execute([$id]);
	echo json_encode(['success' => true]);
} catch (PDOException $e) {
	http_response_code(500);
	echo json_encode(['error' => 'DB Error']);
}
