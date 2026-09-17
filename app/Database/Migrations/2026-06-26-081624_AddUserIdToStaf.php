<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToStaf extends Migration
{
    public function up()
    {
        // 1. Definisikan kolom user_id baru
        $fields = [
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // Diset null agar data staf yang belum punya user tidak error
                'after'      => 'id_staf', // Meletakkan kolom setelah id_staf (opsional)
            ],
        ];

        // 2. Tambahkan kolom ke tabel staf
        $this->forge->addColumn('staf', $fields);

        // 3. Tambahkan Foreign Key yang menghubungkan ke tabel users milik Shield
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->processIndexes('staf');
    }

    public function down()
    {
        // Menghapus foreign key terlebih dahulu sebelum menghapus kolom
        $this->forge->dropForeignKey('staf', 'staf_user_id_foreign');
        $this->forge->dropColumn('staf', 'user_id');
    }
}
