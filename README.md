프로젝트 실행 명령어 
php spark serve


DB명 : member / Table명 : e_member

table 구조: 
index, user_id, user_pw, name, status, created_at, updated_at

CREATE TABLE `e_member` (
  `index` int(11) NOT NULL COMMENT 'Primary Key',
  `user_id` varchar(100) NOT NULL COMMENT 'user_id',
`user_pw` varchar(255) NOT NULL COMMENT 'user_pw’
`name` varchar(100) NOT NULL COMMENT 'name',
`status` varchar(100) NOT NULL COMMENT 'status',
`created_at` datetime NOT NULL COMMENT 'created_at',
`updated_at` datetime NOT NULL COMMENT 'updated_at',  
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Customers collection';
