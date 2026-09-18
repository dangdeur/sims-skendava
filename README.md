# Sistem Administrasi Manajemen Sekolah

## Installasi Web Server

```bash
# apt -y install git composer nginx mariadb-server php-fpm php-intl php-curl php-mbstring php-xml php-mysql zip unzip php-zip php-imagick
# nano /etc/nginx/sites-available/sims-skendava
```
Isi dengan teks berikut

```bash
server {
        listen 9920;
	    root /var/www/sims-skendava/public;
        index index.php index.html index.htm index.nginx-debian.html;
        server_name _;
        location / {
            try_files $uri $uri/ /index.php$is_args$args;
        }
        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass unix:/run/php/php8.4-fpm.sock;
		    fastcgi_read_timeout 36000s;
        }
  	    location ~ /\.ht {
            deny all;
        }
}
```
## Clone aplikasi
```bash
cd /var/www/
git clone https://github.com/dangdeur/sims-skendava.git
composer update
```
Buat simbolic links
```bash
ln -s /etc/nginx/sites-available/sims-skendava /etc/nginx/sites-enabled/
```
Restart nginx
```bash
systemctl restart nginx
```


## Pengaturan
Buat file .env dari template dan sesuaikan parameter database
```bash
cp env .env
```
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
