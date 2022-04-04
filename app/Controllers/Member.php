<?php
namespace App\Controllers;
use CodeIgniter\I18n\Time;
use CodeIgniter\Model;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\M_member;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\Services;

class Member extends ResourceController{
    use ResponseTrait;
    public function __construct() {
        $this->m_member = new M_member();
    }

    /* 토큰값으로 회원 확인 - get api */
    public function index(){
        $key = Services::getSecretKey();
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        if(!$header) return $this->failUnauthorized('Token Required');
        $token = explode(' ', $header)[1];

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $data = $this->m_member->where('user_id', $decoded->user_id)->first();
            if($data){
                $result = [
                    'status'   => '0000',
                    'error'    => null,
                    'messages' => [
                        'success' => '가입된 회원입니다. ',
                        'data' => $data
                    ]
                ];
            }else{
                $result = [
                    'status'   => '1002',
                    'error'    => null,
                    'messages' => [
                        'fail' => '회원이 존재하지 않습니다. '
                    ]
                ];
            }
            return $this->respond($result);
        } catch (\Throwable $th) {
            return $this->fail('Invalid Token');
        }
    }

    /* 회원 id 존재 여부 확인 - get api */
    public function getMember(){
        $user_id = $this->request->getVar('user_id');
        if(is_null($user_id)){
            return $this->respond("아이디를 올바르게 입력해주세요.");
        }

        $is_exist_user = $this->m_member->where('user_id', $user_id)->first();

        if(!$is_exist_user){
            $result = [
                'status'   => '0000',
                'error'    => null,
                'messages' => [
                    'success' => '사용 가능 합니다.'
                ]
            ];
        }else{
            $result = [
                'status'   => '1002',
                'error'    => null,
                'messages' => [
                    'data' => $is_exist_user,
                    'fail' => '중복되었습니다. 다른 정보를 입력해주세요.'
                ]
            ];
        }
        return $this->respond($result);
    }

    /* 회원 가입 - post api */
    public function join() {
        $user_id = $this->request->getVar('user_id');
        $user_pw = $this->request->getVar('user_pw');
        $name = $this->request->getVar('name');

        if(is_null($user_id) || is_null($user_pw) || is_null($name)){
            return $this->respond("가입 정보를 올바르게 입력해주세요.");
        }
        // 가입된 아이디인지 확인
        $chk_user_id = $this->m_member->where('user_id', $user_id)->first();
        if($chk_user_id){
            return $this->respond("이미 가입된 아이디입니다. 다른 아이디로 가입해주세요. ");
        }
        $user_pw = password_hash($user_pw, PASSWORD_BCRYPT);

        $join_data = [
            'user_id' => $user_id,
            'user_pw'  => $user_pw,
            'name'  => $name,
            'status' => 'O',
            'created_at' => Time::now(),
        ];
        $join_result = $this->m_member->insert($join_data);

        if($join_result){
            $result = [
                'status'   => '0000',
                'error'    => null,
                'messages' => [
                    'success' => '회원가입에 성공하였습니다.'
                ]
            ];
        }else{
            $result = [
                'status' 	=> '1002',
                'error'    => null,
                'messages' => [
                    'success' => '회원가입에 실패했습니다. 정보를 확인해주세요.'
                ]
            ];
        }
        return $this->respondCreated($result);
    }

    /* 로그인 api */
    public function login(){
        $user_id = $this->request->getVar('user_id');
        $user_pw = $this->request->getVar('user_pw');
        if(is_null($user_id) || is_null($user_pw)){
            return $this->respond("로그인 정보를 올바르게 입력해주세요.");
        }

        $chk_user = $this->m_member->where('user_id', $user_id)->first();
        $pw_verify = password_verify($user_pw, $chk_user["user_pw"]);
        if(is_null($chk_user) || !$pw_verify){
            return $this->respond("로그인에 실패하였습니다.");
        }
        if($chk_user['status'] == 'X'){
            return $this->respond("탈퇴한 회원이므로 로그인 할 수 없습니다. ");
        }

        $key = Services::getSecretKey();
        $iat = time();
        $exp = $iat + 3600;

        $payload = array(
            "iat" => $iat,
            "exp" => $exp,
            'user_id' => $chk_user['user_id'],
        );

        $token = JWT::encode($payload, $key, 'HS256');

        $result = [
            'status'   => '0000',
            'error'    => null,
            'messages' => [
                'success' => '로그인에 성공하였습니다.'
            ],
            'access_token' => $token
        ];
        return $this->respondCreated($result);

    }

    /* 회원 정보 수정 - put api */
    /* 이 테이블 구조에서는 이름만 수정 가능한 값 */
    public function updateInfo($index){
        if(is_null($index)){
            return $this->respond("회원을 지정해주세요. ");
        }
        $name = $this->request->getVar('name');
        if(is_null($name)){
            return $this->respond("수정할 이름을 입력해주세요.");
        }
        $data = [
            'name'  => $name,
            'updated_at' => Time::now(),
        ];
        $this->m_member->updateInfo($index, $data);
        $result = [
            'status' 	=> '0000',
            'error'    => null,
            'messages' => [
                'success' => '회원 정보 수정 완료되었습니다. '
            ]
        ];

        return $this->respond($result);
    }

    /* 회원 탈퇴 - put api */
    public function updateStatus(){
        $user_id = $this->request->getVar('user_id');
        if(is_null($user_id)){
            return $this->respond("탈퇴할 회원을 올바르게 지정해주세요. ");
        }
        $data = [
            'status'  => 'X'
        ];

        // 가입된 아이디인지 확인
        $chk_user_id = $this->m_member->where('user_id', $user_id)->first();
        if(is_null($chk_user_id)){
            return $this->respond("존재하는 회원이 아닙니다. ");
        }

        // 탈퇴 여부 확인
        $chk_status = $this->m_member->where('user_id', $user_id)
            ->where('status', 'X')->findAll();
        if($chk_status){
            return $this->respond("이미 탈퇴된 회원입니다. ");
        }

        $this->m_member->updateStatus($user_id, $data);

        $result = [
            'status' 	=> '0000',
            'error'    => null,
            'messages' => [
                'success' => '회원 탈퇴 완료되었습니다. '
            ]
        ];

        return $this->respond($result);
    }
}
