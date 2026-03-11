---
marp: true
theme: default
paginate: true
size: 16:9
style: |
  section {
    font-family: 'Pretendard', 'Apple SD Gothic Neo', sans-serif;
  }
  h1 {
    font-size: 2.2em;
  }
  h2 {
    font-size: 1.6em;
  }
  table {
    font-size: 0.85em;
  }
  code {
    font-size: 0.85em;
  }
  section.lead h1 {
    font-size: 2.8em;
  }
  section.lead h2 {
    font-size: 1.4em;
  }
---

<!-- _class: lead -->

# 바이브 코딩 마이그레이션

## RealWorld Laravel Inertia Vue 프로젝트

---

# 목차

1. 프로젝트 현황
2. 마이그레이션 목표
3. Phase 1: 문서화
4. Phase 2: GitHub 이슈 체계화
5. Phase 3: Claude Code 스킬
6. Phase 4: 테스트 인프라
7. Phase 5: 코드 리뷰 및 보안 수정
8. Phase 6: 품질 자동화
9. 실행 결과 요약
10. 다음 단계

---

# 프로젝트 현황

## 기술 스택

| 레이어 | 기술 | 버전 |
|--------|------|------|
| 백엔드 | Laravel | 9.x |
| 프론트엔드 | Vue.js | 3.x |
| SSR 브릿지 | Inertia.js | 0.11.x |
| CSS | Tailwind CSS | 3.x |
| 인증 | Laravel Sanctum | 2.x |
| 빌드 | Laravel Mix | 6.x |

> RealWorld("Conduit") — Medium.com 클론 풀스택 애플리케이션

---

# 프로젝트 현황 — 문제점

## 바이브 코딩 인프라 부재

- CLAUDE.md **없음** — AI가 프로젝트 구조 파악 불가
- ADR **없음** — 아키텍처 결정 근거 부재
- 코드 컨벤션 문서 **없음**
- 테스트 커버리지 **0%** (예제 테스트만 존재)
- ESLint / Prettier **미설정**
- tsconfig.json **없음** (TypeScript 패키지는 설치됨)
- CI/CD **없음** (GitHub Actions 미설정)
- Git Hooks **없음**
- Claude Code 스킬 **없음**

---

# 마이그레이션 목표

## 6개 Phase를 통한 바이브 코딩 전환

```
Phase 1 (문서화) → Phase 2 (이슈) → Phase 3 (스킬)
                                          │
                                          ▼
                   Phase 5 (리뷰) ← Phase 4 (테스트)
                         │
                         ▼
                   Phase 6 (자동화) → README 업데이트
```

**핵심 원칙:** AI가 코드베이스를 정확히 이해하고, 안전하게 수정할 수 있는 환경 구축

---

<!-- _class: lead -->

# Phase 1
## 프로젝트 분석 및 문서화

---

# Phase 1: 문서화 — 산출물

| 산출물 | 파일 | 목적 |
|--------|------|------|
| CLAUDE.md | `CLAUDE.md` | AI 컨텍스트 (빌드, 아키텍처, DB, 라우팅) |
| 코드 컨벤션 | `docs/code-conventions.md` | PHP PSR-12, Vue SFC, Conventional Commits |
| ADR 8개 | `docs/adr/ADR-001~008.md` | 아키텍처 결정 기록 |
| TypeScript | `tsconfig.json` | 타입 검사 설정 |
| ESLint | `eslint.config.mjs` | JS/Vue 린트 |
| Prettier | `.prettierrc` | 코드 포맷팅 |

---

# Phase 1: ADR 목록

| ADR | 제목 |
|-----|------|
| ADR-001 | Laravel + Inertia.js + Vue 3 스택 선택 |
| ADR-002 | Tailwind CSS 스타일링 전략 |
| ADR-003 | Laravel Sanctum 인증 전략 |
| ADR-004 | Spatie 패키지 활용 전략 |
| ADR-005 | 데이터베이스 스키마 설계 |
| ADR-006 | 프론트엔드 TypeScript 도입 |
| ADR-007 | 테스트 전략 (PHPUnit + Laravel Dusk) |
| ADR-008 | CI/CD 전략 (Git Hooks + GitHub Actions) |

> 각 ADR: 상태, 배경, 결정, 근거, 영향, 대안 포함

---

<!-- _class: lead -->

# Phase 2
## GitHub 이슈 체계화

---

# Phase 2: GitHub 이슈 체계화

## 계획

- Epic 이슈 + Phase별 작업 이슈 등록
- 라벨 체계: `epic`, `documentation`, `testing`, `infra`, `security`, `enhancement`
- 각 이슈에 인수조건 + 의존 관계 명시

## 현재 상태: 건너뜀

> 원본 저장소(`sawirricardo/realworld-laravel-inertia-vue`)에 push/issue 권한 없음
> 사용자 소유 저장소로 **fork 후 실행 필요**

---

<!-- _class: lead -->

# Phase 3
## Claude Code 스킬 생성

---

# Phase 3: Claude Code 스킬

| 스킬 | 용도 | 줄 수 |
|------|------|-------|
| `/gen-test` | ADR-007 패턴에 따른 테스트 자동 생성 | 141줄 |
| `/manage-issues` | GitHub 이슈 생성/업데이트/닫기 + 인수조건 검증 | 100줄 |
| `/code-review` | 프로젝트 컨벤션 기반 코드 리뷰 (5계층 체크리스트) | 91줄 |
| `/db-migrate` | 마이그레이션 생성 + 모델/팩토리 동시 업데이트 | 101줄 |

**위치:** `.claude/commands/`
**제약:** 각 스킬 500줄 미만, 상세 내용은 기존 문서 참조

---

<!-- _class: lead -->

# Phase 4
## 테스트 인프라 구축

---

# Phase 4: 테스트 구조

## PHPUnit 설정

- `phpunit.xml`: SQLite in-memory DB 활성화
- `RefreshDatabase` 트레이트로 테스트 간 격리

## 3계층 테스트 전략

| 계층 | 도구 | 대상 |
|------|------|------|
| Unit | PHPUnit | 모델 관계, Policy 인가 |
| Feature | PHPUnit | HTTP 요청, 컨트롤러 |
| E2E | Laravel Dusk | 브라우저 시나리오 |

---

# Phase 4: Unit 테스트 (7개 파일)

## Model 테스트

| 파일 | 테스트 수 | 커버리지 |
|------|----------|----------|
| `UserTest.php` | 9 | 관계 5개, fillable, hidden, casts, factory |
| `ArticleTest.php` | 9 | 관계 4개, slug, formatted date, factory |
| `CommentTest.php` | 5 | 관계 2개, formatted date, appends, factory |
| `TagTest.php` | 5 | 관계, slug, no timestamps, factory |

## Policy 테스트

| 파일 | 테스트 수 | 커버리지 |
|------|----------|----------|
| `ArticlePolicyTest.php` | 4 | update/delete 소유자/비소유자 |
| `CommentPolicyTest.php` | 4 | update/delete 소유자/비소유자 |
| `UserPolicyTest.php` | 4 | update/delete 본인/타인 |

---

# Phase 4: Feature 테스트 (7개 파일)

| 파일 | 테스트 수 | 커버리지 |
|------|----------|----------|
| `RegistrationTest.php` | 5 | 회원가입 폼, 정상 등록, 필드 검증 |
| `LoginTest.php` | 4 | 로그인 폼, 정상/실패, 로그아웃 |
| `ArticleTest.php` | 10 | CRUD 전체, 인증/인가, 검증 |
| `CommentTest.php` | 3 | 생성, 비인증 거부, 검증 |
| `FavoriteTest.php` | 3 | 즐겨찾기/취소, 비인증 거부 |
| `ProfileTest.php` | 3 | 프로필 조회, 설정, 비인증 거부 |
| `HomeTest.php` | 3 | 홈 렌더링, 글 표시, 태그 필터 |

**합계: Unit 40개 + Feature 31개 = 71개 테스트 케이스**

---

<!-- _class: lead -->

# Phase 5
## 코드 리뷰 및 보안 수정

---

# Phase 5: 발견된 보안 이슈

## Critical (3건 수정 완료)

| # | 이슈 | 파일 |
|---|------|------|
| 1 | CommentController IDOR — authorize 누락 | `CommentController.php` |
| 2 | UserController@destroy — logout 후 delete 호출 | `UserController.php` |
| 3 | UserController@update — update 로직 누락 | `UserController.php` |

## High (2건 수정 완료)

| # | 이슈 | 파일 |
|---|------|------|
| 4 | ArticleFeedController auth 미들웨어 누락 | `routes/web.php` |
| 5 | 로그인 Rate Limiting 누락 | `routes/web.php` |

---

# Phase 5: 수정 상세

## CommentController — IDOR 수정

```php
// Before: 권한 검증 없이 누구나 수정/삭제 가능
public function update(Comment $comment) { ... }

// After: 소유자만 수정/삭제 가능
public function update(Comment $comment) {
    $this->authorize('update', $comment);  // 추가
    ...
}
```

## UserController@destroy — 로직 버그 수정

```php
// Before: Auth::logout() → Auth::user()->delete() (NPE 발생)
// After:  $user = Auth::user() → Auth::logout() → $user->delete()
```

---

# Phase 5: 남은 Known Issues

| 이슈 | 심각도 | 상태 |
|------|--------|------|
| Article/Comment/Tag `$fillable` 미설정 | Medium | 문서화 (Model::unguard 사용) |
| Tag `articles()` 관계 오류 (`hasMany` → `belongsToMany`) | Medium | 문서화 |
| HomeController/TagController 불완전한 eager loading | Low | 문서화 |
| ArticleController tag 입력 길이 제한 없음 | Low | 문서화 |

> CLAUDE.md의 Known Issues 섹션에 기록됨

---

<!-- _class: lead -->

# Phase 6
## 품질 자동화

---

# Phase 6: Git Hooks (Husky + lint-staged)

## pre-commit

```
JS/Vue 파일 → ESLint --fix + Prettier --write
PHP 파일   → PHP-CS-Fixer fix
문서 파일   → 바이패스 (검사 생략)
```

## pre-push

```
PHP 코드 → PHPUnit 테스트 실행
PHP 코드 → PHPStan 정적 분석
문서 파일 → 바이패스 (검사 생략)
```

---

# Phase 6: GitHub Actions CI

## `.github/workflows/ci.yml`

| Job | 내용 | 트리거 |
|-----|------|--------|
| `php-tests` | PHP 8.0 + Composer + SQLite + PHPUnit | push/PR to main |
| `php-quality` | PHP-CS-Fixer (dry-run) + PHPStan | push/PR to main |
| `frontend-quality` | Node 18 + ESLint + Prettier + 빌드 | push/PR to main |

## 품질 게이트 흐름

```
커밋 → pre-commit (Lint) → 푸시 → pre-push (Test)
                                        ↓
                              GitHub Actions (Full CI)
                                        ↓
                              통과 시 Merge 가능
```

---

<!-- _class: lead -->

# 실행 결과 요약

---

# 실행 결과

| Phase | 상태 | 산출물 수 |
|-------|------|----------|
| Phase 1: 문서화 | **완료** | 14개 파일 |
| Phase 2: GitHub 이슈 | **건너뜀** | — (권한 부재) |
| Phase 3: Claude Code 스킬 | **완료** | 4개 스킬 |
| Phase 4: 테스트 인프라 | **완료** | 14개 테스트 파일 (71 케이스) |
| Phase 5: 보안 수정 | **완료** | Critical 3건 + High 2건 |
| Phase 6: 품질 자동화 | **완료** | CI + Git Hooks |
| README 업데이트 | **완료** | 한국어 전면 재작성 |

**Architect 검증:** 40개 산출물 모두 확인 완료

---

# 다음 단계

## 사용자 액션 필요

1. **PHP 설치** → `composer install && php artisan test`
   테스트 71개 케이스 런타임 검증

2. **npm install** → Husky 자동 초기화 확인

3. **GitHub Fork** → Phase 2 이슈 체계화 실행

4. **Laravel Dusk** → E2E 테스트 추가
   `composer require --dev laravel/dusk && php artisan dusk:install`

5. **Known Issues** → Mass Assignment, Tag 관계 오류 수정 검토

---

<!-- _class: lead -->

# 감사합니다

## 바이브 코딩으로 생산성을 높이세요
