<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JadwalPbmModel;
use App\Models\GuruModel;
use App\Models\KelasModel;
use App\Libraries\KelasConverter;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Jadwal extends BaseController
{
    protected $jadwalModel;
    protected $guruModel;
    protected $kelasModel;

    private $mapHari = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat'
    ];

    public function __construct()
    {
        $this->jadwalModel = new JadwalPbmModel();
        $this->guruModel   = new GuruModel();
        $this->kelasModel  = new KelasModel();
    }

    /**
     * Menangani GET /jadwal
     * Halaman Utama & Pencarian Jadwal
     */
    public function getIndex()
    {
        $namaGuru   = $this->request->getGet('nama_guru');
        $namaHari   = $this->request->getGet('nama_hari');
        $namaRombel = $this->request->getGet('nama_rombel');

        $data = [
            'title'       => 'Jadwal PBM Guru',
            'jadwal'      => $this->jadwalModel->getJadwal($namaGuru, $namaHari, $namaRombel),
            'nama_guru'   => $namaGuru,
            'nama_hari'   => $namaHari,
            'nama_rombel' => $namaRombel,
            'list_hari'   => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']
        ];

        return view('jadwal', $data);
    }

    /**
     * Menangani POST /jadwal/import
     * Proses Impor File Excel
     */
    public function postImport()
    {
        ini_set('memory_limit', '512M'); 
        $fileExcel = $this->request->getFile('file_excel');

        if (!$fileExcel || !$fileExcel->isValid() || $fileExcel->hasMoved()) {
            return redirect()->back()->with('error', 'Silakan pilih file Excel jadwal_PBM.xlsx yang valid.');
        }

        $spreadsheet = IOFactory::load($fileExcel->getTempName());
        // $spreadsheet->setReadDataOnly(true);
        // $spreadsheet->setReadEmptyCells(false);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, true);

        $db = \Config\Database::connect();
        $db->transStart();

        // Truncate tabel jadwal lama sebelum merekam jadwal baru
        $this->jadwalModel->truncate();

        // Header berada di baris ke-1
        $header     = $rows[1];
        $jamColumns = [];

        foreach ($header as $colLetter => $headerValue) {
            if (is_numeric($headerValue) && (int)$headerValue >= 10 && (int)$headerValue <= 56) {
                $jamColumns[$colLetter] = (int)$headerValue;
            }
        }

        // Loop Baris Excel (Data dimulai dari baris ke-2)
        for ($i = 2; $i <= count($rows); $i++) {
            $row      = $rows[$i];
            $kodeGuru = trim($row['A'] ?? '');
            $namaGuru = trim($row['B'] ?? '');
            $mapel    = trim($row['C'] ?? '');

            if (empty($namaGuru) || empty($mapel)) {
                continue;
            }

            // 1. Simpan / Dapatkan ID Master Guru
            $guru   = $this->guruModel->where('nama_guru', $namaGuru)->first();
            $guruId = $guru ? $guru['id'] : $this->guruModel->insert([
                'kode_guru' => $kodeGuru,
                'nama_guru' => $namaGuru
            ]);

            // 2. Loop Kolom Jam (10 - 56)
            foreach ($jamColumns as $colLetter => $kodeSlot) {
                $kodeKelas = trim($row[$colLetter] ?? '');

                if (!empty($kodeKelas) && is_numeric($kodeKelas)) {
                    $digitHari = (int) substr((string)$kodeSlot, 0, 1);
                    $digitJam  = (int) substr((string)$kodeSlot, 1, 1);

                    $namaHari = $this->mapHari[$digitHari] ?? 'Lainnya';
                    $jamKe    = $digitJam + 1;

                    // Konversi Kode Kelas 3 Digit ke Master Kelas
                    $kelasId = $this->getOrInsertKelasByKode($kodeKelas);

                    // Insert Transaksi Jadwal
                    $this->jadwalModel->insert([
                        'guru_id'   => $guruId,
                        'kelas_id'  => $kelasId,
                        'mapel'     => $mapel,
                        'hari'      => $digitHari,
                        'nama_hari' => $namaHari,
                        'jam_ke'    => $jamKe,
                        'kode_slot' => $kodeSlot
                    ]);
                }
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal mengimpor file jadwal!');
        }

        return redirect()->to('/jadwal')->with('success', 'Data Jadwal PBM Berhasil Diimpor!');
    }

    private function getOrInsertKelasByKode($kodeKelas)
    {
        $kodeStr = str_pad((string)$kodeKelas, 3, '0', STR_PAD_LEFT);
        $kelas   = $this->kelasModel->where('kode_kelas', $kodeStr)->first();

        if ($kelas) {
            return $kelas['id'];
        }

        // Panggil Helper Konversi Kode Kelas
        $dataRombel = Kelas2Rombel::convertKodeKeRombel($kodeStr);

        return $this->kelasModel->insert($dataRombel);
    }
}