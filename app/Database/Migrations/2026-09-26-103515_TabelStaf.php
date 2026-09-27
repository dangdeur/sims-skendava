<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TabelStaf extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'id_staf' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'nuptk' => [
                'type'       => 'VARCHAR',
                'constraint' => '16',
                'null'       => true,
            ],
            'jk' => [
                'type'       => 'VARCHAR',
                'constraint' => '1',
                'null'       => true,
            ],
            'tempat_lahir' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'tanggal_lahir' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => true,
            ],
            'nip' => [
                'type'       => 'VARCHAR',
                'constraint' => '18',
                'null'       => true,
            ],
            'status_kepegawaian' => [
                'type'       => 'VARCHAR',
                'constraint' => '26',
                'null'       => true,
            ],
            'jenis_ptk' => [
                'type'       => 'VARCHAR',
                'constraint' => '4',
                'null'       => true,
            ],
            'agama' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'null'       => true,
            ],
            'alamat_jalan' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
                'null'       => true,
            ],
            'rt' => [
                'type'       => 'VARCHAR',
                'constraint' => '2',
                'null'       => true,
            ],
            'rw' => [
                'type'       => 'VARCHAR',
                'constraint' => '2',
                'null'       => true,
            ],
            'nama_dusun' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'desa_kelurahan' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'kecamatan' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'kode_pos' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'null'       => true,
            ],
            'telepon' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
                'null'       => true,
            ],
            'hp' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'tugas_tambahan' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'sk_cpns' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => true,
            ],
            'tanggal_cpns' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => true,
            ],
            'sk_pengangkatan' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => true,
            ],
            'tmt_pengangkatan' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => true,
            ],
            'lembaga_pengangkatan' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'pangkat_golongan' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'null'       => true,
            ],
            'sumber_gaji' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
                'null'       => true,
            ],
            'nama_ibu_kandung' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => true,
            ],
            'status_perkawinan' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
                'null'       => true,
            ],
            'nama_suami_istri' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => true,
            ],
            'nip_suami_istri' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'pekerjaan_suami_istri' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => true,
            ],
            'tmt_pns' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => true,
            ],
            'sudah_lisensi_kepala_sekolah' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'null'       => true,
            ],
            'pernah_diklat_kepengawasan' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'null'       => true,
            ],
            'keahlian_braille' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'null'       => true,
            ],
            'keahlian_bahasa_isyarat' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'null'       => true,
            ],
            'npwp' => [
                'type'       => 'VARCHAR',
                'constraint' => '25',
                'null'       => true,
            ],
            'nama_wajib_pajak' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => true,
            ],
            'kewarganegaraan' => [
                'type'       => 'VARCHAR',
                'constraint' => '2',
                'null'       => true,
            ],
            'bank' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'nomor_rekening_bank' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'rekening_atas_nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'nik' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'no_kk' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'karpeg' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => true,
            ],
            'karis_karsu' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
                'null'       => true,
            ],
            'lintang' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
                'null'       => true,
            ],
            'bujur' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
                'null'       => true,
            ],
            'nuks' => [
                'type'       => 'VARCHAR',
                'constraint' => '22',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_staf', true);
        $this->forge->createTable('staf');
    }

    public function down()
    {
        //
    }
}
