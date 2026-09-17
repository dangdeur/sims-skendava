<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class StafIntegrationSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // 1. Ambil User Provider dari Shield
        $userProvider = auth()->getProvider();

        // 2. Tarik semua data dari tabel staf Anda yang kolom user_id-nya masih kosong/null
        $daftarStaf = $db->table('staf')->where('user_id', null)->get()->getResultArray();

        foreach ($daftarStaf as $staf) {
            
            // Validasi: Jika email kosong atau tidak valid, lewati staf ini
            if (empty($staf['email']) || !filter_var($staf['email'], FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            // Validasi: Jika email ternyata sudah terdaftar sebelumnya di Shield, lewati
            if ($userProvider->findByCredentials(['email' => $staf['email']])) {
                continue;
            }

            // 3. Generate username otomatis dari kolom 'nama'
            // Mengubah "Budi Santoso, S.Pd" menjadi "budi_santoso"
            $cleanName = preg_replace('/[^a-zA-Z0-9]/', '', strstr($staf['nama'], ',', true) ?: $staf['nama']);
            $username  = strtolower(str_replace(' ', '_', trim($cleanName)));
            
            // Jika username terlalu pendek atau kosong karena regex, gunakan alternatif email prefix
            if (empty($username)) {
                $username = explode('@', $staf['email'])[0];
            }

            // Jika username kembar di database, tambahkan angka random di belakangnya
            while ($userProvider->findByIdentity($username)) {
                $username .= rand(1, 9);
            }

            // 4. Buat akun Shield baru
            $user = new User([
                'username' => $username,
                'email'    => $staf['email'],
                'password' => 'bersinar', // Password default otomatis di-hash oleh Shield
            ]);

            // Simpan akun ke database Shield (tabel 'users' & 'auth_identities')
            $userProvider->save($user);

            // 5. Ambil ID dari user Shield yang baru saja dibuat
            $newShieldUserId = $userProvider->getInsertID();
            $userInstance    = $userProvider->find($newShieldUserId);

            if ($userInstance) {
                // Berikan grup akses default (misal: 'user')
                $userInstance->addGroup('user');

                // Aktifkan akun langsung agar staf bisa langsung login tanpa verifikasi email
                $userInstance->activate();

                // 6. Update tabel staf: masukkan 'user_id' Shield ke data staf yang bersangkutan
                $db->table('staf')
                   ->where('id_staf', $staf['id_staf'])
                   ->update(['user_id' => $newShieldUserId]);
            }
        }
    }
}
