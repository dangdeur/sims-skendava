<?= $this->extend('layout/main') ?>
<?= $this->section('konten') ?>

<?php //$this->include('layout/heading') ?>
<div class="mb-3 row">
  <label for="nama" class="col-sm-2 col-form-label">Nama</label>
  <div class="col-sm-10">
    <input type="text" readonly class="form-control-plaintext" id="nama" value="<?= $user?->nama ?? '' ?>">
  </div>
</div>

<div class="mb-3 row">
  <label for="nip" class="col-sm-2 col-form-label">email</label>
  <div class="col-sm-10">
    <input type="text" readonly class="form-control-plaintext" id="nip" value="<?= $user?->email ?? '' ?>">
  </div>
</div>
<div class="mb-3 row">
  <label for="nip" class="col-sm-2 col-form-label">NIP</label>
  <div class="col-sm-10">
    <input type="text" readonly class="form-control-plaintext" id="nip" value="<?= $user?->nip ?? '' ?>">
  </div>
</div>
<div class="mb-3 row">
  <label for="nuptk" class="col-sm-2 col-form-label">NUPTK</label>
  <div class="col-sm-10">
    <input type="text" readonly class="form-control-plaintext" id="nuptk" value="<?= $user?->nuptk ?? '' ?>">
  </div>
</div>

  <div class="mb-3 row">
    <label for="nuptk" class="col-sm-2 col-form-label">Tempat, Tanggal Lahir</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control-plaintext" id="nuptk" value="<?= $user?->tempat_lahir ? $user->tempat_lahir . ', ' . $user->tanggal_lahir : '' ?>">
    </div>
  </div>

  <div class="mb-3 row">
    <label for="nuptk" class="col-sm-2 col-form-label">NIK</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control-plaintext" id="nuptk" value="<?= $user?->nik ?? '' ?>">
    </div>
  </div>

  <div class="mb-3 row">
    <label for="nuptk" class="col-sm-2 col-form-label">No HP</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control-plaintext" id="nuptk" value="<?= $user?->hp ?? '' ?>">
    </div>
  </div>

   <div class="mb-3 row">
    <label for="nuptk" class="col-sm-2 col-form-label">Pangkat/Golongan</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control-plaintext" id="nuptk" value="<?= $user?->pangkat_golongan  ?? '' ?>">
    </div>
  </div>

  <div class="mb-3 row">
    <label for="nuptk" class="col-sm-2 col-form-label">Alamat</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control-plaintext" id="nuptk" value="<?= $user?->alamat_jalan. ' RT/RW ' . $user?->rt . '/' . $user?->rw .' Kelurahan ' . esc($user?->desa_kelurahan). ' Kecamatan ' . $user->kecamatan ?? '' ?>">
    </div>
  </div>

  <div class="mb-3 row">
    <label for="nuptk" class="col-sm-2 col-form-label">Jabatan</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control-plaintext" id="nuptk" value="<?= $user?->tugas_tambahan  ?? '' ?>">
    </div>
  </div>
    <?= $this->endSection() ?>