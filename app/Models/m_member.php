<?php
namespace App\Models;
use CodeIgniter\Model;

class M_member extends Model {
    protected $table = 'e_member';
    protected $primaryKey = 'index';
    protected $allowedFields = [
        'user_id',
        'user_pw',
        'name',
        'status',
        'created_at',
        'updated_at'
    ];


    function updateInfo($index, $data){
        $builder = $this->builder($this->table);
        $builder->set($data);
        $builder->where('index', $index);
        $builder->update();
    }

    function updateStatus($user_id, $status){
        $builder = $this->builder($this->table);
        $builder->set($status);
        $builder->where('user_id', $user_id);
        $builder->update();
    }

    function findUserByUserId($user_id){
        $builder = $this->builder($this->table);
        $user = $builder->where(['user_id' => $user_id])->first();

        if (!$user)
            throw new Exception('User does not exist for specified email address');

        return $user;
    }

}
