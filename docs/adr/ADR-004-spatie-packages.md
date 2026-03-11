# ADR-004: Spatie 패키지 활용 전략

## 상태
승인

## 배경
반복적인 기능(slug 생성, 데이터 객체 패턴)을 직접 구현할지 검증된 패키지를 활용할지 결정해야 한다.

## 결정
Spatie의 Laravel 패키지를 활용한다:
- `spatie/laravel-sluggable` (^3.4): 모델 slug 자동 생성
- `spatie/laravel-data` (^1.4): 데이터 객체 패턴

## 근거

### laravel-sluggable
- Article, Tag 모델에서 slug 자동 생성 필요
- `HasSlug` 트레이트와 `getSlugOptions()` 메서드로 간단히 구현
- 중복 slug 자동 처리 (suffix 추가)
- SEO 친화적 URL 생성

### laravel-data
- 컨트롤러 ↔ 뷰 간 데이터 전달 시 구조화된 객체 사용 가능
- 타입 안정성 향상
- 유효성 검사 통합 가능

## 사용 현황

### Article 모델
```php
use HasSlug;

public function getSlugOptions(): SlugOptions
{
    return SlugOptions::create()
        ->generateSlugsFrom('title')
        ->saveSlugsTo('slug');
}
```

### Tag 모델
```php
use HasSlug;

public function getSlugOptions(): SlugOptions
{
    return SlugOptions::create()
        ->generateSlugsFrom('name')
        ->saveSlugsTo('slug');
}
```

## 영향
- Spatie 패키지 버전 호환성 관리 필요
- Laravel 메이저 버전 업그레이드 시 패키지 호환성 확인 필수
- 테스트 시 slug 생성 동작 고려 필요

## 대안
- 직접 구현: Eloquent `creating` 이벤트에서 `Str::slug()` 사용. 간단하나 중복 처리 직접 구현 필요
- cviebrock/eloquent-sluggable: 유사 기능이나 Spatie 대비 생태계 작음
