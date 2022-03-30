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

}
