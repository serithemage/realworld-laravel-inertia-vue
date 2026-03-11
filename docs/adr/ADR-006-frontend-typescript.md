# ADR-006: 프론트엔드 TypeScript 도입

## 상태
승인

## 배경
프론트엔드 코드의 타입 안정성을 향상시키고, IDE 자동완성과 리팩토링 지원을 강화하기 위해 TypeScript 도입을 검토한다.

## 결정
프론트엔드에 TypeScript를 점진적으로 도입한다.
- `based/laravel-typescript` 패키지로 Laravel 모델에서 TypeScript 타입 자동 생성
- `tsconfig.json` 설정으로 타입 검사 활성화
- 기존 `.js` 파일은 점진적으로 `.ts`/`.vue` (script lang="ts")로 전환

## 근거
- `based/laravel-typescript` 패키지가 이미 설치되어 있음
- Inertia.js 페이지 Props의 타입 안정성 확보
- 백엔드 모델 변경 시 프론트엔드 타입 자동 동기화 가능
- 런타임 에러를 컴파일 타임에 사전 방지

## 설정

### tsconfig.json
```json
{
  "compilerOptions": {
    "target": "ESNext",
    "module": "ESNext",
    "strict": true,
    "noEmit": true,
    "paths": { "@/*": ["resources/js/*"] }
  },
  "include": ["resources/js/**/*.ts", "resources/js/**/*.vue"]
}
```

### 타입 생성
```bash
php artisan typescript:transform
```

## 전환 전략
1. `tsconfig.json` 설정 (현재 단계)
2. 공통 타입 정의 파일 생성 (`resources/js/types/`)
3. 새로 작성하는 파일은 TypeScript 사용
4. 기존 파일은 수정 시 점진적 전환

## 영향
- 빌드 시간 소폭 증가 (타입 검사)
- 기존 `.js` 파일과 `.ts` 파일 혼용 기간 존재
- 팀원의 TypeScript 기본 지식 필요

## 대안
- JSDoc 타입 주석: TypeScript 없이 타입 힌트 가능하나 강제성 부족
- JavaScript 유지: 타입 안정성 포기, 런타임 에러 위험
