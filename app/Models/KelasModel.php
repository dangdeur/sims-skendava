<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table            = 'kelas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['kode_kelas', 'jenjang', 'kode_jurusan', 'nomor_kelas', 'nama_rombel'];
    protected $useTimestamps    = true;
}