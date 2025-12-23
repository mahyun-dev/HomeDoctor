
<?php
// db.php - PDO 기반 MySQL 연결 및 devices_v1 테이블 자동 생성

$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_NAME = getenv('DB_NAME') ?: 'homedoctor';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';
$DB_CHARSET = 'utf8mb4';

$dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=$DB_CHARSET";
$options = [
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES => false,
];

try {
	$pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
	die('DB 연결 실패: ' . htmlspecialchars($e->getMessage()));
}

// devices_v1 테이블 자동 생성 (없을 시)
$createTableSQL = "CREATE TABLE IF NOT EXISTS devices_v1 (
	id INT AUTO_INCREMENT PRIMARY KEY,
	model_name VARCHAR(255) NOT NULL,
	image_raw LONGTEXT NOT NULL,
	language VARCHAR(8) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
	$pdo->exec($createTableSQL);
} catch (PDOException $e) {
	die('테이블 생성 오류: ' . htmlspecialchars($e->getMessage()));
}
