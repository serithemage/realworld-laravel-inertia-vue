# 테스트 생성 스킬

ADR-007 테스트 전략에 따라 테스트를 생성합니다.

## 사용법

```
/gen-test <대상> [--type=unit|feature|browser]
```

## 입력 파라미터

- `$ARGUMENTS`: 테스트 대상 (모델명, 컨트롤러명, 또는 기능 설명)

## 테스트 유형별 가이드

### Unit 테스트 (--type=unit)

대상: Model, Policy
위치: `tests/Unit/`

**Model 테스트 패턴:**
```php
class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_belongs_to_user(): void
    {
        $article = Article::factory()->create();
        $this->assertInstanceOf(User::class, $article->user);
    }
}
```

**필수 검증 항목:**
- 모든 관계 메서드 (hasMany, belongsTo, belongsToMany)
- Accessor/Mutator
- Slug 생성 동작
- Factory 기본값

**Policy 테스트 패턴:**
```php
class ArticlePolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_update_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($user->can('update', $article));
    }

    public function test_non_owner_cannot_update_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $this->assertFalse($user->can('update', $article));
    }
}
```

### Feature 테스트 (--type=feature)

대상: Controller, HTTP 요청
위치: `tests/Feature/`

**Controller 테스트 패턴:**
```php
class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_article(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/articles', [
            'title' => 'Test Article',
            'content' => 'Test content',
            'excerpt' => 'Test excerpt',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('articles', ['title' => 'Test Article']);
    }
}
```

**필수 검증 항목:**
- 인증 필요 라우트의 비인증 접근 거부
- CRUD 각 액션의 정상 동작
- 유효성 검사 실패 시 에러 반환
- 인가 실패 시 403 반환
- Inertia 페이지 렌더링 확인

### Browser 테스트 (--type=browser)

대상: 사용자 시나리오
위치: `tests/Browser/`

**Dusk 테스트 패턴:**
```php
class ArticleFlowTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_create_and_view_article(): void
    {
        $user = User::factory()->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/articles/create')
                ->type('title', 'Test Article')
                ->type('content', 'Content here')
                ->press('Publish')
                ->assertSee('Test Article');
        });
    }
}
```

## 실행 절차

1. `$ARGUMENTS`에서 테스트 대상 파악
2. 대상 소스 코드 읽기 (모델, 컨트롤러, 정책)
3. 테스트 유형 결정 (명시되지 않으면 대상에 따라 자동)
4. 위 패턴에 따라 테스트 파일 생성
5. `php artisan test --filter=ClassName`으로 실행 확인

## 테스트 환경 설정

- DB: SQLite in-memory (`phpunit.xml` 설정)
- `RefreshDatabase` 트레이트 사용
- Factory로 테스트 데이터 생성
- 인증 테스트: `$this->actingAs($user)`

## 네이밍 규칙

- 파일: `{대상}Test.php` (예: `ArticleTest.php`)
- 메서드: `test_{행위}_description` (예: `test_user_can_create_article`)
- 디렉토리: 대상 유형별 (Models/, Policies/, Controllers/)
