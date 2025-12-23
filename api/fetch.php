
<?php
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json; charset=utf-8');
$language = $_GET['language'] ?? 'ko';
if (!in_array($language, ['ko', 'en'])) $language = 'ko';
try {
	$stmt = $pdo->prepare('SELECT id, model_name, image_raw, created_at FROM devices_v1 WHERE language = ? ORDER BY created_at DESC');
	$stmt->execute([$language]);
	$rows = $stmt->fetchAll();
	foreach ($rows as &$row) {
		$row['model_name'] = htmlspecialchars($row['model_name']);
	}
	echo json_encode(['devices' => $rows]);
} catch (PDOException $e) {
	http_response_code(500);
	echo json_encode(['error' => 'DB Error']);
}
