# GitHub 이슈 관리 스킬

GitHub 이슈를 생성, 업데이트, 닫는 작업을 수행합니다. 인수조건 검증을 포함합니다.

## 사용법

```
/manage-issues <action> [options]
```

## 입력 파라미터

- `$ARGUMENTS`: 액션과 상세 정보

## 액션

### 이슈 생성 (create)

```
/manage-issues create <제목> --label=<라벨> --body=<본문>
```

**이슈 템플릿:**
```markdown
## 배경
[이 작업이 필요한 이유]

## 작업 개요
[수행할 작업 설명]

## 인수조건
- [ ] 조건 1
- [ ] 조건 2
- [ ] 조건 3

## 의존 관계
- Blocked by: #이슈번호 (있는 경우)
- Blocks: #이슈번호 (있는 경우)
```

**라벨 체계:**
- `epic`: 대규모 작업 묶음
- `documentation`: 문서 관련
- `testing`: 테스트 관련
- `infra`: 인프라/CI/CD
- `security`: 보안 관련
- `enhancement`: 기능 개선
- `bug`: 버그 수정

### 이슈 업데이트 (update)

```
/manage-issues update #번호 --comment=<코멘트>
```

진행 상황이나 변경 사항을 코멘트로 기록합니다.

### 이슈 닫기 (close)

```
/manage-issues close #번호
```

**닫기 전 검증:**
1. 이슈의 인수조건 목록 확인
2. 각 인수조건 충족 여부 검증
3. 미충족 항목이 있으면 코멘트로 알림 후 닫지 않음
4. 모든 인수조건 충족 시 닫기 실행

### 이슈 목록 (list)

```
/manage-issues list [--label=<라벨>] [--state=open|closed]
```

## 실행 절차

1. `$ARGUMENTS`에서 액션 파악
2. GitHub CLI (`gh`) 명령어로 해당 액션 실행
3. 이슈 닫기 시 인수조건 검증 수행
4. 결과 보고

## 사용하는 CLI 명령어

```bash
# 이슈 생성
gh issue create --title "제목" --body "본문" --label "라벨"

# 이슈 목록
gh issue list --label "라벨" --state open

# 이슈 조회
gh issue view 번호

# 이슈 코멘트
gh issue comment 번호 --body "코멘트"

# 이슈 닫기
gh issue close 번호 --comment "닫기 사유"
```
