<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;

class Pengaturan extends BaseConfig
{
    public $sekolah = [
        'nama' => 'SMKN 2 Pandeglang',
        'alamat' => 'Jl. Raya Lintas Timur KM. 3 Pandeglang',
        'email' => 'info@smkn2pandeglang.sch.id',
    ];
    public $web = [
        'nama' => 'CI4 Starter',
        'deskripsi' => 'CodeIgniter 4 Starter Template',
        'template' => 'newage',
        'template_auth' => 'sbadmin',
        'footer' => '@Endang Suhendar 2026',
    ];

    public $user = [
        'template' => 'sbadmin',

    ];

    public int $itemsPerPage = 20;
}
