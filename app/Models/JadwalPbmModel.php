<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalPbmModel extends Model
{
    protected $table            = 'jadwal_pbm';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['guru_id', 'kelas_id', 'mapel', 'hari', 'nama_hari', 'jam_ke', 'kode_kelas'];
    protected $useTimestamps    = true;

    /**
     * Pencarian Jadwal PBM dengan Join Tabel Guru, Kelas, dan Ref Jurusan
     */
    public function getJadwal(?string $namaGuru = null, ?string $namaHari = null, ?string $namaRombel = null)
    {
        $builder = $this->db->table($this->table)
            ->select('jadwal_pbm.*, guru.nama_guru, guru.kode_guru, kelas.nama_rombel, kelas.kode_kelas, jurusan.nama_jurusan')
            ->join('guru', 'guru.id = jadwal_pbm.guru_id')
            ->join('kelas', 'kelas.id = jadwal_pbm.kelas_id')
            ->join('jurusan', 'jurusan.kode_jurusan = kelas.kode_jurusan', 'left');

        if (!empty($namaGuru)) {
            $builder->like('guru.nama_guru', $namaGuru);
        }

        if (!empty($namaHari)) {
            $builder->where('jadwal_pbm.nama_hari', ucfirst(strtolower($namaHari)));
        }

        if (!empty($namaRombel)) {
            $builder->groupStart()
                    ->like('kelas.nama_rombel', $namaRombel)
                    ->orWhere('kelas.kode_kelas', $namaRombel)
                    ->groupEnd();
        }

        return $builder->orderBy('jadwal_pbm.hari', 'ASC')
                       ->orderBy('jadwal_pbm.jam_ke', 'ASC')
                       ->get()
                       ->getResultArray();
    }
}