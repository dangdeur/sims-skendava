# Sistem Administrasi Manajemen Sekolah

## Installasi

```bash
apt -y install php-intl php-curl php-mbstring php-xml php-mysql zip unzip php-zip php-imagick
git clone https://github.com/dangdeur/ci4starter.git
```

## Pengaturan

app/Config/Pengaturan.php

import data Pendidik dan Tenaga kependidikan kedalam tabel staf
sesuaikan Commands/SyncStafToShield.php
$user = new User([
                 'username' => $email, // Menggunakan email sebagai username sesuai permintaan
                 'email'    => $email,
                 'password' => 'password', // Password default, atau gunakan env('PASSWORD')
                ]);
 $newUser->addGroup('staf');

```bash
php spark auth:sync-staf
```
