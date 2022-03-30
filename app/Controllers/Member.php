<?php
namespace App\Controllers;
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
    public function getMember($user_id){
        $is_exist_user = $this->m_member->where('user_id', $user_id)->first();

        if($is_exist_user){
            $result = [
                'status'   => '0000',
                'error'    => null,
                'messages' => [
                    'data' => $is_exist_user,
                    'success' => '사용가능합니다.'
                ]
            ];

        }else{
            $result = [
                'status'   => '1002',
                'error'    => null,
                'messages' => [
                    'success' => '중복되었습니다. 다른 정보를 입력해주세요.'
                ]
            ];
        }
        return $this->respond($result);
    }

    /* 회원 가입 - post api */
    public function create() {
        $join_data = [
            'user_id' => $this->request->getVar('user_id'),
            'user_pw'  => $this->request->getVar('user_pw'),
            'name'  => $this->request->getVar('name'),
        ];
        $ret = $this->m_member->insert($join_data);

        if($ret){
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
    public function update($index = null){
        $index = $this->request->getVar('index');
        $data = [
            'name'  => $this->request->getVar('name'),
        ];
        $this->m_member->update($index, $data);
        $result = [
            'status' 	=> '0000',
            'error'    => null,
            'messages' => [
                'success' => '회원 정보 수정 완료되었습니다. '
            ]
        ];

        return $this->respond($result);
    }





}
