{{-- Form bersama tambah dan ubah mahasiswa. $mahasiswa hanya ada saat mengubah. --}}
@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label for="nrp" class="form-label">NRP (10 digit)</label>
        <input id="nrp" name="nrp" type="text" inputmode="numeric" maxlength="10" required autofocus
            class="form-control @error('nrp') is-invalid @enderror" value="{{ old('nrp', $mahasiswa->nrp ?? '') }}">
        @error('nrp') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="nama" class="form-label">Nama</label>
        <input id="nama" name="nama" type="text" required
            class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $mahasiswa->nama ?? '') }}">
        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="departemen_id" class="form-label">Departemen</label>
        <select id="departemen_id" name="departemen_id" required class="form-select @error('departemen_id') is-invalid @enderror">
            <option value="">Pilih departemen</option>
            @foreach ($departemens as $departemen)
                <option value="{{ $departemen->id }}" @selected(old('departemen_id', $mahasiswa->departemen_id ?? '') == $departemen->id)>{{ $departemen->nama }}</option>
            @endforeach
        </select>
        @error('departemen_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label for="angkatan" class="form-label">Angkatan</label>
        <input id="angkatan" name="angkatan" type="number" required
            class="form-control @error('angkatan') is-invalid @enderror" value="{{ old('angkatan', $mahasiswa->angkatan ?? now()->year) }}">
        @error('angkatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label for="ipk" class="form-label">IPK (opsional)</label>
        <input id="ipk" name="ipk" type="number" step="0.01" min="0" max="4"
            class="form-control @error('ipk') is-invalid @enderror" value="{{ old('ipk', $mahasiswa->ipk ?? '') }}">
        @error('ipk') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label for="foto" class="form-label">Foto (opsional, JPG atau PNG, maksimal 2 MB)</label>
        @if (isset($mahasiswa) && $mahasiswa->urlFoto())
            <div class="mb-2"><img src="{{ $mahasiswa->urlFoto() }}" alt="Foto {{ $mahasiswa->nama }}" class="rounded" width="80" height="80" style="object-fit: cover"></div>
        @endif
        <input id="foto" name="foto" type="file" accept="image/png,image/jpeg" class="form-control @error('foto') is-invalid @enderror">
        @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <div class="form-check">
            <input id="is_active" class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $mahasiswa->is_active ?? true))>
            <label class="form-check-label" for="is_active">Mahasiswa aktif (tidak cuti)</label>
        </div>
    </div>
</div>
