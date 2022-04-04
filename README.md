<프로젝트 실행 명령어>  
php spark serve  


<주요 기능>  
1. 회원가입, 로그인, 회원 조회, 회원 정보 수정, 회원 존재 여부 확인, 회원 탈퇴 기능 개발  
2. jwt 토큰 발급을 통한 oauth 검증  
3. 비밀번호 암호화  



<DB>  
 1. DB명 : member  &nbsp; 
 2. Table명 : e_member  
 index, user_id, user_pw, name, status, created_at, updated_at  
  
  
CREATE TABLE `e_member` (  
  `index` int(11) NOT NULL COMMENT 'Primary Key',  
  `user_id` varchar(100) NOT NULL COMMENT 'user_id',  
  `user_pw` varchar(255) NOT NULL COMMENT 'user_pw’,  
  `name` varchar(100) NOT NULL COMMENT 'name',  
  `status` varchar(100) NOT NULL COMMENT 'status',  
  `created_at` datetime NOT NULL COMMENT 'created_at',  
  `updated_at` datetime NOT NULL COMMENT 'updated_at',  
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Customers collection';  
  
  
  <각 로직의 부연설명>  
회원가입 (app/Controllers/Member/join)  
1. user_id, user_pw, name 정보를 전달받음  
2. 필요한 정보 전달 받았는지 확인  
3. 가입된 아이디인지 확인하는 모델 호출  
4. password 암호화  
5. 회원 테이블에 insert 하는 모델 호출  
5. insert 성공 여부에 따라 response 리턴  
