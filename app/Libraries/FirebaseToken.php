<?php

namespace App\Libraries;

use Google\Auth\Credentials\ServiceAccountCredentials;
use CodeIgniter\Cache\CacheInterface;

class FirebaseToken
{
    public static function getAccessToken()
    {
        // Gunakan cache CI4 agar tidak perlu request token ke Google setiap kali mengirim chat
        $cache = \Config\Services::cache();
        $cachedToken = $cache->get('fcm_oauth_token');

        if ($cachedToken) {
            return $cachedToken;
        }

        // Lokasi file JSON rahasia Firebase Anda
        $jsonPath = ROOTPATH . 'fb_auth.json'; 
        $scopes   = ['https://googleapis.com'];

        $credentials = new ServiceAccountCredentials($scopes, $jsonPath);
        $tokenData   = $credentials->fetchAuthToken();

        // Simpan di cache selama 50 menit (Token asli hangus dalam 60 menit)
        $cache->save('fcm_oauth_token', $tokenData['access_token'], 3000);

        return $tokenData['access_token'];
    }
}
