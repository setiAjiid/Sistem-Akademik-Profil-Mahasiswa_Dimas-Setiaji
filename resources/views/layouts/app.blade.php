<!DOCTYPE html>
{{-- Challenge 1: $modeGelap dikirim View Composer (AppServiceProvider) dari parameter ?mode=dark. --}}
<html lang="id" @class(['dark' => $modeGelap])>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ config('app.name') }}</title>
    {{-- Font dan CSS/JS diproses Vite dari resources/, tidak ada tautan CDN. --}}
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body @class([
    'min-h-screen transition-colors duration-300',
    'bg-[#0b1120] text-slate-100' => $modeGelap,
    'bg-slate-50 text-slate-900' => ! $modeGelap,
])>
    <header>
        <div class="isi">
            <a class="brand" href="{{ route('home', $kueriMode) }}">
                <span class="logo">DS</span>
                Dimas Setiaji
            </a>
            <nav>
                {{-- request()->routeIs() mengecek nama rute yang sedang aktif. $kueriMode menjaga ?mode=dark saat pindah halaman. --}}
                <a href="{{ route('home', $kueriMode) }}" class="{{ request()->routeIs('home', 'beranda') ? 'aktif' : '' }}">Beranda</a>
                <a href="{{ route('profil', $kueriMode) }}" class="{{ request()->routeIs('profil', 'mahasiswa.*') ? 'aktif' : '' }}">Profil</a>
                <a href="{{ route('ide-agent', $kueriMode) }}" class="{{ request()->routeIs('ide-agent*', 'agent.*') ? 'aktif' : '' }}">Ide Agent</a>
                <a href="{{ route('dashboard.index', $kueriMode) }}" class="{{ request()->routeIs('dashboard.*', 'ipk.*') ? 'aktif' : '' }}">Dashboard</a>
                <a href="{{ $urlGantiMode }}" class="tombol-mode" title="Ganti tema">
                    {{ $modeGelap ? 'Mode terang' : 'Mode gelap' }}
                </a>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <span>Tugas Mandiri PBKK Pertemuan 4, Institut Teknologi Sepuluh Nopember</span>
        <span>Dimas Setiaji (5025241056), Departemen Teknik Informatika ITS</span>
    </footer>
</body>
</html>
