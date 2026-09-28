<?php

namespace App\Libraries;

class Kelas2Rombel
{
    private static $mapJenjang = [
        1 => 'X',
        2 => 'XI',
        3 => 'XII'
    ];

    /**
     * Mengonversi 3 digit kode kelas (misal: "121" -> Jenjang X, Jurusan APHP, Nomor 1)
     */
    public static function ubahKodeKeRombel(string $kodeKelas): array
    {
        $kodeStr = str_pad(trim($kodeKelas), 3, '0', STR_PAD_LEFT);

        $digitJenjang = (int) substr($kodeStr, 0, 1); // Digit 1
        $digitJurusan = (int) substr($kodeStr, 1, 1); // Digit 2
        $digitNomor   = (int) substr($kodeStr, 2, 1); // Digit 3

        $jenjang = self::$mapJenjang[$digitJenjang] ?? 'X';

        // Ambil nama jurusan dari tabel jurusan
        $db = \Config\Database::connect();
        $jurusan = $db->table('jurusan')
                      ->where('kode_jurusan', $digitJurusan)
                      ->get()
                      ->getRowArray();

        $singkatanJurusan = $jurusan ? $jurusan['singkatan_jurusan'] : 'JURUSAN-' . $digitJurusan;

        // Format nama rombel: Misal "X APHP 1"
        $namaRombel = "{$jenjang} {$singkatanJurusan}{$digitNomor}";

        return [
            'kode_kelas'   => $kodeStr,
            'jenjang'      => $jenjang,
            'kode_jurusan' => $digitJurusan,
            'nomor_kelas'  => $digitNomor,
            'nama_rombel'  => $namaRombel
        ];
    }
}