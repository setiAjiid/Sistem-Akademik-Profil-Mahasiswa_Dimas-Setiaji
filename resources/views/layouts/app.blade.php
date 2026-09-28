<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ config('app.name') }}</title>
    {{-- Font dan CSS/JS diproses Vite dari resources/, tidak ada tautan CDN. --}}
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0b1120] text-slate-100">
    <header>
        <div class="isi">
            <a class="brand" href="{{ route('home') }}">
                <span class="logo">DS</span>
                Dimas Setiaji
            </a>
            <nav>
                {{-- request()->routeIs() mengecek nama rute yang sedang aktif --}}
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'aktif' : '' }}">Home</a>
                <a href="{{ route('mahasiswa.show', ['nrp' => $nrpPemilik]) }}" class="{{ request()->routeIs('mahasiswa.*') ? 'aktif' : '' }}">Profil</a>
                <a href="{{ route('agent.show', ['tema' => 'tokenpilot']) }}" class="{{ request()->routeIs('agent.*') ? 'aktif' : '' }}">Agentic AI</a>
                <a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('dashboard.*') || request()->routeIs('ipk.*') ? 'aktif' : '' }}">Dashboard</a>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <span>Tugas Mandiri PBKK Pertemuan 2</span>
        <span>Dimas Setiaji (5025241056), Teknik Informatika ITS</span>
    </footer>
</body>
</html>
