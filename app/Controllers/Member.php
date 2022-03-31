<?php
namespace App\Controllers;
use CodeIgniter\I18n\Time;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ApiModel;

class Member extends ResourceController{
    use ResponseTrait;
    public function __construct() {
        $this->m_member = new \App\Models\M_member();
    }

    /* 모든 회원 조회 - get api */
    public function index(){
        $data = $this->m_member->orderBy('index', 'DESC')->findAll();
        return $this->respond($data);
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
                    'success' => '사용가능합니다.'
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
    public function create() {
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
