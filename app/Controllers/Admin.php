<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\StafModel;

class Admin extends BaseController
{
    protected $stafModel;

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
}

