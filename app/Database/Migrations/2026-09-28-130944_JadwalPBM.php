<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class JadwalPBM extends Migration
{
    public function up()
    {
        // 1. Tabel Master Ref Jurusan / Kode Kelas (Tabel Pemetaan)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_jurusan' => [
                'type'       => 'INT',
                'constraint' => 2, // Digit ke-2 dari kode kelas (contoh: 1, 2, 3, dst)
            ],
            'nama_jurusan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100, // contoh: 'APHP', 'TKJ', 'AKL'
            ],
            'singkatan_jurusan' => [
                'type'       => 'VARCHAR',
                'constraint' => 20, // contoh: 'APHP', 'TKJ'
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('kode_jurusan');
        $this->forge->createTable('ref_jurusans');

        // 2. Tabel Master Kelas / Rombel
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_kelas' => [
                'type'       => 'VARCHAR',
                'constraint' => 10, // contoh: '121', '222'
            ],
            'jenjang' => [
                'type'       => 'ENUM',
                'constraint' => ['X', 'XI', 'XII'],
            ],
            'kode_jurusan' => [
                'type'       => 'INT',
                'constraint' => 2,
            ],
            'nomor_kelas' => [
                'type'       => 'INT',
                'constraint' => 3, // Digit ke-3 dari kode kelas
            ],
            'nama_rombel' => [
                'type'       => 'VARCHAR',
                'constraint' => 100, // contoh: 'X APHP 1', 'XI TKJ 2'
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('kode_kelas');
        $this->forge->addKey('nama_rombel');
        $this->forge->createTable('kelas');

        // 3. Tabel Master Guru
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_guru' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'nama_guru' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('nama_guru');
        $this->forge->createTable('guru');

        // 4. Tabel Transaksi Jadwal PBM
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'guru_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'kelas_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'mapel' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'hari' => [
                'type'       => 'TINYINT',
                'constraint' => 1, // 1=Senin, 2=Selasa, 3=Rabu, 4=Kamis, 5=Jumat
            ],
            'nama_hari' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'jam_ke' => [
                'type'       => 'INT',
                'constraint' => 2,
            ],
            'kode_kelas' => [
                'type'       => 'INT',
                'constraint' => 3,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('guru_id', 'guru', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('kelas_id', 'kelas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey(['hari', 'jam_ke']);
        $this->forge->createTable('jadwal_pbms');
    }

    public function down()
    {
        $this->forge->dropTable('jadwal_pbms');
        $this->forge->dropTable('guru');
        $this->forge->dropTable('kelas');
        $this->forge->dropTable('ref_jurusan');
    }
}
