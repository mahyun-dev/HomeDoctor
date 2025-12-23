# 🏠 Home Doctor (홈닥터)

"사진 한 장으로 관리하는 내 집 안의 가전 전문가"

## 프로젝트 개요
- 흩어진 가전 매뉴얼과 브랜드별 앱을 통합, 누구나 쉽게 가전제품을 관리할 수 있는 웹 서비스
- AI(Gemini) 기반 모델명 추출, 다국어 지원, 고령층/1인가구 친화적 UI

## 폴더 구조
```
├── index.php               # 메인 컨트롤러 및 반응형 뷰
├── db.php                  # DB 연결 및 테이블 자동 생성
├── lang.php                # 언어 엔진 (쿠키 기반)
├── /lang                   # 다국어 JSON
│   ├── ko.json             # 한국어
│   └── en.json             # 영어
├── /api                    # 서버 API
│   ├── save.php            # 기기 저장
│   ├── fetch.php           # 목록 조회
│   └── delete.php          # 삭제
└── /assets                 # 정적 자원
	├── css/style.css       # 커스텀 UI 스타일
	└── js/app.js           # Gemini 연동/클라이언트 로직
```

## 주요 기능
- AI 이미지 스캔(Gemini Vision) → 모델명/제조사 추출
- 기기 등록/조회/삭제 (MySQL)
- 다국어(한국어/영어) 실시간 전환
- 반응형 웹, 스켈레톤 로딩, 플로팅 버튼 UI

## 설치 및 실행
1. PHP 8.x, MySQL 8.x 환경 필요
2. DB 계정정보는 db.php에서 환경변수 또는 직접 수정
3. 웹서버 루트에 업로드 후 접속 (최초 접속 시 DB/테이블 자동 생성)

## 기술스택
- PHP, MySQL, JavaScript(ES6+), Gemini API, Pretendard Webfont
- 보안: PDO(Prepared), XSS 방지, 이미지 리사이즈(Base64)

## 확장성
- 실시간 고장 알림(Web Push), 가족/지인 공유, 앱 설치 없이 사용 가능

## 라이선스
MIT