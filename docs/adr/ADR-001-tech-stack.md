# ADR-001: Laravel + Inertia.js + Vue 3 스택 선택

## 상태
승인

## 배경
RealWorld("Conduit") 명세를 구현하는 풀스택 웹 애플리케이션을 개발해야 한다. CRUD, 인증, 라우팅, 페이지네이션 등 일반적인 웹 기능을 포함하며, 프로덕션 수준의 코드 품질이 요구된다.

## 결정
- **백엔드:** Laravel 9 (PHP 8.0.2+)
- **프론트엔드:** Vue 3 (Composition API)
- **SSR 브릿지:** Inertia.js
- **빌드 도구:** Laravel Mix (Webpack 기반)

## 근거

### Laravel 선택
- PHP 생태계에서 가장 성숙한 풀스택 프레임워크
- Eloquent ORM, 마이그레이션, 인증, 인가 등 내장 기능이 풍부
- RealWorld 명세의 CRUD/Auth 요구사항에 적합

### Inertia.js 선택
- API 레이어 없이 서버 사이드 라우팅 + 클라이언트 SPA 경험 제공
- Laravel 컨트롤러에서 직접 Vue 페이지로 데이터 전달
- REST API + SPA 분리 방식 대비 개발 생산성 향상
- CSRF, 세션 인증이 기본 동작

### Vue 3 선택
- Inertia.js의 Vue 3 공식 어댑터 지원
- Composition API로 로직 재사용성 향상
- 충분한 생태계와 커뮤니티

## 영향
- API 엔드포인트가 별도로 필요 없음 (Inertia가 처리)
- 모바일 앱이나 외부 클라이언트 지원 시 별도 API 레이어 추가 필요
- 프론트/백엔드가 동일 저장소에서 관리됨 (모노레포)

## 대안
- Laravel + Livewire: JavaScript 프레임워크 없이 동적 UI 가능하나 복잡한 인터랙션에 한계
- Laravel API + Vue SPA (분리): 유연하나 개발 복잡도 증가, CORS/인증 처리 부담
- Next.js + API: 별도 백엔드 필요, Laravel 생태계 활용 불가
