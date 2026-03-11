# RealWorld Laravel Inertia Vue ("Conduit")

[![CI](https://github.com/sawirricardo/realworld-laravel-inertia-vue/actions/workflows/ci.yml/badge.svg)](https://github.com/sawirricardo/realworld-laravel-inertia-vue/actions/workflows/ci.yml)

> Laravel 9 + Inertia.js + Vue 3 기반의 풀스택 RealWorld 애플리케이션 (Medium.com 클론)

[RealWorld](https://github.com/gothinkster/realworld) 명세를 따르는 CRUD, 인증, 라우팅, 페이지네이션 등을 포함한 풀스택 애플리케이션입니다.

## 기술 스택

| 레이어 | 기술 | 버전 |
|--------|------|------|
| 백엔드 | Laravel | 9.x |
| 프론트엔드 | Vue.js | 3.x |
| SSR 브릿지 | Inertia.js | 0.11.x |
| CSS | Tailwind CSS | 3.x |
| 인증 | Laravel Sanctum | 2.x |
| 빌드 | Laravel Mix | 6.x |

## 주요 기능

- 사용자 인증 (회원가입, 로그인, 로그아웃)
- Article CRUD (생성, 조회, 수정, 삭제)
- 댓글 시스템
- 글 즐겨찾기 (Favorite)
- 사용자 팔로우 (Follow)
- 태그 기반 필터링
- 사용자 프로필 및 설정

## 설치 및 실행

### 사전 요구사항

- PHP 8.0.2+
- Composer
- Node.js 18+
- MySQL 또는 SQLite

### 설치

```bash
# 저장소 클론
git clone https://github.com/sawirricardo/realworld-laravel-inertia-vue.git
cd realworld-laravel-inertia-vue

# 의존성 설치
composer install
npm install

# 환경 설정
cp .env.example .env
php artisan key:generate

# 데이터베이스 초기화
php artisan migrate --seed

# 개발 서버 실행
php artisan serve     # 백엔드 (localhost:8000)
npm run watch         # 프론트엔드 (파일 변경 감지)
```

## 테스트

```bash
# PHPUnit 전체 테스트
php artisan test

# Unit 테스트만
php artisan test --testsuite=Unit

# Feature 테스트만
php artisan test --testsuite=Feature

# 특정 테스트
php artisan test --filter=ArticleTest
```

## 코드 품질

```bash
# PHP 코드 포맷팅
composer format

# PHP 정적 분석
composer analyse

# ESLint (JavaScript/Vue)
npm run lint

# Prettier (코드 포맷팅)
npm run format:check
```

## 프로젝트 구조

```
app/
├── Http/Controllers/    # Inertia 페이지 반환 컨트롤러
├── Models/              # Eloquent 모델 (User, Article, Comment, Tag)
├── Policies/            # 인가 정책 (소유자 검증)
└── Providers/           # 서비스 프로바이더

resources/js/
├── Components/          # 재사용 가능한 Vue 컴포넌트
├── Layouts/             # 공통 레이아웃
└── Pages/               # Inertia 페이지 컴포넌트

tests/
├── Unit/Models/         # 모델 관계, 접근자 테스트
├── Unit/Policies/       # 인가 정책 테스트
└── Feature/             # HTTP 요청, 컨트롤러 테스트

docs/
├── code-conventions.md  # 코드 컨벤션 가이드
└── adr/                 # Architecture Decision Records
```

## CI/CD

- **pre-commit**: ESLint + Prettier (JS/Vue), PHP-CS-Fixer (PHP)
- **pre-push**: PHPUnit 테스트, PHPStan 정적 분석
- **GitHub Actions**: PHP 테스트, 코드 품질 검사, 프론트엔드 빌드 검증

## 문서

- [코드 컨벤션](docs/code-conventions.md)
- [Architecture Decision Records](docs/adr/README.md)

## 기여 가이드

1. 이슈를 확인하거나 새 이슈를 생성합니다
2. 기능 브랜치를 생성합니다 (`feat/feature-name`)
3. [Conventional Commits](https://www.conventionalcommits.org/) 형식으로 커밋합니다
4. PR을 생성합니다
5. CI 검사가 통과하면 리뷰를 요청합니다

### 커밋 메시지 형식

```
feat(scope): 새 기능 추가
fix(scope): 버그 수정
docs: 문서 변경
test: 테스트 추가/수정
chore: 빌드/도구 변경
```

## 라이선스

[MIT License](https://opensource.org/licenses/MIT)
