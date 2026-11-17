-- ==========================================================
-- WildGuard - Skema Database (MySQL / MariaDB)
-- Import lewat phpMyAdmin atau: mysql -u root < schema.sql
-- ==========================================================
DROP DATABASE IF EXISTS wildguard;
CREATE DATABASE wildguard CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE wildguard;

-- 1. Status perlindungan (nasional / IUCN / CITES)
CREATE TABLE status_perlindungan (
  id_status    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama_status  VARCHAR(60)  NOT NULL UNIQUE,
  sumber       ENUM('Nasional','IUCN','CITES') NOT NULL,
  keterangan   VARCHAR(255) NULL
) ENGINE=InnoDB;

-- 2. Lokasi sebaran
CREATE TABLE lokasi (
  id_lokasi    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama_lokasi  VARCHAR(100) NOT NULL UNIQUE,
  pulau        VARCHAR(60)  NOT NULL
) ENGINE=InnoDB;

-- 3. Spesies (flora & fauna)  -> N:1 ke status_perlindungan
CREATE TABLE spesies (
  id_spesies   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama         VARCHAR(100) NOT NULL,
  nama_latin   VARCHAR(150) NOT NULL UNIQUE,
  tipe         ENUM('flora','fauna') NOT NULL,
  id_status    INT UNSIGNED NOT NULL,
  habitat      VARCHAR(150) NULL,
  populasi     VARCHAR(80)  NULL,
  deskripsi    TEXT NULL,
  fakta        TEXT NULL,
  gambar_url   VARCHAR(500) NULL,
  CONSTRAINT fk_spesies_status FOREIGN KEY (id_status)
    REFERENCES status_perlindungan(id_status)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 4. Tabel penghubung spesies <-> lokasi  (relasi M:N)
CREATE TABLE spesies_lokasi (
  id_spesies INT UNSIGNED NOT NULL,
  id_lokasi  INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_spesies, id_lokasi),
  CONSTRAINT fk_sl_spesies FOREIGN KEY (id_spesies)
    REFERENCES spesies(id_spesies) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_sl_lokasi FOREIGN KEY (id_lokasi)
    REFERENCES lokasi(id_lokasi) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Kategori artikel
CREATE TABLE kategori_artikel (
  id_kategori   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama_kategori VARCHAR(60) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- 6. Artikel  -> N:1 ke kategori_artikel
CREATE TABLE artikel (
  id_artikel  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_kategori INT UNSIGNED NOT NULL,
  judul       VARCHAR(200) NOT NULL,
  slug        VARCHAR(220) NOT NULL UNIQUE,
  isi         TEXT NOT NULL,
  tanggal     DATE NOT NULL,
  gambar_url  VARCHAR(500) NULL,
  CONSTRAINT fk_artikel_kategori FOREIGN KEY (id_kategori)
    REFERENCES kategori_artikel(id_kategori)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 7. Jenis temuan (untuk laporan)
CREATE TABLE jenis_temuan (
  id_jenis   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama_jenis VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- 8. Laporan dugaan perdagangan ilegal
--    -> N:1 ke jenis_temuan, N:1 (opsional) ke spesies
CREATE TABLE laporan (
  id_laporan       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_jenis         INT UNSIGNED NOT NULL,
  id_spesies       INT UNSIGNED NULL,
  lokasi_kejadian  VARCHAR(200) NOT NULL,
  tanggal_kejadian DATE NOT NULL,
  deskripsi        TEXT NOT NULL,
  status_laporan   ENUM('baru','diproses','selesai') NOT NULL DEFAULT 'baru',
  created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_laporan_jenis FOREIGN KEY (id_jenis)
    REFERENCES jenis_temuan(id_jenis) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_laporan_spesies FOREIGN KEY (id_spesies)
    REFERENCES spesies(id_spesies) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- ==========================================================
-- DATA AWAL (diambil dari data di script.js WildGuard)
-- ==========================================================
INSERT INTO status_perlindungan (nama_status, sumber, keterangan) VALUES
('Dilindungi','Nasional','Dilindungi undang-undang Indonesia'),
('Critically Endangered','IUCN','Sangat terancam punah di alam liar'),
('Endangered','IUCN','Terancam punah');

INSERT INTO lokasi (nama_lokasi, pulau) VALUES
('Hutan hujan Sumatera','Sumatera'),
('Hutan hujan Kalimantan','Kalimantan'),
('Nusa Tenggara Timur','Nusa Tenggara'),
('Taman Nasional Ujung Kulon','Jawa'),
('Taman Nasional Komodo','Nusa Tenggara');

INSERT INTO spesies (nama,nama_latin,tipe,id_status,habitat,populasi,deskripsi,fakta,gambar_url) VALUES
('Rafflesia Arnoldii','Rafflesia arnoldii','flora',1,'Hutan hujan tropis',NULL,
 'Tumbuhan parasit yang menempel pada akar tanaman merambat Tetrastigma. Bunganya bisa berdiameter satu meter dan menjadi bunga tunggal terbesar di dunia.',
 'Bunganya berbau seperti daging busuk untuk menarik lalat penyerbuk.',
 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTIyQI258x8TipVWLSCMXqAKoQfMJhoDvegRgsit7zH2w&s=10'),
('Bunga Bangkai','Amorphophallus titanum','flora',1,'Hutan dataran rendah',NULL,
 'Punya umbi dan daun; perbungaannya mekar sangat jarang dan merupakan yang tertinggi di dunia.',
 'Saat mekar mengeluarkan panas dan aroma busuk hingga ratusan meter.',
 'https://media.istockphoto.com/id/2173142816/id/foto/bunga-mayat-amorphophallus-titanum.jpg?s=612x612&w=0&k=20&c=AiLy1f3ZiXZtZhAXhJCArUFseWZvey750RGGxYDiRDU='),
('Anggrek Hitam','Coelogyne pandurata','flora',1,'Hutan rawa & tepi sungai',NULL,
 'Flora identitas Kalimantan Timur dengan corak hitam keunguan yang khas; populasinya menurun karena perburuan liar.',
 'Sering diburu untuk diperjualbelikan secara ilegal.',
 'https://ugm.ac.id/wp-content/uploads/2023/10/orchidplantcare.info_.jpg'),
('Cendana','Santalum album','flora',1,'Hutan tropis kering',NULL,
 'Kayu dan minyaknya harum, dipakai untuk parfum, kosmetik, dan upacara adat. Terus diburu sehingga populasinya menipis.',
 'Butuh puluhan tahun agar kayunya cukup wangi dan bernilai.',
 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQnj6mOqfV2hTHT09BwUA2BkBbwBgxa2WlaRaQp0HcSi4Z_YjIm_hjz4I0&s=10'),
('Orangutan Sumatera','Pongo abelii','fauna',2,'Hutan primer dataran rendah','Sekitar 14.000 ekor',
 'Hidup di kanopi hutan hujan; habitatnya rusak akibat sawit, pembalakan liar, dan perburuan.',
 'Bisa memakai ranting sebagai alat untuk mengambil makanan.',
 'https://cdn.betahita.id/5/8/9/7/5897.jpeg'),
('Harimau Sumatera','Panthera tigris sumatrae','fauna',2,'Hutan hujan & pegunungan','Kurang dari 600 ekor',
 'Satu-satunya subspesies harimau yang tersisa di Indonesia. Ancaman utama: hilangnya habitat dan perburuan.',
 'Pola lorengnya unik pada tiap individu, seperti sidik jari.',
 'https://www.marariversafarilodge.com/wp-content/uploads/2022/08/harimau-sumatra-1024x576.jpg'),
('Badak Jawa','Rhinoceros sondaicus','fauna',2,'Hutan hujan dataran rendah','Sekitar 76 ekor',
 'Hanya tersisa di Taman Nasional Ujung Kulon, Banten, sehingga sangat rentan terhadap bencana dan penyakit.',
 'Salah satu mamalia besar paling terancam punah di dunia.',
 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR9e70pAjniADcNWJNH5gGjV0aixTts5J4pxCBmI7HyyD-Iv4bQ8aYJolot&s=10'),
('Komodo','Varanus komodoensis','fauna',3,'Savana & hutan tropis kering','Sekitar 3.000 ekor',
 'Kadal terbesar yang masih hidup, hanya ditemukan di beberapa pulau di Taman Nasional Komodo.',
 'Bisa mendeteksi bau bangkai dari jarak beberapa kilometer.',
 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQKS95r_gl0yzA_orF5LYhY1Ip3H3ZHAWBrs3LLiz8sA&s=10');

-- id_spesies: 1 Rafflesia, 2 Bunga Bangkai, 3 Anggrek Hitam, 4 Cendana,
--             5 Orangutan, 6 Harimau, 7 Badak Jawa, 8 Komodo
-- id_lokasi : 1 Sumatera, 2 Kalimantan, 3 NTT, 4 Ujung Kulon, 5 TN Komodo
INSERT INTO spesies_lokasi (id_spesies, id_lokasi) VALUES
(1,1),(1,2),(2,1),(3,2),(4,3),(5,1),(6,1),(7,4),(8,5);

INSERT INTO kategori_artikel (nama_kategori) VALUES ('Edukasi'),('Lingkungan'),('Tips');

INSERT INTO artikel (id_kategori,judul,slug,isi,tanggal,gambar_url) VALUES
(1,'Apa itu Perdagangan Ilegal Satwa Liar?','apa-itu-perdagangan-ilegal',
 'Perdagangan ilegal satwa liar adalah kegiatan menangkap, memelihara, menjual, atau mengangkut satwa dan tumbuhan liar tanpa izin resmi. Kini banyak transaksi terjadi lewat media sosial dan marketplace.',
 '2024-04-20','https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=400&q=80'),
(2,'Dampak Perdagangan Ilegal terhadap Keanekaragaman Hayati','dampak-keanekaragaman-hayati',
 'Setiap spesies punya peran dalam ekosistem. Jika satu spesies terus diambil dari alam, rantai ekosistem terganggu dan kepunahan tidak bisa dibatalkan.',
 '2024-04-15','https://images.unsplash.com/photo-1448375240586-882707db888b?auto=format&fit=crop&w=400&q=80'),
(3,'Cara Melindungi Flora dan Fauna di Sekitar Kita','cara-melindungi-sekitar-kita',
 'Jangan membeli atau memelihara satwa dan tumbuhan dilindungi, kurangi sampah plastik, sebarkan informasi, dan laporkan dugaan perdagangan ilegal.',
 '2024-04-10','https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=400&q=80');

INSERT INTO jenis_temuan (nama_jenis) VALUES
('Perdagangan satwa hidup'),
('Bagian tubuh satwa (kulit, tanduk, gading, dll.)'),
('Tumbuhan atau anggrek langka'),
('Penjualan lewat media sosial atau marketplace'),
('Lainnya');

INSERT INTO laporan (id_jenis,id_spesies,lokasi_kejadian,tanggal_kejadian,deskripsi,status_laporan) VALUES
(4,3,'Marketplace online, penjual di Samarinda','2026-10-20','Akun penjual menawarkan anggrek hitam dalam jumlah banyak tanpa dokumen izin.','baru'),
(1,5,'Pasar hewan, Medan','2026-10-12','Terlihat anak orangutan dijual di kandang kecil oleh seorang penjual.','diproses');
