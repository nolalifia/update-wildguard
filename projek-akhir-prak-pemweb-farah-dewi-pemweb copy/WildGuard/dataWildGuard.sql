-- WildGuard | Praktikum Sistem Database
-- MySQL 8.x (utf8mb4). Impor lewat HeidiSQL: File > Run SQL file.

CREATE DATABASE IF NOT EXISTS `wildguard` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `wildguard`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `laporan`, `artikel`, `spesies_lokasi`, `spesies`, `jenis_temuan`,
                     `kategori_artikel`, `lokasi`, `status_perlindungan`, `users`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(10) NOT NULL DEFAULT 'user' COMMENT 'admin atau user',
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `status_perlindungan` (
  `id_status` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_status` VARCHAR(60) NOT NULL,
  `sumber` ENUM('Nasional','IUCN','CITES') NOT NULL,
  `keterangan` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id_status`),
  UNIQUE KEY `status_perlindungan_nama_status_unique` (`nama_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lokasi` (
  `id_lokasi` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_lokasi` VARCHAR(100) NOT NULL,
  `pulau` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`id_lokasi`),
  UNIQUE KEY `lokasi_nama_lokasi_unique` (`nama_lokasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `spesies` (
  `id_spesies` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `nama_latin` VARCHAR(150) NOT NULL,
  `tipe` ENUM('flora','fauna') NOT NULL,
  `id_status` BIGINT UNSIGNED NOT NULL,
  `habitat` VARCHAR(150) DEFAULT NULL,
  `populasi` VARCHAR(80) DEFAULT NULL,
  `ciri_khas` VARCHAR(150) DEFAULT NULL,
  `ringkasan` VARCHAR(255) DEFAULT NULL,
  `deskripsi` TEXT,
  `fakta` TEXT,
  `gambar_url` VARCHAR(500) DEFAULT NULL,
  PRIMARY KEY (`id_spesies`),
  UNIQUE KEY `spesies_nama_latin_unique` (`nama_latin`),
  CONSTRAINT `spesies_id_status_foreign` FOREIGN KEY (`id_status`)
    REFERENCES `status_perlindungan` (`id_status`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `spesies_lokasi` (
  `id_spesies` BIGINT UNSIGNED NOT NULL,
  `id_lokasi` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id_spesies`, `id_lokasi`),
  CONSTRAINT `spesies_lokasi_id_spesies_foreign` FOREIGN KEY (`id_spesies`)
    REFERENCES `spesies` (`id_spesies`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `spesies_lokasi_id_lokasi_foreign` FOREIGN KEY (`id_lokasi`)
    REFERENCES `lokasi` (`id_lokasi`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `kategori_artikel` (
  `id_kategori` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_kategori` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`id_kategori`),
  UNIQUE KEY `kategori_artikel_nama_kategori_unique` (`nama_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `artikel` (
  `id_artikel` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_kategori` BIGINT UNSIGNED NOT NULL,
  `judul` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(220) NOT NULL,
  `isi` TEXT NOT NULL,
  `tanggal` DATE NOT NULL,
  `gambar_url` VARCHAR(500) DEFAULT NULL,
  `status` ENUM('menunggu','terbit') NOT NULL DEFAULT 'terbit',
  `id_user` BIGINT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id_artikel`),
  UNIQUE KEY `artikel_slug_unique` (`slug`),
  CONSTRAINT `artikel_id_kategori_foreign` FOREIGN KEY (`id_kategori`)
    REFERENCES `kategori_artikel` (`id_kategori`) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `artikel_id_user_foreign` FOREIGN KEY (`id_user`)
    REFERENCES `users` (`id`) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jenis_temuan` (
  `id_jenis` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_jenis` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_jenis`),
  UNIQUE KEY `jenis_temuan_nama_jenis_unique` (`nama_jenis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `laporan` (
  `id_laporan` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_jenis` BIGINT UNSIGNED NOT NULL,
  `id_spesies` BIGINT UNSIGNED DEFAULT NULL,
  `id_user` BIGINT UNSIGNED DEFAULT NULL,
  `lokasi_kejadian` VARCHAR(200) NOT NULL,
  `tanggal_kejadian` DATE NOT NULL,
  `deskripsi` TEXT NOT NULL,
  `lampiran` VARCHAR(255) DEFAULT NULL,
  `status_laporan` ENUM('baru','diproses','selesai') NOT NULL DEFAULT 'baru',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_laporan`),
  CONSTRAINT `laporan_id_jenis_foreign` FOREIGN KEY (`id_jenis`)
    REFERENCES `jenis_temuan` (`id_jenis`) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `laporan_id_spesies_foreign` FOREIGN KEY (`id_spesies`)
    REFERENCES `spesies` (`id_spesies`) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `laporan_id_user_foreign` FOREIGN KEY (`id_user`)
    REFERENCES `users` (`id`) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Data awal
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
  (1, 'Admin WildGuard', 'admin@wildguard.test', '$2y$10$tHgbGnat8gM9OxjbCYu0juieuphkmlig4.Qdv/woFIUB4Zcxvhtf.', 'admin', '2026-11-07 08:00:00', '2026-11-07 08:00:00'),
  (2, 'Pelapor Demo', 'user@wildguard.test', '$2y$10$V9f/575V9u859UHfVbBeiu.ZpffuBm7Hx8jJ1gl.l5GvdP7mNBeoa', 'user', '2026-11-07 08:00:00', '2026-11-07 08:00:00');

INSERT INTO `status_perlindungan` (`id_status`, `nama_status`, `sumber`, `keterangan`) VALUES
  (1, 'Dilindungi', 'Nasional', 'Dilindungi undang-undang Indonesia'),
  (2, 'Critically Endangered', 'IUCN', 'Sangat terancam punah di alam liar'),
  (3, 'Endangered', 'IUCN', 'Terancam punah');

INSERT INTO `lokasi` (`id_lokasi`, `nama_lokasi`, `pulau`) VALUES
  (1, 'Hutan hujan Sumatera', 'Sumatera'),
  (2, 'Hutan hujan Kalimantan', 'Kalimantan'),
  (3, 'Nusa Tenggara Timur', 'Nusa Tenggara'),
  (4, 'Taman Nasional Ujung Kulon', 'Jawa'),
  (5, 'Taman Nasional Komodo', 'Nusa Tenggara');

INSERT INTO `spesies` (`id_spesies`, `nama`, `nama_latin`, `tipe`, `id_status`, `habitat`, `populasi`, `ciri_khas`, `ringkasan`, `deskripsi`, `fakta`, `gambar_url`) VALUES
  (1, 'Rafflesia Arnoldii', 'Rafflesia arnoldii', 'flora', 1, 'Hutan hujan tropis', NULL, 'Tanpa akar, batang, daun', 'Bunga terbesar di dunia yang tumbuh liar di hutan hujan Sumatera dan Kalimantan.', 'Rafflesia arnoldii adalah tumbuhan parasit yang hidup menempel pada akar tanaman merambat Tetrastigma. Bunganya bisa tumbuh hingga diameter satu meter dan menjadi bunga tunggal terbesar di dunia. Karena tidak punya daun untuk berfotosintesis, ia bergantung sepenuhnya pada inangnya untuk bertahan hidup.', 'Bunga Rafflesia mengeluarkan bau seperti daging busuk untuk menarik lalat sebagai penyerbuk, makanya sering dijuluki ''bunga bangkai'' meski beda spesies dengan Amorphophallus titanum.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTIyQI258x8TipVWLSCMXqAKoQfMJhoDvegRgsit7zH2w&s=10'),
  (2, 'Bunga Bangkai', 'Amorphophallus titanum', 'flora', 1, 'Hutan dataran rendah', NULL, 'Tinggi bisa capai 3-4 m', 'Tumbuhan endemik Sumatera dengan bunga majemuk tertinggi di dunia.', 'Berbeda dari Rafflesia, Amorphophallus titanum punya umbi dan daun, hanya saja bunganya (yang sebenarnya adalah rangkaian bunga majemuk atau perbungaan) mekar sangat jarang, kadang hanya sekali dalam beberapa tahun. Perbungaannya adalah yang tertinggi di dunia.', 'Saat mekar, bunga bangkai bisa mengeluarkan panas dan aroma busuk yang menyebar sampai ratusan meter untuk menarik serangga penyerbuk.', 'https://media.istockphoto.com/id/2173142816/id/foto/bunga-mayat-amorphophallus-titanum.jpg?s=612x612&w=0&k=20&c=AiLy1f3ZiXZtZhAXhJCArUFseWZvey750RGGxYDiRDU='),
  (3, 'Anggrek Hitam', 'Coelogyne pandurata', 'flora', 1, 'Hutan rawa & tepi sungai', NULL, 'Bercak hitam di lidah bunga', 'Anggrek langka khas Kalimantan dengan bercak hitam khasnya di kelopak bunga.', 'Anggrek hitam adalah flora identitas Kalimantan Timur. Warna hijau kekuningan pada kelopaknya dipadukan dengan corak hitam keunguan yang khas membuatnya jadi salah satu anggrek paling dicari kolektor, sekaligus alasan populasinya terus menurun akibat perburuan liar.', 'Karena keindahannya, anggrek hitam sering diburu untuk diperjualbelikan secara ilegal, padahal statusnya dilindungi undang-undang.', 'https://ugm.ac.id/wp-content/uploads/2023/10/orchidplantcare.info_.jpg'),
  (4, 'Cendana', 'Santalum album', 'flora', 1, 'Hutan tropis kering', NULL, 'Kayu & akar beraroma khas', 'Pohon penghasil kayu wangi khas Nusa Tenggara Timur yang populasinya kian menipis.', 'Cendana dikenal karena kayu dan minyaknya yang harum, banyak dipakai untuk parfum, kosmetik, hingga upacara adat. Nilai ekonominya yang tinggi justru membuat pohon ini terus diburu dan ditebang, sehingga populasinya di alam liar semakin sulit ditemukan.', 'Cendana butuh puluhan tahun untuk tumbuh sampai kayunya cukup wangi dan bernilai, sehingga penebangan liar bisa berdampak sangat lama untuk pemulihannya.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQnj6mOqfV2hTHT09BwUA2BkBbwBgxa2WlaRaQp0HcSi4Z_YjIm_hjz4I0&s=10'),
  (5, 'Orangutan Sumatera', 'Pongo abelii', 'fauna', 2, 'Hutan primer dataran rendah', 'Sekitar 14.000 ekor', NULL, 'Kera besar endemik Sumatera yang habitatnya terus menyusut akibat deforestasi.', 'Orangutan Sumatera hidup di kanopi hutan hujan dan sangat bergantung pada hutan primer yang luas. Perusakan habitat akibat perkebunan sawit, pembalakan liar, dan perburuan membuat populasinya terus menurun drastis dalam beberapa dekade terakhir.', 'Orangutan Sumatera termasuk hewan paling cerdas di dunia dan bisa menggunakan alat sederhana, misalnya ranting untuk mengambil makanan dari celah pohon.', 'https://cdn.betahita.id/5/8/9/7/5897.jpeg'),
  (6, 'Harimau Sumatera', 'Panthera tigris sumatrae', 'fauna', 2, 'Hutan hujan & pegunungan', 'Kurang dari 600 ekor', NULL, 'Subspesies harimau terakhir di Indonesia yang kini jumlahnya kurang dari 600 ekor.', 'Setelah harimau Jawa dan Bali dinyatakan punah, harimau Sumatera menjadi satu-satunya subspesies harimau yang tersisa di Indonesia. Ancaman utamanya adalah hilangnya habitat dan perburuan liar untuk diambil kulit serta bagian tubuhnya.', 'Setiap harimau Sumatera punya pola garis loreng yang unik, mirip seperti sidik jari manusia, sehingga tidak ada dua individu dengan pola yang persis sama.', 'https://www.marariversafarilodge.com/wp-content/uploads/2022/08/harimau-sumatra-1024x576.jpg'),
  (7, 'Badak Jawa', 'Rhinoceros sondaicus', 'fauna', 2, 'Hutan hujan dataran rendah', 'Sekitar 76 ekor', NULL, 'Salah satu mamalia paling langka di dunia yang hanya tersisa di Ujung Kulon.', 'Badak Jawa kini hanya bisa ditemukan di satu tempat di dunia, yaitu Taman Nasional Ujung Kulon, Banten. Populasi kecil dan area penyebaran yang sangat terbatas membuat spesies ini sangat rentan terhadap bencana alam maupun penyakit.', 'Karena populasinya sangat kecil dan terisolasi di satu lokasi, badak Jawa disebut sebagai salah satu mamalia besar paling terancam punah di dunia.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR9e70pAjniADcNWJNH5gGjV0aixTts5J4pxCBmI7HyyD-Iv4bQ8aYJolot&s=10'),
  (8, 'Komodo', 'Varanus komodoensis', 'fauna', 3, 'Savana & hutan tropis kering', 'Sekitar 3.000 ekor', NULL, 'Kadal terbesar di dunia yang hanya hidup di beberapa pulau di Nusa Tenggara.', 'Komodo adalah kadal terbesar yang masih hidup saat ini dan hanya ditemukan secara alami di beberapa pulau di Taman Nasional Komodo. Air liurnya mengandung bakteri dan racun yang membantunya melumpuhkan mangsa berukuran besar.', 'Komodo bisa mendeteksi bau bangkai dari jarak beberapa kilometer menggunakan lidahnya yang bercabang, mirip cara kerja indra penciuman ular.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQKS95r_gl0yzA_orF5LYhY1Ip3H3ZHAWBrs3LLiz8sA&s=10');

INSERT INTO `spesies_lokasi` (`id_spesies`, `id_lokasi`) VALUES
  (1, 1),
  (1, 2),
  (2, 1),
  (3, 2),
  (4, 3),
  (5, 1),
  (6, 1),
  (7, 4),
  (8, 5);

INSERT INTO `kategori_artikel` (`id_kategori`, `nama_kategori`) VALUES
  (1, 'Edukasi'),
  (2, 'Lingkungan'),
  (3, 'Tips');

INSERT INTO `artikel` (`id_artikel`, `id_kategori`, `judul`, `slug`, `isi`, `tanggal`, `gambar_url`, `status`, `id_user`) VALUES
  (1, 1, 'Apa itu Perdagangan Ilegal Satwa Liar?', 'apa-itu-perdagangan-ilegal', 'Perdagangan ilegal satwa liar adalah kegiatan menangkap, memelihara, menjual, atau mengangkut satwa dan tumbuhan liar tanpa izin resmi. Yang diperdagangkan bisa berupa hewan hidup, kulit, tanduk, gading, sisik, maupun tumbuhan langka seperti anggrek.\n\nDi Indonesia, jenis yang dilindungi diatur dalam peraturan perundang-undangan. Menangkap, memiliki, menyimpan, atau memperdagangkan jenis yang dilindungi, baik dalam keadaan hidup maupun mati, termasuk pelanggaran hukum.\n\nPerdagangan ini terus ada karena ada permintaan, misalnya untuk hewan peliharaan eksotis, koleksi, hiasan, dan bahan obat tradisional. Kini banyak transaksi terjadi lewat media sosial dan marketplace, sehingga lebih sulit dipantau.', '2024-04-20', 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=400&q=80', 'terbit', NULL),
  (2, 2, 'Dampak Perdagangan Ilegal terhadap Keanekaragaman Hayati', 'dampak-keanekaragaman-hayati', 'Setiap spesies punya peran dalam ekosistemnya. Orangutan membantu menyebarkan biji, harimau menjaga jumlah hewan mangsa, dan lebah serta serangga penyerbuk menjaga tumbuhan tetap berbunga dan berbuah.\n\nKetika satu spesies diambil terus-menerus dari alam, rantai ini ikut terganggu. Populasi yang menyusut membuat spesies lebih rentan terhadap penyakit dan perubahan lingkungan, dan dalam kasus terburuk berujung pada kepunahan.\n\nKepunahan tidak bisa dibatalkan. Itu sebabnya mencegah perdagangan ilegal jauh lebih efektif daripada memulihkan populasi yang sudah hampir habis.', '2024-04-15', 'https://images.unsplash.com/photo-1448375240586-882707db888b?auto=format&fit=crop&w=400&q=80', 'terbit', NULL),
  (3, 3, 'Cara Melindungi Flora dan Fauna di Sekitar Kita', 'cara-melindungi-sekitar-kita', 'Melindungi flora dan fauna tidak harus dimulai dari hal besar. Langkah sederhana di sekitar kita sudah berarti.\n\nJangan membeli atau memelihara satwa dan tumbuhan dilindungi, sekalipun dijual murah atau terlihat menggemaskan. Cari tahu dulu asal-usul dan status perlindungannya sebelum membeli tanaman hias atau hewan peliharaan.\n\nKurangi jejak lingkungan kita: pilih produk kayu dan kertas yang bersertifikat, kurangi sampah plastik, dan jangan membuang sampah di hutan, sungai, atau pantai.\n\nTerakhir, sebarkan informasinya. Ajak teman dan keluarga mengenal spesies dilindungi, dan laporkan kalau menemukan dugaan perdagangan ilegal.', '2024-04-10', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=400&q=80', 'terbit', NULL),
  (4, 1, 'Mengenal Status Perlindungan: Dilindungi, CITES, dan IUCN', 'status-perlindungan', 'Status perlindungan sebuah spesies bisa berasal dari beberapa sistem yang berbeda, dan sering kali dibaca bersamaan.\n\nStatus dilindungi adalah penetapan di tingkat nasional. Spesies dengan status ini tidak boleh ditangkap, dipelihara, atau diperdagangkan tanpa izin.\n\nCITES adalah perjanjian internasional yang mengatur perdagangan lintas negara. Spesies dikelompokkan ke dalam Apendiks I, II, dan III sesuai tingkat pembatasan perdagangannya.\n\nIUCN Red List menilai risiko kepunahan sebuah spesies di alam, dari Least Concern sampai Critically Endangered. Ini adalah penilaian ilmiah, bukan aturan hukum, tetapi sering menjadi dasar penentuan prioritas konservasi.', '2024-04-05', 'https://images.unsplash.com/photo-1497250681960-ef046c08a56e?auto=format&fit=crop&w=400&q=80', 'terbit', NULL),
  (5, 2, 'Mengapa Hutan Hujan Terus Menyusut?', 'hutan-sumatera-menyusut', 'Hutan hujan tropis menyimpan sebagian besar keanekaragaman hayati daratan, tetapi luasnya terus berkurang. Penyebab utamanya antara lain pembukaan lahan untuk perkebunan, pembalakan liar, kebakaran, dan pembangunan infrastruktur.\n\nKetika hutan terpecah menjadi bagian-bagian kecil, satwa kehilangan ruang jelajah dan sumber makanan. Pertemuan satwa dengan permukiman juga makin sering terjadi dan sering berakhir merugikan satwa.\n\nMelindungi hutan berarti melindungi rumah ribuan spesies sekaligus menjaga air, udara, dan iklim yang kita butuhkan.', '2024-04-01', 'https://images.unsplash.com/photo-1511497584788-876760111969?auto=format&fit=crop&w=400&q=80', 'terbit', NULL),
  (6, 3, 'Cara Melaporkan Dugaan Perdagangan Satwa dengan Aman', 'melapor-dengan-aman', 'Kalau menemukan dugaan perdagangan satwa atau tumbuhan dilindungi, utamakan keselamatan diri. Jangan mendekati atau menegur pelaku sendirian.\n\nCatat hal yang kamu lihat: jenis satwa atau tumbuhan, lokasi, tanggal dan waktu, serta ciri-ciri penjual atau akun penjualnya. Kalau aman, ambil foto atau tangkapan layar tanpa mengganggu situasi.\n\nSampaikan laporan lewat formulir WildGuard di bawah, atau langsung ke Balai Konservasi Sumber Daya Alam (BKSDA) terdekat. Jangan membeli satwa tersebut dengan alasan ''menyelamatkan'', karena itu justru menjaga permintaan tetap ada.', '2024-03-28', 'https://images.unsplash.com/photo-1535338454770-8be927b5a00b?auto=format&fit=crop&w=400&q=80', 'terbit', NULL);

INSERT INTO `jenis_temuan` (`id_jenis`, `nama_jenis`) VALUES
  (1, 'Perdagangan satwa hidup'),
  (2, 'Bagian tubuh satwa (kulit, tanduk, gading, dll.)'),
  (3, 'Tumbuhan atau anggrek langka'),
  (4, 'Penjualan lewat media sosial atau marketplace'),
  (5, 'Lainnya');

INSERT INTO `laporan` (`id_laporan`, `id_jenis`, `id_spesies`, `id_user`, `lokasi_kejadian`, `tanggal_kejadian`, `deskripsi`, `lampiran`, `status_laporan`, `created_at`) VALUES
  (1, 4, 3, 2, 'Marketplace online, penjual di Samarinda', '2026-10-20', 'Akun penjual menawarkan anggrek hitam dalam jumlah banyak tanpa dokumen izin.', NULL, 'baru', '2026-10-20 09:15:00'),
  (2, 1, 5, NULL, 'Pasar hewan, Medan', '2026-10-12', 'Terlihat anak orangutan dijual di kandang kecil oleh seorang penjual.', NULL, 'diproses', '2026-10-12 14:30:00');