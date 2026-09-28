<?php

namespace App\Data;

/**
 * Data statis aplikasi. Tugas ini belum memakai database,
 * jadi semua data ditulis sebagai array PHP di sini.
 */
class DataStatis
{
    public const NRP_PEMILIK = '5025241056';

    public const TEMA_AGENT_DEFAULT = 'General Assistant Agent';

    /** Total SKS untuk lulus S1 Teknik Informatika ITS. */
    public const SKS_LULUS = 144;

    /** Pilihan kategori di formulir pengumpulan ide (/ide-agent). Kunci dikirim form, nilai ditampilkan. */
    public const KATEGORI_IDE = [
        'keamanan' => 'Keamanan Sistem',
        'produktivitas' => 'Produktivitas Developer',
        'pendidikan' => 'Pendidikan',
        'data' => 'Analisis Data',
        'lainnya' => 'Lainnya',
    ];

    /**
     * Daftar mahasiswa, diindeks berdasarkan NRP.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function mahasiswa(): array
    {
        return [
            self::NRP_PEMILIK => [
                'nrp' => self::NRP_PEMILIK,
                'nama' => 'Dimas Setiaji',
                'inisial' => 'DS',
                'foto' => 'img/dimas.jpg', // relatif terhadap folder public, dipanggil lewat asset()
                'departemen' => 'Teknik Informatika',
                'fakultas' => 'FTEIC',
                'angkatan' => 2024,
                'semester' => 5,
                'kelas' => 'PBKK B / Kelompok B1',
                'asal' => 'Kepanjen, Kabupaten Malang',
                'email' => '5025241056@student.its.ac.id',
                'linkedin' => 'https://www.linkedin.com/in/dimas-setiaji-596b33335/',
                'ringkasan' => 'Tertarik pada pengembangan perangkat lunak, pengembangan gim, dan sistem interaktif. '
                    .'Terbiasa membangun aplikasi dan merancang antarmuka, dengan dasar pemrograman dan pemecahan masalah yang kuat.',
                'minat' => ['Software Development', 'Game Development', 'Interactive Systems'],
                'keahlian' => ['C/C++', 'C#', 'Python', 'JavaScript', 'HTML & CSS', 'Figma'],
                'peran' => [
                    ['nama' => 'Asisten Dosen Dasar Pemrograman dan Struktur Data', 'tempat' => 'ITS', 'waktu' => 'Agustus 2025 - sekarang'],
                    ['nama' => 'Problem Setter dan Liaison Officer NPC Schematics', 'tempat' => 'ITS', 'waktu' => 'Januari - November 2025'],
                ],
                'prestasi' => [
                    ['tahun' => 2025, 'nama' => 'Finalis GEMASTIK XVIII, Divisi Pengembangan Aplikasi Permainan'],
                    ['tahun' => 2024, 'nama' => 'Finalis National Programming Contest (NPC) Schematics ITS'],
                    ['tahun' => 2024, 'nama' => 'Juara 3 Lomba Esai Nasional SMAU-BP'],
                    ['tahun' => 2023, 'nama' => 'Peringkat 4 OSN-K Informatika Kabupaten Malang'],
                    ['tahun' => 2023, 'nama' => 'Juara 1 Lomba Esai Nasional Universitas Jember'],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function cariMahasiswa(string $nrp): ?array
    {
        return self::mahasiswa()[$nrp] ?? null;
    }

    /**
     * Riwayat studi per semester, sesuai transkrip. IP null berarti semester masih berjalan
     * dan nilai mata kuliahnya belum keluar.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function riwayatStudi(): array
    {
        return [
            [
                'semester' => 1,
                'tahun' => '2024/2025 Gasal',
                'sks' => 17,
                'ip' => 3.82,
                'mata_kuliah' => [
                    ['nama' => 'Dasar Pemrograman', 'sks' => 4, 'nilai' => 'A'],
                    ['nama' => 'Sistem Digital', 'sks' => 3, 'nilai' => 'AB'],
                    ['nama' => 'Aljabar Linier', 'sks' => 3, 'nilai' => 'AB'],
                    ['nama' => 'Sistem Basis Data', 'sks' => 4, 'nilai' => 'A'],
                    ['nama' => 'Kalkulus 1', 'sks' => 3, 'nilai' => 'A'],
                ],
            ],
            [
                'semester' => 2,
                'tahun' => '2024/2025 Genap',
                'sks' => 19,
                'ip' => 3.82,
                'mata_kuliah' => [
                    ['nama' => 'Pengantar Teknologi Elektro dan Informatika Cerdas', 'sks' => 2, 'nilai' => 'A'],
                    ['nama' => 'Struktur Data', 'sks' => 4, 'nilai' => 'A'],
                    ['nama' => 'Sistem Operasi', 'sks' => 4, 'nilai' => 'AB'],
                    ['nama' => 'Organisasi Komputer', 'sks' => 3, 'nilai' => 'AB'],
                    ['nama' => 'Komputasi Numerik', 'sks' => 3, 'nilai' => 'A'],
                    ['nama' => 'Kalkulus 2', 'sks' => 3, 'nilai' => 'A'],
                ],
            ],
            [
                'semester' => 3,
                'tahun' => '2025/2026 Gasal',
                'sks' => 21,
                'ip' => 3.69,
                'mata_kuliah' => [
                    ['nama' => 'Pemrograman Web', 'sks' => 3, 'nilai' => 'A'],
                    ['nama' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'nilai' => 'A'],
                    ['nama' => 'Jaringan Komputer', 'sks' => 4, 'nilai' => 'B'],
                    ['nama' => 'Teori Graf', 'sks' => 3, 'nilai' => 'A'],
                    ['nama' => 'Matematika Diskrit', 'sks' => 3, 'nilai' => 'A'],
                    ['nama' => 'Konsep Pengembangan Perangkat Lunak', 'sks' => 2, 'nilai' => 'AB'],
                    ['nama' => 'Konsep Kecerdasan Artifisial', 'sks' => 3, 'nilai' => 'AB'],
                ],
            ],
            [
                'semester' => 4,
                'tahun' => '2025/2026 Genap',
                'sks' => 20,
                'ip' => 3.70,
                'mata_kuliah' => [
                    ['nama' => 'Pemrograman Jaringan', 'sks' => 3, 'nilai' => 'A'],
                    ['nama' => 'Probabilitas dan Statistik', 'sks' => 3, 'nilai' => 'AB'],
                    ['nama' => 'Otomata', 'sks' => 2, 'nilai' => 'A'],
                    ['nama' => 'Manajemen Basis Data', 'sks' => 3, 'nilai' => 'AB'],
                    ['nama' => 'Perancangan dan Analisis Algoritma', 'sks' => 3, 'nilai' => 'A'],
                    ['nama' => 'Pembelajaran Mesin', 'sks' => 3, 'nilai' => 'AB'],
                    ['nama' => 'Perancangan Perangkat Lunak', 'sks' => 3, 'nilai' => 'AB'],
                ],
            ],
            [
                'semester' => 5,
                'tahun' => '2026/2027 Gasal',
                'sks' => 21,
                'ip' => null,
                'mata_kuliah' => [
                    ['nama' => 'Pemrograman Berbasis Kerangka Kerja', 'sks' => 3, 'nilai' => null],
                    ['nama' => 'Keamanan Informasi', 'sks' => 3, 'nilai' => null],
                    ['nama' => 'Pemodelan dan Simulasi', 'sks' => 3, 'nilai' => null],
                    ['nama' => 'Grafika Komputer', 'sks' => 3, 'nilai' => null],
                    ['nama' => 'Rekayasa Sistem Berbasis Pengetahuan', 'sks' => 3, 'nilai' => null],
                    ['nama' => 'Teknik Pengembangan Game', 'sks' => 3, 'nilai' => null],
                    ['nama' => 'Game Cerdas', 'sks' => 3, 'nilai' => null],
                ],
            ],
        ];
    }

    /**
     * Ringkasan studi: IPK, total SKS, dan jumlah semester yang sudah selesai.
     * IPK = jumlah (IP x SKS) dibagi total SKS, hanya untuk semester yang sudah punya IP.
     *
     * @return array{ipk: float, totalSks: int, jumlahSemester: int}
     */
    public static function ringkasanStudi(): array
    {
        $totalSks = 0;
        $totalBobot = 0;
        $jumlahSemester = 0;

        foreach (self::riwayatStudi() as $semester) {
            if ($semester['ip'] === null) {
                continue;
            }

            $totalSks += $semester['sks'];
            $totalBobot += $semester['ip'] * $semester['sks'];
            $jumlahSemester++;
        }

        return [
            'ipk' => $totalSks > 0 ? $totalBobot / $totalSks : 0,
            'totalSks' => $totalSks,
            'jumlahSemester' => $jumlahSemester,
        ];
    }

    /**
     * Tema platform Agentic AI untuk rute /agent/{tema?}. Kunci array adalah slug di URL.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function temaAgent(): array
    {
        return [
            'tokenpilot' => [
                'nama' => 'TokenPilot',
                'tagline' => 'Mengatur konteks agar AI coding bekerja lebih terarah dan hemat token.',
                'deskripsi' => 'TokenPilot adalah aplikasi web untuk memberikan tugas coding, misalnya memperbaiki '
                    .'fitur login atau menambahkan fitur kecil. Di belakang layar ada agent yang memilih informasi '
                    .'yang relevan dari project, mengirim tugas ke AI coding, lalu memantau dan memeriksa hasilnya.',
                'masalah' => 'Saat memakai AI coding, konteks yang diberikan sering terlalu luas, berulang, '
                    .'atau justru kurang. Akibatnya AI harus membaca banyak file dan mencoba berkali-kali. '
                    .'TokenPilot mengatur konteks tersebut supaya pekerjaan lebih efisien.',
                // Komponen utama untuk diagram rancangan di /ide-agent, urut sesuai aliran tugas.
                'rancangan' => [
                    ['aktor' => 'Pengguna', 'peran' => 'Memberi tugas, hasil yang diharapkan, dan batasan'],
                    ['aktor' => 'Agent TokenPilot', 'peran' => 'Local LLM memilih konteks yang relevan dari project'],
                    ['aktor' => 'AI Coding', 'peran' => 'Codex / Claude mengubah kode di ruang kerja terpisah'],
                    ['aktor' => 'Test Runner', 'peran' => 'Menjalankan test; gagal berarti kembali ke agent (maks. 2x)'],
                    ['aktor' => 'Laporan', 'peran' => 'Diff kode, hasil test, jejak agent, dan pemakaian token'],
                ],
                'alur' => [
                    'Pengguna memberikan tugas, hasil yang diharapkan, dan batasannya.',
                    'Program memeriksa project dan memberi tahu agent apakah ada catatan dari tugas sebelumnya.',
                    'Agent memilih tindakan: membaca catatan lama, mencari file tambahan, atau langsung menjalankan AI coding.',
                    'Sistem menyarankan model dan tingkat usaha; pengguna boleh mengubahnya sebelum mulai.',
                    'AI coding mengubah kode di ruang kerja terpisah, lalu program menjalankan test.',
                    'Jika test gagal, agent menambah konteks dan mencoba lagi. Percobaan ulang dibatasi, misalnya dua kali.',
                    'Aplikasi menampilkan perubahan kode, hasil test, jejak tindakan agent, dan jumlah token yang dipakai.',
                ],
                'pembagian' => [
                    'Local LLM' => 'AI yang berjalan di komputer sendiri. Mengambil keputusan kecil tentang konteks yang perlu dipakai.',
                    'Codex / Claude' => 'AI coding yang menganalisis masalah sulit dan mengubah kode.',
                    'Program TokenPilot' => 'Menjalankan pencarian dan test, menghitung pemakaian token, membatasi percobaan.',
                ],
                'tech_stack' => [
                    'Laravel + Blade/Livewire' => 'Aplikasi web, formulir tugas, halaman progres, riwayat, dan API ke runner lokal.',
                    'PostgreSQL' => 'Menyimpan project, tugas, keputusan agent, hasil test, pemakaian token, dan catatan lama.',
                    'Python' => 'Runner lokal: pengatur alur agent, pencarian file, pemanggilan AI coding, dan test.',
                    'Ollama + local LLM' => 'Menjalankan AI lokal untuk keputusan sederhana.',
                    'Codex CLI' => 'AI coding utama untuk menganalisis dan mengubah kode.',
                    'Python MCP SDK' => 'Membuat TokenPilot MCP, penghubung antara agent dan tools.',
                    'Git' => 'Memisahkan ruang kerja dan meninjau perubahan kode sebelum diterima.',
                ],
                'target' => 'Pemakaian token lebih efisien dengan hasil tugas yang tetap benar. '
                    .'Penghematan harus dibuktikan lewat pengujian, bukan sekadar dijanjikan.',
            ],
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function cariTemaAgent(string $slug): ?array
    {
        return self::temaAgent()[strtolower($slug)] ?? null;
    }
}
