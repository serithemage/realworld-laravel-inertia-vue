# 코드 컨벤션

## PHP

### 스타일 가이드
- PSR-12 코딩 표준 준수
- PHP-CS-Fixer Laravel preset 사용 (`composer format`)
- PHPStan 정적 분석 (`composer analyse`)

### 네이밍 규칙

| 대상 | 규칙 | 예시 |
|------|------|------|
| 클래스 | PascalCase | `ArticleController`, `UserPolicy` |
| 모델 | 단수형 PascalCase | `Article`, `User`, `Comment` |
| 메서드 | camelCase | `getCreatedAtFormattedAttribute()` |
| 변수 | camelCase | `$articleFavorites` |
| 상수 | UPPER_SNAKE_CASE | `MAX_ARTICLES_PER_PAGE` |
| 마이그레이션 파일 | snake_case (타임스탬프 접두사) | `2022_04_17_121856_create_articles_table.php` |
| 라우트 파라미터 | snake_case | `{article:slug}` |
| DB 테이블 | 복수형 snake_case | `articles`, `article_users` |
| DB 컬럼 | snake_case | `user_id`, `created_at` |

### 컨트롤러 패턴
- 리소스 컨트롤러: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`
- 단일 액션 컨트롤러: `__invoke()` (HomeController, ArticleFeedController)
- Inertia 응답: `return inertia('PageName', ['data' => $data])`

### 모델 패턴
- Factory 사용: 모든 모델에 `HasFactory` 트레이트
- 관계 정의: 메서드명은 관계 성격에 따라 명명
  - `hasMany`: 복수형 (`articles()`, `comments()`)
  - `belongsTo`: 단수형 (`user()`, `article()`)
  - `belongsToMany`: 문맥에 따라 (`tags()`, `articleFavorites()`)
- Slug 생성: `Spatie\Sluggable\HasSlug` 트레이트 사용

### 인가 패턴
- Policy 기반 인가 (`$this->authorize('action', $model)`)
- 소유자 검증: `$user->getKey() == $model->user_id`

## Vue.js / JavaScript

### 스타일 가이드
- ESLint + Prettier 사용
- Vue 3 Composition API 권장 (기존 코드는 Options API)
- Single File Component (SFC) `.vue` 파일

### 네이밍 규칙

| 대상 | 규칙 | 예시 |
|------|------|------|
| 컴포넌트 파일 | PascalCase | `ArticlePreview.vue` |
| 페이지 파일 | PascalCase | `Create.vue`, `Show.vue` |
| 디렉토리 | PascalCase (Laravel 리소스 기준) | `Article/`, `User/` |
| Props | camelCase | `article`, `currentUser` |
| 이벤트 | kebab-case | `@article-created` |

### 컴포넌트 구조
```
<template>
  <!-- HTML 템플릿 -->
</template>

<script setup>
// Composition API (권장)
</script>

<style scoped>
/* 스코프드 스타일 (Tailwind 사용 시 최소화) */
</style>
```

### Inertia.js 페이지 패턴
- 페이지 컴포넌트: `resources/js/Pages/` 디렉토리
- 레이아웃: `resources/js/Layouts/AppLayout.vue`
- 공유 컴포넌트: `resources/js/Components/`
- 라우트 링크: `<Link :href="route('name')">` (Inertia Link + Ziggy)

## 커밋 메시지

Conventional Commits 형식:

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Type
- `feat`: 새 기능
- `fix`: 버그 수정
- `docs`: 문서 변경
- `style`: 포맷팅 (코드 동작 변경 없음)
- `refactor`: 리팩토링
- `test`: 테스트 추가/수정
- `chore`: 빌드/도구 변경
- `ci`: CI/CD 설정 변경

### 예시
```
feat(article): add article favorite functionality
fix(auth): fix session invalidation on logout
docs: update CLAUDE.md with test commands
test(model): add User model relationship tests
```

## 디렉토리 규칙

- 새 페이지 추가 시 `resources/js/Pages/{ResourceName}/` 아래에 배치
- 새 컴포넌트 추가 시 `resources/js/Components/` 아래에 배치
- 새 컨트롤러 추가 시 `app/Http/Controllers/` 아래에 배치
- 새 모델 추가 시 반드시 Factory와 Migration을 함께 생성
- 새 Policy 추가 시 `AuthServiceProvider`에 등록

## 테스트 규칙

- Unit 테스트: `tests/Unit/` (모델 관계, 정책 검증)
- Feature 테스트: `tests/Feature/` (HTTP 요청, 컨트롤러 동작)
- 테스트 DB: SQLite in-memory (`:memory:`)
- Factory 활용: 테스트 데이터는 반드시 Factory로 생성
- Trait 사용: `RefreshDatabase` (Feature 테스트), `DatabaseMigrations` 필요 시
