<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserDetailsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_staf' => ['type' => 'INT', 'constraint' => 3, 'unsigned' => true, 'auto_increment' => true],
            'id_user' => ['type' => 'INT', 'constraint' => 3, 'unsigned' => true],
            'kode_staf' => ['type' => 'INT', 'constraint' => 3],
            'nama' => ['type' => 'CHAR', 'constraint' => '50', 'null' => true],
            'nama_gelar' => ['type' => 'CHAR', 'constraint' => '60', 'null' => true],
            'nuptk' => ['type' => 'CHAR', 'constraint' => '50', 'null' => true],
            'jk' => ['type' => 'CHAR', 'constraint' => '1', 'null' => true],
            'tempat_lahir' => ['type' => 'CHAR', 'constraint' => '50', 'null' => true],
            'tanggal_lahir' => ['type' => 'CHAR', 'constraint' => '10', 'null' => true],
            'nik' => ['type' => 'CHAR', 'constraint' => '50', 'null' => true],
            'nip' => ['type' => 'CHAR', 'constraint' => '50', 'null' => true],
            'status_kepegawaian' => ['type' => 'CHAR', 'constraint' => '50', 'null' => true],
            'hp' => ['type' => 'CHAR', 'constraint' => '15', 'null' => true],
            'email' => ['type' => 'CHAR', 'constraint' => '50', 'null' => true],
            'tugas_tambahan' => ['type' => 'CHAR', 'constraint' => '50', 'null' => true],

        ]);
        $this->forge->addKey('id_staf', true);
        // Link to Shield's core users table
        $this->forge->addForeignKey('id_user', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('staf');
    }

    public function down()
    {
        //
    }
}
