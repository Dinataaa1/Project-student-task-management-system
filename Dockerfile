FROM php:8.2-apache

# 1. Install ekstensi PHP yang dibutuhkan untuk MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 2. Tingkatkan limit upload PHP (Sangat krusial untuk file tugas mahasiswa)
RUN { \
    echo 'file_uploads=On'; \
    echo 'upload_max_filesize=50M'; \
    echo 'post_max_size=55M'; \
    echo 'max_file_uploads=20'; \
    echo 'memory_limit=256M'; \
} > /usr/local/etc/php/conf.d/uploads.ini

# 3. Aktifkan modul rewrite Apache
RUN a2enmod rewrite

WORKDIR /var/www/html

# 4. Panggang (Copy) seluruh kode sumber dari laptop/VPS ke dalam Image Docker
COPY . /var/www/html/

# 5. Buat folder uploads jika belum ada untuk mencegah error saat chown
RUN mkdir -p /var/www/html/controllers/uploads

# 6. Berikan hak milik seluruh folder web kepada user Apache (www-data)
#    Ini memastikan proses unggah file tidak terkena error "Permission Denied"
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Opsional: Berikan hak akses tulis penuh khusus untuk folder uploads
RUN chmod -R 775 /var/www/html/controllers/uploads