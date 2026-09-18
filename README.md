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
cd sims-skendava
composer update
chown -R www-data:www-data /var/www/sims-skendava/writable/
chmod -R 775 /var/www/sims-skendava/writable/
```
Buat simbolic links
```bash
ln -s /etc/nginx/sites-available/sims-skendava /etc/nginx/sites-enabled/
```
Restart nginx
```bash
systemctl restart nginx
```
##Database
Buat database untuk aplikasi
```bash
mysql -u root
```
```mysql
MariaDB [(none)]> CREATE DATABASE skendava;
MariaDB [(none)]> CREATE USER 'skendava'@'%' IDENTIFIED BY 'password';
MariaDB [(none)]> GRANT ALL PRIVILEGES ON skendava.* TO 'skendava'@'%';
MariaDB [(none)]> FLUSH PRIVILEGES;
MariaDB [(none)]> EXIT
```


## Pengaturan
Buat file .env dari template
```bash
cp env .env
```
Edit file .env dan ubah ENVIRONMENT sesuai kebutuhan
```bash
#CI_ENVIRONMENT = production
CI_ENVIRONMENT = development
```
Sesuaikan pengaturan DATABASE
```bash
database.default.hostname = localhost
database.default.database = skendava
database.default.username = skendava
database.default.password = password
database.default.DBDriver = MySQLi
# database.default.DBPrefix =
database.default.port = 3306
```
```bash

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
