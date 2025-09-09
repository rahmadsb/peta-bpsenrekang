ALTER TABLE `kecamatan` ADD `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
ALTER TABLE `kecamatan` ADD `kode_prov` VARCHAR(2) NOT NULL AFTER `id`;
ALTER TABLE `kecamatan` ADD `nama_provinsi` VARCHAR(255) NOT NULL AFTER `kode_prov`;
ALTER TABLE `kecamatan` ADD `kode_kabupaten` VARCHAR(4) NOT NULL AFTER `nama_provinsi`;
ALTER TABLE `kecamatan` ADD `nama_kabupaten` VARCHAR(255) NOT NULL AFTER `kode_kabupaten`;
ALTER TABLE `kecamatan` ADD `kode_kecamatan` VARCHAR(6) NOT NULL AFTER `nama_kabupaten`;
ALTER TABLE `kecamatan` ADD `nama_kecamatan` VARCHAR(255) NOT NULL AFTER `kode_kecamatan`;
ALTER TABLE `kecamatan` ADD `created_at` TIMESTAMP NULL DEFAULT NULL AFTER `nama_kecamatan`;
ALTER TABLE `kecamatan` ADD `updated_at` TIMESTAMP NULL DEFAULT NULL AFTER `created_at`;
