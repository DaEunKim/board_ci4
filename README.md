프로젝트 실행 명령어 &nbsp;
php spark serve &nbsp;
&nbsp;
<주요 기능>&nbsp;
1. 회원가입, 로그인, 회원 조회, 회원 정보 수정, 회원 존재 여부 확인, 회원 탈퇴 기능 개발&nbsp;
2. jwt 토큰 발급을 통한 oauth 검증&nbsp;
3. 비밀번호 암호화&nbsp;

DB명 : member / Table명 : e_member&nbsp;

table 구조: 
index, user_id, user_pw, name, status, created_at, updated_at&nbsp;
&nbsp;

CREATE TABLE `e_member` (
  `index` int(11) NOT NULL COMMENT 'Primary Key',
  `user_id` varchar(100) NOT NULL COMMENT 'user_id',
`user_pw` varchar(255) NOT NULL COMMENT 'user_pw’
`name` varchar(100) NOT NULL COMMENT 'name',
`status` varchar(100) NOT NULL COMMENT 'status',
`created_at` datetime NOT NULL COMMENT 'created_at',
`updated_at` datetime NOT NULL COMMENT 'updated_at',  
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Customers collection';
