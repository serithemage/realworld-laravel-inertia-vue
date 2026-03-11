# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

RealWorld("Conduit") 풀스택 애플리케이션 - Medium.com 클론.
Laravel 9 + Vue 3 + Inertia.js + Tailwind CSS 기반. REST API 없이 Inertia.js가 서버-클라이언트 브릿지 역할을 수행한다.

## Commands

```bash
# 개발 서버
php artisan serve              # 백엔드 (localhost:8000)
npm run watch                  # 프론트엔드 빌드 + 파일 감지
npm run hot                    # HMR 개발 모드

# 빌드
npm run dev                    # 개발 빌드 (1회)
npm run production             # 프로덕션 빌드

# 테스트
php artisan test                       # 전체 테스트
php artisan test --filter=ClassName    # 특정 테스트
php artisan test --testsuite=Unit      # Unit만
php artisan test --testsuite=Feature   # Feature만

# 코드 품질
composer format                # PHP-CS-Fixer (PSR-12 + Laravel preset)
composer analyse               # PHPStan 정적 분석

# TypeScript 타입 생성
php artisan typescript:generate  # resources/js/models.d.ts 생성

# DB 초기화
php artisan migrate --seed
```

## Architecture

### Inertia.js 패턴 (핵심)

이 프로젝트는 SPA 라우터나 REST API를 사용하지 않는다. 모든 라우팅은 Laravel `routes/web.php`에서 처리하고, 컨트롤러가 `inertia('PageName', $data)` 형태로 Vue 페이지 컴포넌트를 반환한다.

- 컨트롤러 → `inertia()` 응답 → Vue 페이지 렌더링
- 인증 상태는 `HandleInertiaRequests` 미들웨어가 모든 페이지에 `$page.props.auth`로 공유
- 프론트엔드 라우트 헬퍼: `route('name', params)` (Ziggy 패키지)
- 폼 처리: Inertia `useForm()` → `form.post()`, `form.put()` 등

### 컨트롤러 패턴

- **리소스 컨트롤러**: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`
- **단일 액션 컨트롤러**: `__invoke()` (HomeController, ArticleFeedController)
- 인가: Policy 기반 (`$this->authorize('action', $model)`)

### 모델 패턴

- `Model::unguard()` 활성화 (AppServiceProvider) — `$fillable`/`$guarded` 미사용
- Slug 자동 생성: `HasSlug` 트레이트 + `getSlugOptions()` 메서드 (Article, Tag)
- 비프로덕션 환경에서 lazy loading 방지 활성화

### Vue 컴포넌트

- 기존 코드는 Options API 사용, Composition API 권장
- 페이지: `resources/js/Pages/{ResourceName}/` (PascalCase 디렉토리)
- 레이아웃: `resources/js/Layouts/AppLayout.vue`
- 공유 컴포넌트: `resources/js/Components/`

### DB 관계 구조

```
User 1──N Article 1──N Comment (N──1 User)
User N──N User (followers)
User N──N Article (article_users/즐겨찾기)
Article N──N Tag (article_tags)
```

피벗 테이블: `article_users`, `article_tags`, `followers`
Tag 모델은 timestamps 없음.

## Code Style

- **PHP**: PSR-12 + Laravel preset. `composer format`으로 적용
- **JS/Vue**: Prettier (semi: false, singleQuote: true, tabWidth: 2, printWidth: 100)
- **커밋**: Conventional Commits — `<type>(<scope>): <subject>` (feat, fix, docs, refactor, test, chore)
- 상세 컨벤션: `docs/code-conventions.md` 참조

## Known Issues

- Tag 모델의 `articles()` 관계가 `hasMany`로 정의되어 있으나, `article_tags` 피벗 테이블을 사용하므로 `belongsToMany`가 올바름
- `UserController@update`: 검증만 수행하고 실제 `update()` 호출 누락
- `UserController@destroy`: `Auth::logout()` 후 `Auth::user()->delete()` 호출 — 이미 로그아웃된 상태에서 에러 발생 가능
- `ArticleFeedController`: `auth` 미들웨어 미적용으로 비인증 사용자 접근 시 에러
- `Article/Show.vue`의 팔로우 버튼이 `articles.favorite` 라우트를 사용 — 별도 팔로우 라우트 필요
