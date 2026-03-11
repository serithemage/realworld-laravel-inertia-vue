# ADR-002: Tailwind CSS 스타일링 전략

## 상태
승인

## 배경
프론트엔드 스타일링 방식을 결정해야 한다. Vue 컴포넌트 기반 개발에서 일관된 디자인 시스템이 필요하다.

## 결정
Tailwind CSS 3.x를 유틸리티 퍼스트 CSS 프레임워크로 사용한다.

## 근거
- 유틸리티 클래스 기반으로 별도 CSS 파일 작성 최소화
- PurgeCSS 내장으로 프로덕션 빌드 시 사용하지 않는 CSS 제거
- Laravel Mix와 PostCSS를 통한 원활한 통합
- Vue SFC 내에서 클래스 직접 적용으로 컴포넌트 단위 스타일 관리
- Responsive 디자인 유틸리티 내장

## 설정
- `tailwind.config.js`에서 content 경로 설정: `resources/**/*.{blade.php,js,vue}`
- PostCSS 플러그인: `tailwindcss`, `autoprefixer`
- `resources/css/app.css`에서 `@tailwind` 디렉티브 사용

## 영향
- HTML에 클래스가 많아져 템플릿이 길어질 수 있음
- 팀원 모두 Tailwind 유틸리티 클래스 숙지 필요
- 커스텀 디자인 토큰은 `tailwind.config.js`의 `theme.extend`에서 관리

## 대안
- Bootstrap: 프리빌트 컴포넌트 풍부하나 커스터마이징 제한적
- Scoped CSS: 컴포넌트별 스타일 분리 가능하나 일관성 유지 어려움
- CSS Modules: 네이밍 충돌 방지되나 유틸리티 편의성 부재
