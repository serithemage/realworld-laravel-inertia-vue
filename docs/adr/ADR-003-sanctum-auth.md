# ADR-003: Laravel Sanctum 인증 전략

## 상태
승인

## 배경
사용자 인증 및 세션 관리 방식을 결정해야 한다. Inertia.js 기반 SPA에서 안전하고 간편한 인증이 필요하다.

## 결정
Laravel Sanctum을 세션 기반 인증으로 사용한다.

## 근거
- Inertia.js는 동일 도메인에서 동작하므로 세션 쿠키 기반 인증이 자연스러움
- Sanctum은 SPA 인증(쿠키 기반)과 API 토큰 인증 모두 지원
- Laravel 내장 `auth` 미들웨어와 완벽 통합
- CSRF 토큰 자동 처리

## 인증 흐름
1. **로그인:** `POST /login` → `Auth::attempt()` → 세션 생성 → 리다이렉트
2. **회원가입:** `POST /register` → `User::create()` → `Auth::login()` → 리다이렉트
3. **로그아웃:** `DELETE /logout` → `Auth::logout()` → 세션 무효화 → 토큰 재생성
4. **인증 확인:** `auth` 미들웨어로 보호된 라우트

## 라우트 보호 현황
- `auth` 미들웨어: 글 작성/수정/삭제, 댓글 작성, 즐겨찾기, 설정
- `guest` 미들웨어: 로그인, 회원가입 페이지
- 미보호: 홈, 글 목록/상세, 프로필 조회, 태그

## 영향
- 세션 스토리지 필요 (기본 파일, 프로덕션에서는 Redis/DB 권장)
- 동일 도메인 제약 (모바일 앱은 API 토큰 방식 별도 구현 필요)
- Bcrypt 해싱 (테스트 시 라운드 4로 최적화)

## 대안
- JWT (tymon/jwt-auth): 무상태 인증이나 토큰 관리 복잡, XSS 취약점 주의 필요
- Laravel Passport: OAuth2 전체 구현이나 SPA에는 과잉
- Laravel Breeze: 스캐폴딩 제공이나 Inertia 통합 시 커스터마이징 필요
