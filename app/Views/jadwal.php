<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <h3 class="mb-4">Sistem Jadwal Pelaksanaan Belajar Mengajar (PBM)</h3>

    <!-- Notifikasi Flashdata -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="row mb-4">
        <!-- Card Import Excel -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Import File Excel</div>
                <div class="card-body">
                    <form action="<?= base_url('jadwal/import') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label for="file_excel" class="form-label">Upload jadwal_PBM.xlsx</label>
        <input class="form-control" type="file" id="file_excel" name="file_excel" accept=".xlsx, .xls" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Import Data</button>
</form>
                </div>
            </div>
        </div>

        <!-- Card Filter & Pencarian -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">Cari Jadwal PBM</div>
                <div class="card-body">
                   <form action="<?= base_url('jadwal') ?>" method="get" class="row g-2">
    <div class="col-md-4">
        <input type="text" name="nama_guru" class="form-control" placeholder="Nama Guru..." value="<?= esc($nama_guru) ?>">
    </div>
    <div class="col-md-4">
        <select name="nama_hari" class="form-select">
            <option value="">-- Semua Hari --</option>
            <?php foreach ($list_hari as $hari) : ?>
                <option value="<?= $hari ?>" <?= ($nama_hari == $hari) ? 'selected' : '' ?>><?= $hari ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-4">
        <input type="text" name="nama_rombel" class="form-control" placeholder="Nama Rombel (misal: X APHP 1)..." value="<?= esc($nama_rombel) ?>">
    </div>
    <div class="col-12 mt-3 text-end">
        <a href="<?= base_url('jadwal') ?>" class="btn btn-outline-secondary">Reset</a>
        <button type="submit" class="btn btn-success">Cari Jadwal</button>
    </div>
</form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data Jadwal -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Hari</th>
                            <th>Jam Ke-</th>
                            <th>Nama Guru</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas / Rombel</th>
                            <th>Nama Jurusan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($jadwal)) : ?>
                            <?php $no = 1; foreach ($jadwal as $row) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><span class="badge bg-info text-dark"><?= esc($row['nama_hari']) ?></span></td>
                                    <td>Jam ke-<?= esc($row['jam_ke']) ?></td>
                                    <td><strong><?= esc($row['nama_guru']) ?></strong></td>
                                    <td><?= esc($row['mapel']) ?></td>
                                    <td><span class="badge bg-success"><?= esc($row['nama_rombel']) ?></span> (<?= esc($row['kode_kelas']) ?>)</td>
                                    <td><?= esc($row['nama_jurusan'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center py-3 text-muted">Tidak ada data jadwal ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>