<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Jurusan extends Seeder
{
    public function run()
    {
        // Sesuaikan digit_ke_2 dengan kode jurusan di sekolah Anda
        $data = [
            ['kode_jurusan' => 1, 'nama_jurusan' => 'Agribisnis Tanaman Pangan Dan Hortikultura', 'singkatan_jurusan' => 'ATPH'],
            ['kode_jurusan' => 2, 'nama_jurusan' => 'Agribisnis Pengolahan Hasil Pertanian', 'singkatan_jurusan' => 'APHP'],
            ['kode_jurusan' => 3, 'nama_jurusan' => 'Teknik Kendaraan Ringan','singkatan_jurusan' => 'TKR'],
            ['kode_jurusan' => 4, 'nama_jurusan' => 'Teknik Instalasi Tenaga Listrik','singkatan_jurusan' => 'TKL'],
            ['kode_jurusan' => 5, 'nama_jurusan' => 'Desain Komunikasi Visual','singkatan_jurusan' => 'DKV'],
            ['kode_jurusan' => 6, 'nama_jurusan' => 'Teknik Komputer dan Jaringan','singkatan_jurusan' => 'TKJ'],
            ['kode_jurusan' => 7, 'nama_jurusan' => 'Analisis Pengujian Laboratorium','singkatan_jurusan' => 'APL'],
            ['kode_jurusan' => 8, 'nama_jurusan' => 'Teknik Sepeda Motor','singkatan_jurusan' => 'TSM'],
        ];

        $this->db->table('jurusan')->insertBatch($data);
    }
}