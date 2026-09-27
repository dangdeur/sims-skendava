<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Impor Data Staf</title>
    <link rel="stylesheet" href="https://jsdelivr.net">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Impor Data Staf (Excel)</h5>
                </div>
                <div class="card-body">
                    
                    <!-- Alert Notifikasi -->
                    <?php if (session()->getFlashdata('info')): ?>
                        <div class="alert alert-info"><?= session()->getFlashdata('info') ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/staf/prosesimpor') ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="file_excel" class="form-label">Pilih Berkas Excel (.xls, .xlsx)</label>
                            <input class="form-control" type="file" id="file_excel" name="file_excel" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Unggah dan Impor</button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
