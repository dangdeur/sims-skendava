<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\StafModel;

class Admin extends BaseController
{
    protected $stafModel;
    private $Hari = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat'
    ];

    public function __construct()
    {
        $this->stafModel = new StafModel();
    }

    public function getIndex()
    {
        //
    }

    /**
     * Menangani GET /admin/staf/impor
     */
    public function getImporStaf()
    {
        if (!auth()->loggedIn() || !auth()->user()->inGroup('admin')) {
            return redirect()->to('/login');
        }

        return view('admin/impor_staf');
    }


    /**
     * Menangani POST /admin/staf/prosesimpor
     */
    public function postProsesImpor()
    {
        if (!auth()->loggedIn() || !auth()->user()->inGroup('admin')) {
            return redirect()->to('/login');
        }

        // Validasi file ungguhan
        $validationRule = [
            'file_excel' => [
                'label' => 'File Excel',
                'rules' => 'uploaded[file_excel]|ext_in[file_excel,xls,xlsx]',
            ],
        ];

        if (!$this->validate($validationRule)) {
            return redirect()->back()->with('error', $this->validator->getError('file_excel'));
        }

        $file = $this->request->getFile('file_excel');
        
        try {
            // Membaca file excel menggunakan PhpSpreadsheet
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheetData   = $spreadsheet->getActiveSheet()->toArray();

            $dataToInsert = [];
            
            // Loop baris excel (lewati baris 1 jika itu header)
            foreach ($sheetData as $index => $row) {
                if ($index === 4) continue; // Skip header

                // Sesuaikan urutan kolom indeks array ($row[0], $row[1], dst) dengan struktur excel Anda
                $dataToInsert[] = [
                    'nama'     => $row[1],
                    'nuptk'    => $row[2],
                    'jk'      => $row[3],
                    'tempat_lahir'      => $row[4],
                    'tanggal_lahir'      => $row[5],
                    'nip'      => $row[6],
                    'status_kepegawaian'      => $row[7],
                    'jenis_ptk'      => $row[8],
                    'agama'      => $row[9],
                    'alamat_jalan'      => $row[10],
                    'rt'      => $row[11],
                    'rw'      => $row[12],
                    'nama_dusun'      => $row[13],
                    'desa_kelurahan'      => $row[14],
                    'kecamatan'      => $row[15],
                    'kode_pos'      => $row[16],
                    'telepon'      => $row[17],
                    'hp'      => $row[18],
                    'email'      => $row[19],
                    'tugas_tambahan'      => $row[20],
                    'sk_cpns'      => $row[21],
                    'tanggal_cpns'      => $row[22],
                    'sk_pengangkatan'      => $row[23],
                    'tmt_pengangkatan'      => $row[24],
                    'lembaga_pengangkatan'      => $row[25],
                    'pangkat_golongan'      => $row[26],
                    'sumber_gaji'      => $row[27],
                    'nama_ibu_kandung'      => $row[28],
                    'status_perkawinan'      => $row[29],
                    'nama_suami_istri'      => $row[30],
                    'nip_suami_istri'      => $row[31],
                    'pekerjaan_suami_istri'      => $row[32],
                    'tmt_pns'      => $row[33],
                    'sudah_lisensi_kepala_sekolah'      => $row[34],
                    'pernah_diklat_kepengawasan'      => $row[35],
                    'keahlian_braille'      => $row[36],
                    'keahlian_bahasa_isyarat'      => $row[37],
                    'npwp'      => $row[38],
                    'nama_wajib_pajak'      => $row[39],
                     'kewarganegaraan'      => $row[40],
                    'bank'      => $row[41],
                    'nomor_rekening_bank'      => $row[42],
                    'rekening_atas_nama'      => $row[43],
                    'nik'      => $row[44],
                    'no_kk'      => $row[45],
                    'karpeg'      => $row[46],
                    'karis_karsu'      => $row[47],
                    'lintang'      => $row[48],
                    'bujur'      => $row[49],
                    'nuks'      => $row[50],
                   
                ];
            }

            if (!empty($dataToInsert)) {
                $this->stafModel->insertBatch($dataToInsert);
                return redirect()->to('/admin/staf')->with('success', 'Data staf berhasil diimpor.');
            }

            return redirect()->back()->with('error', 'File Excel kosong atau tidak valid.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membaca file: ' . $e->getMessage());
        }
    }

    public function getImportJadwal()
    {
        $filePath = WRITEPATH . 'uploads/jadwal_PBM.xlsx';
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        $db = \Config\Database::connect();
        $db->transStart();

        $guruModel   = new \App\Models\GuruModel();
        $kelasModel  = new \App\Models\KelasModel();
        $jadwalModel = new \App\Models\JadwalPbmModel();

        // 1. Ambil header jam/hari (kolom 10 sampai 56)
        $header = $rows[1];
        $jamColumns = [];
        foreach ($header as $colLetter => $headerValue) {
            if (is_numeric($headerValue) && (int)$headerValue >= 10 && (int)$headerValue <= 56) {
                $jamColumns[$colLetter] = (int)$headerValue;
            }
        }

        // 2. Loop Data
        for ($i = 2; $i <= count($rows); $i++) {
            $row = $rows[$i];

            $kodeGuru = trim($row['A'] ?? '');
            $namaGuru = trim($row['B'] ?? '');
            $mapel    = trim($row['C'] ?? '');

            if (empty($namaGuru) || empty($mapel)) {
                continue;
            }

            // Insert/Get Guru
            $guru = $guruModel->where('nama_guru', $namaGuru)->first();
            $guruId = $guru ? $guru['id'] : $guruModel->insert([
                'kode_guru' => $kodeGuru,
                'nama_guru' => $namaGuru
            ]);

            // Unpivot kolom 10 - 56
            foreach ($jamColumns as $colLetter => $kodeSlot) {
                $kodeKelas = trim($row[$colLetter] ?? '');

                if (!empty($kodeKelas) && is_numeric($kodeKelas)) {
                    // Parsing kode slot jadwal (2 digit: digit 1 = hari, digit 2 = jam ke)
                    $digitHari = (int) substr((string)$kodeSlot, 0, 1);
                    $digitJam  = (int) substr((string)$kodeSlot, 1, 1);

                    $namaHari = $this->mapHari[$digitHari] ?? 'Lainnya';
                    $jamKe    = $digitJam + 1; // 0 = Jam ke-1, 1 = Jam ke-2, dst.

                    // Dapatkan ID Kelas melalui helper konversi independen
                    $kelasId = $this->getOrInsertKelasByKode($kodeKelas, $kelasModel);

                    // Insert Jadwal Detail
                    $jadwalModel->insert([
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

        return "Proses Impor Berhasil Dijalankan!";
    }

    private function getOrInsertKelasByKode($kodeKelas, $kelasModel)
    {
        $kodeStr = str_pad((string)$kodeKelas, 3, '0', STR_PAD_LEFT);
        $kelas   = $kelasModel->where('kode_kelas', $kodeStr)->first();

        if ($kelas) {
            return $kelas['id'];
        }

        // Gunakan fungsi konversi independen
        $dataRombel = KelasConverter::convertKodeKeRombel($kodeStr);

        return $kelasModel->insert($dataRombel);
    }
}

