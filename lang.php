
<?php
// lang.php - 쿠키 기반 언어 스위칭 및 다국어 JSON 로딩

// 지원 언어 목록
$supported_langs = ['ko', 'en'];

// 언어 선택 처리 (POST)
if (isset($_POST['set_lang']) && in_array($_POST['set_lang'], $supported_langs)) {
	setcookie('lang', $_POST['set_lang'], time() + 60*60*24*30, '/');
	$_COOKIE['lang'] = $_POST['set_lang'];
	header('Location: ' . $_SERVER['REQUEST_URI']);
	exit;
}

// 현재 언어 결정 (쿠키 → 브라우저 → 기본값)
if (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], $supported_langs)) {
	$lang = $_COOKIE['lang'];
} else {
	$browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '', 0, 2);
	$lang = in_array($browser_lang, $supported_langs) ? $browser_lang : 'ko';
}

// 다국어 JSON 로딩
$lang_file = __DIR__ . "/lang/{$lang}.json";
if (!file_exists($lang_file)) {
	$lang_file = __DIR__ . "/lang/ko.json";
}
$i18n = json_decode(file_get_contents($lang_file), true) ?: [];
