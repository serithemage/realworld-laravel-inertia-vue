# DB 마이그레이션 스킬

데이터베이스 마이그레이션을 생성하고, 관련 모델과 팩토리를 함께 업데이트합니다.

## 사용법

```
/db-migrate <설명>
```

## 입력 파라미터

- `$ARGUMENTS`: 마이그레이션 설명 (예: "add published_at column to articles table")

## 실행 절차

### 1. 마이그레이션 파일 생성

```bash
php artisan make:migration <snake_case_설명>
```

**네이밍 규칙:**
- 테이블 생성: `create_<테이블명>_table`
- 컬럼 추가: `add_<컬럼명>_to_<테이블명>_table`
- 컬럼 삭제: `remove_<컬럼명>_from_<테이블명>_table`
- 컬럼 변경: `change_<컬럼명>_in_<테이블명>_table`

### 2. 마이그레이션 코드 작성

**기존 스키마 참고 (ADR-005):**
- 외래 키: `nullOnDelete()` 사용 (프로젝트 기존 패턴)
- 인덱스: slug 등 조회 빈도 높은 컬럼에 추가
- 타임스탬프: 일반 테이블은 `timestamps()` 포함

```php
public function up(): void
{
    Schema::table('articles', function (Blueprint $table) {
        $table->timestamp('published_at')->nullable()->after('state');
    });
}

public function down(): void
{
    Schema::table('articles', function (Blueprint $table) {
        $table->dropColumn('published_at');
    });
}
```

### 3. 모델 업데이트

변경된 스키마에 맞게 모델 수정:
- 새 컬럼의 `$casts` 추가 (날짜, enum 등)
- 새 관계 메서드 추가
- Accessor/Mutator 필요 시 추가

### 4. 팩토리 업데이트

해당 모델의 Factory에 새 컬럼 기본값 추가:

```php
public function definition(): array
{
    return [
        // 기존 필드...
        'published_at' => $this->faker->optional()->dateTime,
    ];
}
```

### 5. 테스트 확인

```bash
# 마이그레이션 실행
php artisan migrate

# 롤백 테스트
php artisan migrate:rollback --step=1

# 다시 실행
php artisan migrate

# 기존 테스트 통과 확인
php artisan test
```

### 6. 결과 보고

변경된 파일 목록:
- `database/migrations/YYYY_MM_DD_HHMMSS_<이름>.php` (신규)
- `app/Models/<모델>.php` (수정)
- `database/factories/<모델>Factory.php` (수정)

## 주의사항

- 기존 데이터가 있는 프로덕션 DB에 적용할 경우 `nullable()` 또는 `default()` 사용
- `down()` 메서드에 롤백 로직 반드시 포함
- 테이블 삭제 시 외래 키 의존성 확인
- 인덱스 변경 시 대규모 테이블에서의 잠금(Lock) 시간 고려
