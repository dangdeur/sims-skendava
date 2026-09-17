<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class SyncStafToShield extends BaseCommand
{
    protected $group = 'Auth';
    protected $name = 'auth:sync-staf';
    protected $description = 'Menambahkan user Shield secara manual dari tabel staf.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $userModel = model(UserModel::class);

        // DEBUG: Cek total baris asli di tabel staf terlebih dahulu
        $totalStafAsli = $db->table('staf')->countAllResults();
        CLI::write("Total data di tabel staf saat ini: " . $totalStafAsli, 'cyan');

        // Ambil semua data staf tanpa filter ketat terlebih dahulu untuk kita periksa di dalam loop
        $stafList = $db->table('staf')->get()->getResultArray();

        if (empty($stafList)) {
            CLI::error("Tabel 'staf' benar-benar kosong. Isi data terlebih dahulu!");
            return;
        }

        $successCount = 0;
        $failCount = 0;
        $skippedCount = 0;

        foreach ($stafList as $staf) {
            // Ambil email dan bersihkan dari spasi yang tidak sengaja terinput
            $email = isset($staf['email']) ? trim($staf['email']) : '';

            // 1. VALIDASI: Jika email kosong, buatkan username/email dummy atau lewati
            if ($email === '') {
                CLI::write("Staf ID {$staf['id_staf']} ({$staf['nama']}) dilewati karena kolom EMAIL KOSONG.", 'yellow');
                $skippedCount++;
                continue;
            }

            // 2. VALIDASI: Pastikan format email valid untuk standar Shield
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                CLI::write("Staf ID {$staf['id_staf']} dilewati karena format email tidak valid: '{$email}'", 'yellow');
                $skippedCount++;
                continue;
            }

            // 3. CEK DUPLIKASI: Apakah email sudah terdaftar di tabel users milik Shield?
            $existingUser = $userModel->findByCredentials(['email' => $email]);
            if ($existingUser) {
                // Jika user sudah ada di Shield tapi user_id di tabel staf masih kosong, langsung hubungkan
                if (empty($staf['user_id'])) {
                    $db->table('staf')
                        ->where('id_staf', $staf['id_staf'])
                        ->update(['user_id' => $existingUser->id]);
                    CLI::write("User Shield sudah ada. Menghubungkan user_id untuk: {$email}", 'blue');
                    $successCount++;
                } else {
                    CLI::write("Staf dengan email {$email} sudah terhubung ke Shield. Dilewati.", 'dark_gray');
                    $skippedCount++;
                }
                continue;
            }

            // 4. PROSES SIMPAN: Buat Entity User Baru
            $user = new User([
                'username' => $email,
                'email' => $email,
                'password' => env('PASSWORD')
            ]);

            if ($userModel->save($user)) {
                $userId = $userModel->getInsertID();
                $newUser = $userModel->find($userId);

                // Tambahkan ke grup default
                $newUser->addGroup('user');

                // Update kolom user_id di tabel staf
                $db->table('staf')
                    ->where('id_staf', $staf['id_staf'])
                    ->update(['user_id' => $userId]);

                $successCount++;
                CLI::write("Berhasil membuat user & menghubungkan: {$email}", 'green');
            } else {
                $failCount++;
                CLI::error("Gagal menyimpan user untuk email: {$email}. Error: " . implode(', ', $userModel->errors()));
            }
        }

        CLI::write("\nProses Selesai!", 'blue');
        CLI::write("Total Berhasil Dibuat/Dihubungkan: {$successCount}", 'green');
        CLI::write("Total Dilewati: {$skippedCount}", 'yellow');
        CLI::write("Total Gagal Sistem: {$failCount}", 'red');
    }



}