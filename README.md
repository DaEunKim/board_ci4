프로젝트 실행 명령어 &nbsp;
php spark serve 

&nbsp;
<주요 기능>&nbsp;
1. 회원가입, 로그인, 회원 조회, 회원 정보 수정, 회원 존재 여부 확인, 회원 탈퇴 기능 개발&nbsp;
2. jwt 토큰 발급을 통한 oauth 검증&nbsp;
3. 비밀번호 암호화&nbsp;

<DB 구조> &nbsp;
DB명 : member &nbsp;
Table명 : e_member &nbsp;

table 구조 &nbsp;
index, user_id, user_pw, name, status, created_at, updated_at &nbsp;
&nbsp;

CREATE TABLE `e_member` ( &nbsp;
  `index` int(11) NOT NULL COMMENT 'Primary Key', &nbsp;
  `user_id` varchar(100) NOT NULL COMMENT 'user_id', &nbsp;
  `user_pw` varchar(255) NOT NULL COMMENT 'user_pw’, &nbsp;
  `name` varchar(100) NOT NULL COMMENT 'name', &nbsp;
  `status` varchar(100) NOT NULL COMMENT 'status', &nbsp;
  `created_at` datetime NOT NULL COMMENT 'created_at', &nbsp;
  `updated_at` datetime NOT NULL COMMENT 'updated_at',  &nbsp;
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Customers collection';&nbsp;

<각 로직의 부연설명>
&nbsp;
회원가입 (app/Controllers/Member/join) &nbsp;
1. user_id, user_pw, name 정보를 전달받음 &nbsp;
2. 필요한 정보 전달 받았는지 확인 &nbsp;
3. 가입된 아이디인지 확인하는 모델 호출 &nbsp;
4. password 암호화 &nbsp;
5. 회원 테이블에 insert 하는 모델 호출 &nbsp;
5. insert 성공 여부에 따라 response 리턴 &nbsp;
