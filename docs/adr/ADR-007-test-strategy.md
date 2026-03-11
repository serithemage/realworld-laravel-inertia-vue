# ADR-007: 테스트 전략 (PHPUnit + Laravel Dusk)

## 상태
승인

## 배경
프로덕션 서비스로 운영하기 위해 체계적인 테스트 전략이 필요하다. 현재 예제 테스트만 존재하며 실질적 커버리지가 0%이다.

## 결정
3계층 테스트 전략을 적용한다:
1. **Unit 테스트**: PHPUnit (모델, 정책)
2. **Feature 테스트**: PHPUnit (HTTP 요청, 컨트롤러)
3. **E2E 테스트**: Laravel Dusk (브라우저 기반 시나리오)

## 테스트 계층

### Unit 테스트 (`tests/Unit/`)
- 모델 관계 검증 (hasMany, belongsTo, belongsToMany)
- Policy 검증 (소유자 인가 로직)
- Accessor/Mutator 검증
- 외부 의존성 없이 독립 실행

### Feature 테스트 (`tests/Feature/`)
- HTTP 요청/응답 검증
- 인증/인가 플로우
- Article CRUD 전체 흐름
- Comment 생성/삭제
- 즐겨찾기/팔로우
- 입력 유효성 검사
- Inertia 페이지 렌더링 확인

### E2E 테스트 (`tests/Browser/`)
- Laravel Dusk 활용 (ChromeDriver)
- 사용자 시나리오 기반
- 주요 플로우: 회원가입 → 로그인 → 글 작성 → 댓글 → 즐겨찾기

## 테스트 환경
```xml
<!-- phpunit.xml -->
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```
- SQLite in-memory DB로 빠른 실행
- `RefreshDatabase` 트레이트로 테스트 간 격리
- Bcrypt 라운드 4로 속도 최적화

## Factory 활용
- 모든 테스트 데이터는 Factory로 생성
- 기존 Factory 활용: `UserFactory`, `ArticleFactory`, `CommentFactory`, `TagFactory`
- 관계 데이터는 Factory 체이닝으로 구성

## 실행 명령어
```bash
php artisan test                      # 전체
php artisan test --testsuite=Unit     # Unit만
php artisan test --testsuite=Feature  # Feature만
php artisan dusk                      # E2E (Dusk)
```

## 목표 커버리지
- 모델: 100% (관계, 접근자)
- Policy: 100% (모든 인가 메서드)
- Controller: 주요 CRUD 플로우 90%+
- E2E: 핵심 사용자 시나리오 커버

## 영향
- Laravel Dusk 추가 의존성 (`composer require --dev laravel/dusk`)
- E2E 테스트 실행 시 ChromeDriver 필요
- CI에서 E2E 실행 시 headless Chrome 설정 필요

## 대안
- Pest PHP: 더 간결한 문법이나 PHPUnit과 호환, 추후 전환 가능
- Cypress (E2E): JavaScript 기반 E2E이나 Laravel 통합이 Dusk 대비 약함
- Playwright: 최신 E2E 도구이나 Laravel 전용 기능 부재
