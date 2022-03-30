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
        'created_at'
    ];

}
