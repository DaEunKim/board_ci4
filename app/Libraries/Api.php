<?php
namespace App\Libraries;
use CodeIgniter\Libraries;
use \Firebase\JWT\JWT;

class Api extends Libraries{
    // api key
    private $api_key = array("abcdefghij");

    public function check_api($data){
        $oauth_result = $this->check_oauth_token();

        if($oauth_result !== true){
            if(count($data["required"]) == 0 && count($data["selection"]) == 0){
                $this->respond("403","ERROR.COMMON.1002","필수 파라미터 부족");
            }

            if(count($data["required"]) > 0){
                foreach($data["required"] as $row){
                    if(!$row && $row !== "0"){
                        $this->respond("403","ERROR.COMMON.1003","필수 파라미터 부족");
                    }
                }
            }

            $seed = $data["finger"]['seed'];
            $timestamp = $data["finger"]['timestamp'];
            $data_fingerprint = $data["finger"]['fingerprint'];

            //check fingerprint params
            if(is_null($seed) || !$timestamp || !$data_fingerprint) {
                $this->respond("403", "ERROR.AUTH.1001", "API 암호화 인증 실패");
            }

            //array merge
            $data = array_merge($data["required"], $data["selection"], array("timestamp" => $timestamp));

            //check fingerprint
            $data_fingerprint = str_replace(' ', '+', $data_fingerprint);
            $fingerprint = "".base64_encode(hash_hmac('sha256', $this->stringsum_recursive($data, ""), $this->api_key[$seed], true));

            if($fingerprint != $data_fingerprint){
                $this->respond("403","ERROR.AUTH.1002","API 암호화 인증 실패");
            }
        }

    }

    private function check_oauth_token(){
        // header 에서 oauth token 값을 구한다.
        $authHeader = $this->request->getHeader("Authorization");
        $jwt = $authHeader->getValue();
        $api_key = $this->api_key;

        $decoded = JWT::decode($jwt, $api_key, array('HS256'));

        if($decoded['result'] !== true) {
            return $this->respond("403","ERROR.OAUTH.".$decoded['result_code'],$decoded['message']);
        }
        else {
            return true;
        }

        return false;
    }

    //sort array by key
    private function stringsum_recursive($params, $str="")	{
        if(is_array($params)) {
            ksort($params);
            foreach ($params as $val) {
                if(is_string($val) || is_numeric($val))
                    $str .= $val;
            }
        }
        return $str;
    }
}
