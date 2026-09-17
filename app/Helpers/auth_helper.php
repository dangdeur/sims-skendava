<?php

use App\Models\StafModel;

/**
 * Mengambil seluruh data staf dari user yang sedang login saat ini.
 * 
 * @return object|null
 */
if (! function_exists('staf_profile')) {
    function staf_profile()
    {
        // Pastikan user sudah login via Shield
        if (! auth()->loggedIn()) {
            return null;
        }

        // Ambil ID user dari Shield yang sedang aktif
        $userId = auth()->id();

        // Cari data staf berdasarkan user_id tersebut
        $stafModel = model(StafModel::class);
        return $stafModel->where('user_id', $userId)->first();
    }
}
