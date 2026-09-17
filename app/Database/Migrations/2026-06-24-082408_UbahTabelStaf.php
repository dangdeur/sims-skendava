<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UbahTabelStaf extends Migration
{
    public function up()
    {
        // Menambahkan kolom user_id ke tabel staf jika belum ada
        $fields = [
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id_staf' // diletakkan setelah id_staf
            ],
        ];
        
        $this->forge->addColumn('staf', $fields);
        
        // Opsional: Tambahkan Foreign Key agar data konsisten dengan Shield
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->db->query('ALTER TABLE staf ADD CONSTRAINT fk_staf_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('staf', 'staf_user_id_foreign');
        $this->forge->dropColumn('staf', 'user_id');
    }
}
