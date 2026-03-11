# 코드 리뷰 스킬

프로젝트 코드 컨벤션 기반으로 코드를 리뷰합니다.

## 사용법

```
/code-review [파일경로 또는 범위]
```

## 입력 파라미터

- `$ARGUMENTS`: 리뷰 대상 (파일 경로, 디렉토리, 또는 "staged"/"diff")

## 리뷰 체크리스트

### 1. 보안 (Critical)

- [ ] SQL Injection: Raw 쿼리에 사용자 입력 직접 사용 여부
- [ ] XSS: 사용자 입력을 이스케이프 없이 출력 여부 (Blade `{!! !!}`, Vue `v-html`)
- [ ] CSRF: 폼 요청에 CSRF 토큰 포함 여부 (Inertia는 자동 처리)
- [ ] IDOR: 리소스 접근 시 소유자 검증 (Policy 사용 여부)
- [ ] Mass Assignment: `$fillable` 또는 `$guarded` 설정 확인
- [ ] 인증: `auth` 미들웨어 적용 확인
- [ ] 파일 업로드: 파일 타입/크기 검증

### 2. 코드 품질 (High)

- [ ] N+1 쿼리: `with()`, `withCount()` 사용으로 eager loading 확인
- [ ] 에러 핸들링: try-catch 또는 적절한 에러 응답
- [ ] 입력 검증: `request()->validate()` 또는 FormRequest 사용
- [ ] 중복 코드: DRY 원칙 준수
- [ ] 불필요한 쿼리: 사용하지 않는 데이터 조회 여부

### 3. 컨벤션 준수 (Medium)

- [ ] PHP: PSR-12 준수 (`composer format`으로 확인)
- [ ] 네이밍: 컨벤션 문서(`docs/code-conventions.md`) 준수
- [ ] 컨트롤러: 리소스 또는 단일 액션 패턴
- [ ] 모델: 관계 메서드 네이밍, Factory 존재
- [ ] Vue: SFC 구조, Composition API 권장
- [ ] 커밋: Conventional Commits 형식

### 4. 테스트 (Medium)

- [ ] 새 기능에 대한 테스트 존재 여부
- [ ] 기존 테스트 통과 여부
- [ ] 테스트 커버리지 적정 수준

### 5. 성능 (Low)

- [ ] 페이지네이션 사용 (대량 데이터 조회 시)
- [ ] 인덱스 활용 (쿼리 조건 컬럼)
- [ ] 캐시 활용 가능 여부

## 출력 형식

```markdown
## 코드 리뷰 결과

### Critical
- [파일:라인] 설명

### High
- [파일:라인] 설명

### Medium
- [파일:라인] 설명

### Low
- [파일:라인] 설명

### 요약
- Critical: N건
- High: N건
- Medium: N건
- Low: N건
```

## 실행 절차

1. `$ARGUMENTS`로 리뷰 대상 파악
   - 파일 경로: 해당 파일 리뷰
   - 디렉토리: 디렉토리 내 모든 파일 리뷰
   - "staged": `git diff --cached` 결과 리뷰
   - "diff": `git diff` 결과 리뷰
   - 미지정: 전체 `app/` 디렉토리 리뷰
2. 대상 파일 읽기
3. 체크리스트 기반 검사
4. 결과를 위 형식으로 출력
5. Critical 이슈 발견 시 수정 제안 포함
