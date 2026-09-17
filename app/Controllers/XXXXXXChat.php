<?php

namespace App\Controllers;

use App\Models\ChatModel;
use App\Models\UserDetailModel; // Asumsi Anda sudah memiliki UserModel pengelolaan sekolah
use Pusher\Pusher;
use App\Libraries\FirebaseToken;

class ChatController extends BaseController
{
    protected $chatModel;

    public function __construct()
    {
        $this->chatModel = new ChatModel();
    }

    // Halaman utama Chat
    public function getIndex()
    {
        $staf = staf_profile();
        // Contoh ID user yang sedang login dari session
        $userId = session()->get('user_id') ?? 1; 
        
        return view('pengguna/dashboard', [
            'userId' => $userId,'pengaturan' => $pengaturan, 'user' => $staf
        ]);
    }

    // Method untuk mengirim pesan
    public function postsendMessage()
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

        // 2. Trigger Real-time Broadcast via Pusher
        $options = ['cluster' => 'ap1', 'useTLS' => true];
        $pusher = new Pusher('PUSHER_KEY', 'PUSHER_SECRET', 'PUSHER_APP_ID', $options);

        $payload = ['sender_id' => $senderId, 'message' => $message];

        // Tentukan channel broadcast
        if ($groupId) {
            $channel = 'group-' . $groupId; // Untuk grup / publik
        } else {
            $channel = 'private-user-' . $receiverId; // Untuk privat
        }

        $pusher->trigger($channel, 'new-message', $payload);

        // 3. Kirim Push Notification via FCM (Firebase)
        $this->sendPushNotification($receiverId, $groupId, $message);

        return $this->response->setJSON(['status' => 'success']);
    }

    // Fungsi CURL untuk memicu Firebase Cloud Messaging v1
    private function getsendPushNotification($receiverId, $groupId, $message)
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
                        'icon' => '/assets/img/school-logo.png'
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
