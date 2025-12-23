
// Home Doctor JS: 기기 목록, 스캔, Gemini 연동
const API_KEY = 'AIzaSyDywduY8rmC6ZgM7XHnL8z1cpQxi3G4Wf8';
const GEMINI_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro-vision:generateContent?key=' + API_KEY;

// 기기 목록 불러오기
async function fetchDevices() {
	const lang = document.documentElement.lang || 'ko';
	const grid = document.getElementById('deviceGrid');
	grid.innerHTML = '<div class="card skeleton"></div><div class="card skeleton"></div><div class="card skeleton"></div>';
	try {
		const res = await fetch('/api/fetch.php?language=' + lang);
		const data = await res.json();
		grid.innerHTML = '';
		if (!data.devices || !data.devices.length) {
			grid.innerHTML = `<div class="card">${lang === 'ko' ? '등록된 기기가 없습니다.' : 'No devices registered.'}</div>`;
			return;
		}
		for (const d of data.devices) {
			grid.innerHTML += `<div class="card">
				<img src="data:image/jpeg;base64,${d.image_raw}" alt="device" style="max-width:100%;max-height:120px;border-radius:16px;box-shadow:0 2px 8px #0001;" loading="lazy"><br>
				<div style="font-weight:600;font-size:1.1rem;">${d.model_name}</div>
				<div style="font-size:0.9rem;color:#888;">${d.created_at}</div>
				<button onclick="deleteDevice(${d.id})" style="margin-top:12px;background:#eee;border:none;border-radius:12px;padding:6px 18px;cursor:pointer;">${lang === 'ko' ? '삭제' : 'Delete'}</button>
			</div>`;
		}
	} catch (e) {
		grid.innerHTML = `<div class="card">API Error</div>`;
	}
}

window.deleteDevice = async function(id) {
	if (!confirm('정말 삭제하시겠습니까?')) return;
	await fetch('/api/delete.php', {
		method: 'POST',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
		body: 'id=' + encodeURIComponent(id)
	});
	fetchDevices();
}

// 퀵스캔 버튼 이벤트
document.getElementById('quickScanBtn').onclick = () => {
	openImageInput();
};

function openImageInput() {
	const input = document.createElement('input');
	input.type = 'file';
	input.accept = 'image/*';
	input.onchange = async (e) => {
		const file = e.target.files[0];
		if (!file) return;
		const reader = new FileReader();
		reader.onload = async function(ev) {
			const base64 = ev.target.result.split(',')[1];
			// 이미지 리사이즈(800px 이하) 후 Gemini 호출
			const resized = await resizeImage(ev.target.result, 800);
			await scanWithGemini(resized);
		};
		reader.readAsDataURL(file);
	};
	input.click();
}

function resizeImage(dataUrl, maxSize) {
	return new Promise((resolve) => {
		const img = new Image();
		img.onload = function() {
			let w = img.width, h = img.height;
			if (w > maxSize || h > maxSize) {
				if (w > h) { h = h * (maxSize / w); w = maxSize; }
				else { w = w * (maxSize / h); h = maxSize; }
			}
			const canvas = document.createElement('canvas');
			canvas.width = w; canvas.height = h;
			const ctx = canvas.getContext('2d');
			ctx.drawImage(img, 0, 0, w, h);
			resolve(canvas.toDataURL('image/jpeg', 0.85).split(',')[1]);
		};
		img.src = dataUrl;
	});
}

async function scanWithGemini(base64img) {
	const lang = document.documentElement.lang || 'ko';
	const grid = document.getElementById('deviceGrid');
	grid.innerHTML = '<div class="card skeleton"></div>';
	try {
		const prompt = lang === 'ko' ?
			'이 이미지는 가전제품의 모델명/제조사 스티커입니다. 모델명과 제조사를 JSON으로만 응답해줘.' :
			'This image is a sticker of a home appliance. Please extract model name and manufacturer, and respond only in JSON.';
		const res = await fetch(GEMINI_URL, {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({
				contents: [{
					parts: [
						{ text: prompt },
						{ inlineData: { mimeType: 'image/jpeg', data: base64img } }
					]
				}]
			})
		});
		const data = await res.json();
		let model_name = '', manufacturer = '';
		try {
			const json = JSON.parse(data.candidates[0].content.parts[0].text);
			model_name = json.model_name || json.model || '';
			manufacturer = json.manufacturer || '';
		} catch (e) { model_name = ''; }
		if (!model_name) {
			grid.innerHTML = `<div class="card">AI 인식 실패</div>`;
			return;
		}
		// DB 저장
		const saveRes = await fetch('/api/save.php', {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: `model_name=${encodeURIComponent(model_name)}&image_raw=${encodeURIComponent(base64img)}&language=${lang}`
		});
		const saveData = await saveRes.json();
		if (saveData.success) fetchDevices();
		else grid.innerHTML = `<div class="card">DB Error</div>`;
	} catch (e) {
		grid.innerHTML = `<div class="card">Gemini API Error</div>`;
	}
}

// 최초 로딩 시 목록
window.addEventListener('DOMContentLoaded', fetchDevices);
