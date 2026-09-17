<?php

namespace App\Models;

use CodeIgniter\Model;

class UserDetailModel extends Model
{
    protected $table = 'staf';
    protected $primaryKey = 'id_staf';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_user',
        'kode_staf',
        'nama',
        'nama_gelar',
        'nuptk',
        'jk',
        'tempat_lahir',
        'tanggal_lahir',
        'nik',
        'nip',
        'status_kepegawaian',
        'hp',
        'email',
        'tugas_tambahan',
        'fcm_token'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

     public function getFcmToken($user_id)
    {
        $user = $this->select('fcm_token')->find($user_id);
        return $user ? $user['fcm_token'] : null;
    }

    // Mengambil daftar siswa atau guru untuk keperluan multi-user chat
    public function getUsersByRole($role)
    {
        return $this->where('role', $role)->where('active', 1)->findAll();
    }

}
