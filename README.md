<프로젝트 실행 명령어>  
php spark serve  


<주요 기능>  
1. 회원가입, 로그인, 회원 조회, 회원 정보 수정, 회원 존재 여부 확인, 회원 탈퇴 기능 개발  
2. jwt 토큰 발급을 통한 oauth 검증  
3. 비밀번호 암호화  



< 데이터베이스 >  
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


로그인 (app/Controllers/Member/login)  
1. user_id, user_pw 정보를 전달받음.  
2. 필요한 정보 모두 전달 받았는지 확인  
3. 아이디와 패스워드 일치하는지 확인하는 모델 호출  
4. jwt 발급 (access token 발급)  
5. 회원 조회 및 jwt 발급 성공 여부에 따라 response 리턴  


토큰으로 회원 확인  (app/Controllers/Member/index)  
1. 로그인에서 발급한 jwt 값 정보를 전달받음.   
2. jwt decode 를 통해 키 복호화  
3. 키 복호화로 얻은 회원 id를 조회하는 모델 호출  
4. 가입 여부 확인 후 response 리턴  
