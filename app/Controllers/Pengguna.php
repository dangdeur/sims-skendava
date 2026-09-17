<?php

namespace App\Controllers;

// use App\Models\ChatModel;
use App\Models\StafModel;
// use App\Libraries\FirebaseToken;


class Pengguna extends BaseController
{
    protected $StafModel;
    // protected $chatModel;

    public function __construct()
    {
        // $this->chatModel = new ChatModel();
        $this->StafModel = new StafModel();
    }

    public function getIndex(): string
    {
        // Ambil profil staf yang sedang login menggunakan helper kustom kita
        $staf = staf_profile();

        // Jika user yang login ternyata tidak terikat dengan data staf manapun


        // Kirim data staf ke View
        $data = [
            'nama'   => $staf->nama,
            'nuptk'  => $staf->nuptk,
            'nip'    => $staf->nip,
            'hp'     => $staf->hp,
            'status' => $staf->status_kepegawaian ?? 'Tidak Diketahui'
        ];
        $pengaturan = config('Pengaturan');


        return view('pengguna/dashboard', ['pengaturan' => $pengaturan, 'user' => $staf]);
    }
    public function getProfil(): string
    {
        // Ambil profil staf yang sedang login menggunakan helper kustom kita
        $staf = staf_profile();

        // Jika user yang login ternyata tidak terikat dengan data staf manapun


        // Kirim data staf ke View
        $data = [
            'nama'   => $staf->nama,
            'nuptk'  => $staf->nuptk,
            'nip'    => $staf->nip,
            'hp'     => $staf->hp,
            'status' => $staf->status_kepegawaian ?? 'Tidak Diketahui'
        ];
        $pengaturan = config('Pengaturan');
        // d($pengaturan);
        //$user = auth()->user();

        return view('pengguna/profil', ['pengaturan' => $pengaturan, 'user' => $staf]);
    }

    public function postSaveFcmToken()
{
    // $userModel = new \App\Models\UserDetailModel();
    $userId    = session()->get('user_id') ?? 1; 
    $fcmToken  = $this->request->getPost('fcm_token');

    if (!$fcmToken) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Token FCM kosong dari client.']);
    }

    // Eksekusi update dan cek apakah berhasil
    if ($this->userDetailModel->update($userId, ['fcm_token' => $fcmToken])) {
        return $this->response->setJSON(['status' => 'success', 'message' => 'Token berhasil disimpan.']);
    } else {
        // Tampilkan error database jika ada kesalahan struktur tabel
        return $this->response->setJSON([
            'status' => 'error', 
            'message' => 'Gagal simpan ke DB.',
            'errors' => $this->userDetailModel->errors()
        ])->setStatusCode(500);
    }
}


    // Method untuk mengirim pesan
    public function postSendMessage()
    {
        $senderId   = $this->request->getPost('sender_id');
        $message    = $this->request->getPost('message');
        $receiverId = $this->request->getPost('receiver_id'); // Isi jika privat / multi-user
        $groupId    = $this->request->getPost('group_id');    // Isi jika grup / publik

        // 1. Simpan ke Database
        $data = [
            'sender_id'   => $senderId,
            'message'     => $message,
            'receiver_id' => $receiverId ? $receiverId : null,
            'group_id'    => $groupId ? $groupId : null,
        ];

        $this->chatModel->insert($data);

    

        // 3. Kirim Push Notification via FCM (Firebase)
        $this->getSendPushNotification($receiverId, $groupId, $message);

        return $this->response->setJSON(['status' => 'success']);
    }

    // Fungsi CURL untuk memicu Firebase Cloud Messaging v1
    private function getSendPushNotification($receiverId, $groupId, $message)
    {
        $accessToken = FirebaseToken::getAccessToken();
        // Ambil token FCM tujuan dari database user Anda
        $targetToken = $this->userDetailModel->getFcmToken($receiverId);
        //$targetToken = "CONTOH_TOKEN_FCM_USER"; 

        $url = 'https://googleapis.com';

        $payload = [
            'message' => [
                'token' => $targetToken,
                'notification' => [
                    'title' => $groupId ? 'Pesan Baru di Grup' : 'Pesan Privat Baru',
                    'body' => $message
                ],
                'webpush' => [
                    'notification' => [
                        'icon' => '<?= base_url() ?>web-app-manifest-512x512.png'
                    ]
                ]
            ]
        ];

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_exec($ch);
        curl_close($ch);
    }
}
