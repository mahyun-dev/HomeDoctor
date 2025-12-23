
<?php
require_once __DIR__ . '/lang.php';
require_once __DIR__ . '/db.php';
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Home Doctor</title>
	<!-- Pretendard Webfont -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css">
	<link rel="stylesheet" href="/assets/css/style.css">
	<style>
		:root {
			--main-blue: #0066FF;
			--soft-gray: #F1F5F9;
			--radius: 24px;
			--shadow: 0 4px 24px 0 rgba(0,0,0,0.07);
		}
		body {
			font-family: 'Pretendard', sans-serif;
			background: var(--soft-gray);
			margin: 0;
			min-height: 100vh;
		}
		.container {
			max-width: 1200px;
			margin: 0 auto;
			padding: 32px 16px 96px 16px;
		}
		.header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 24px 0 8px 0;
		}
		.logo {
			font-size: 2rem;
			font-weight: 800;
			color: var(--main-blue);
			letter-spacing: -1px;
		}
		.lang-switch {
			position: relative;
		}
		.lang-btn {
			background: white;
			border: none;
			border-radius: 50%;
			width: 44px;
			height: 44px;
			box-shadow: var(--shadow);
			cursor: pointer;
			transition: box-shadow 0.2s;
		}
		.lang-btn:active {
			box-shadow: 0 0 0 2px var(--main-blue);
		}
		.lang-dropdown {
			display: none;
			position: absolute;
			right: 0;
			top: 52px;
			background: white;
			border-radius: var(--radius);
			box-shadow: var(--shadow);
			min-width: 120px;
			z-index: 10;
			animation: fadeIn 0.3s;
		}
		.lang-switch.open .lang-dropdown {
			display: block;
		}
		.lang-dropdown button {
			width: 100%;
			background: none;
			border: none;
			padding: 12px 20px;
			font-size: 1rem;
			cursor: pointer;
			text-align: left;
		}
		.lang-dropdown button:hover {
			background: var(--soft-gray);
		}
		@keyframes fadeIn {
			from { opacity: 0; transform: translateY(-10px); }
			to { opacity: 1; transform: translateY(0); }
		}
		.grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
			gap: 32px;
		}
		.card {
			background: #fff;
			border-radius: var(--radius);
			box-shadow: var(--shadow);
			padding: 32px 24px;
			min-height: 220px;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			transition: box-shadow 0.2s;
		}
		.card.skeleton {
			background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
			background-size: 200% 100%;
			animation: skeleton 1.2s infinite linear;
		}
		@keyframes skeleton {
			0% { background-position: 200% 0; }
			100% { background-position: -200% 0; }
		}
		.floating-btn {
			position: fixed;
			bottom: 32px;
			right: 32px;
			background: var(--main-blue);
			color: #fff;
			border: none;
			border-radius: 50%;
			width: 72px;
			height: 72px;
			box-shadow: var(--shadow);
			font-size: 2.2rem;
			display: flex;
			align-items: center;
			justify-content: center;
			cursor: pointer;
			z-index: 100;
			transition: background 0.2s;
		}
		.floating-btn:active {
			background: #0050cc;
		}
		@media (max-width: 600px) {
			.container { padding: 16px 4px 88px 4px; }
			.header { flex-direction: column; align-items: flex-start; gap: 8px; }
			.floating-btn { right: 16px; bottom: 16px; width: 60px; height: 60px; font-size: 1.7rem; }
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="header">
			<div class="logo">🏠 Home Doctor</div>
			<div class="lang-switch" id="langSwitch">
				<button class="lang-btn" id="langBtn" aria-label="언어 변경">
					<span style="font-size:1.5rem;">🌐</span>
				</button>
				<div class="lang-dropdown" id="langDropdown">
					<form method="post" style="margin:0;">
						<button type="submit" name="set_lang" value="ko">한국어</button>
						<button type="submit" name="set_lang" value="en">English</button>
					</form>
				</div>
			</div>
		</div>
		<h1 style="margin-top:0; color:#222; font-size:2.2rem; font-weight:700;">
			<?php echo htmlspecialchars($i18n['welcome']); ?>
		</h1>
		<div class="grid" id="deviceGrid">
			<!-- 스켈레톤 로딩 카드 예시 -->
			<div class="card skeleton"></div>
			<div class="card skeleton"></div>
			<div class="card skeleton"></div>
		</div>
	</div>
	<button class="floating-btn" id="quickScanBtn" title="Quick Scan">
		<span>📷</span>
	</button>
	<script src="/assets/js/app.js"></script>
	<script>
	// 언어 드롭다운 애니메이션
	const langSwitch = document.getElementById('langSwitch');
	const langBtn = document.getElementById('langBtn');
	langBtn.onclick = (e) => {
		e.preventDefault();
		langSwitch.classList.toggle('open');
	};
	document.addEventListener('click', (e) => {
		if (!langSwitch.contains(e.target)) langSwitch.classList.remove('open');
	});
	</script>
</body>
</html>
