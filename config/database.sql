CREATE DATABASE lol_db;
USE lol_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('mahasiswa', 'dosen') NOT NULL 
);

CREATE TABLE dosen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nip VARCHAR(20) NOT NULL UNIQUE,
    nama_dosen VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nrp VARCHAR(20) NOT NULL UNIQUE,
    nama_mahasiswa VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE mata_kuliah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_matkul VARCHAR(100) NOT NULL,
    dosen_id INT NOT NULL,
    ruangan VARCHAR(10),
    jadwal VARCHAR(20),
    FOREIGN KEY (dosen_id) REFERENCES dosen(id) ON DELETE CASCADE
);

CREATE TABLE tugas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matkul_id INT NOT NULL,
    judul_tugas VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    file_lampiran VARCHAR(255) NULL, -- INI TAMBAHANNYA
    deadline DATETIME NOT NULL,
    FOREIGN KEY (matkul_id) REFERENCES mata_kuliah(id) ON DELETE CASCADE
);

-- TRANSACTION TABLE

CREATE TABLE krs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    mata_kuliah_id INT NOT NULL,
    semester VARCHAR(5),
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE,
    FOREIGN KEY (mata_kuliah_id) REFERENCES mata_kuliah(id) ON DELETE CASCADE
);

CREATE TABLE pengumpulan_tugas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tugas_id INT NOT NULL,
    mahasiswa_id INT NOT NULL,
    file_tugas VARCHAR(255) NOT NULL,
    waktu_kumpul TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    nilai INT NULL,
    FOREIGN KEY (tugas_id) REFERENCES tugas(id) ON DELETE CASCADE,
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE
);
