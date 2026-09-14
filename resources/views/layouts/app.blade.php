<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
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
