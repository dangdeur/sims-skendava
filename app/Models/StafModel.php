<?php

namespace App\Models;

use CodeIgniter\Model;

class StafModel extends Model
{
    protected $table            = 'staf';
    protected $primaryKey       = 'id_staf';
    protected $useAutoIncrement = true;
    //protected $returnType       = 'array';
     protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'nama', 'nuptk', 'nip', 'email', 'hp','fcm_token'];

    
    public function getAllTokens()
    {
        return $this->select('fcm_token')
                    ->where('fcm_token IS NOT NULL')
                    ->findAll();
    }

    public function getFcmToken($user_id)
    {
        $user = $this->select('fcm_token')->find($user_id);
        return $user ? $user['fcm_token'] : null;
    }

    public function updateFcmToken($userId, $token)
    {
        return $this->where('user_id', $userId)
                    ->set(['fcm_token' => $token])
                    ->update();
    }
    // Mengambil daftar siswa atau guru untuk keperluan multi-user chat
    public function getUsersByRole($role)
    {
        return $this->where('role', $role)->where('active', 1)->findAll();
    }

   
}
