<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatModel extends Model
{
    protected $table            = 'chats';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['sender_id', 'receiver_id', 'group_id', 'message', 'created_at'];

    // Mengambil riwayat chat privat beralaskan ID pengirim dan penerima
    public function getPrivateChat($user1, $user2)
    {
        return $this->where("(sender_id = $user1 AND receiver_id = $user2)")
                    ->orWhere("(sender_id = $user2 AND receiver_id = $user1)")
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    // Mengambil riwayat chat grup atau publik
    public function getGroupChat($groupId)
    {
        return $this->where('group_id', $groupId)
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }
}
