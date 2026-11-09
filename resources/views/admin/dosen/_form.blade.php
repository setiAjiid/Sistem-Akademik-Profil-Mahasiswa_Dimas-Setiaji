{{-- Form bersama tambah dan ubah dosen. $dosen hanya ada saat mengubah. --}}
@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label for="nip" class="form-label">NIP (18 digit)</label>
        <input id="nip" name="nip" type="text" inputmode="numeric" maxlength="18" required autofocus
            class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $dosen->nip ?? '') }}">
        @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="nama" class="form-label">Nama</label>
        <input id="nama" name="nama" type="text" required
            class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $dosen->nama ?? '') }}">
        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">Email (opsional)</label>
        <input id="email" name="email" type="email"
            class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $dosen->email ?? '') }}">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="departemen_id" class="form-label">Departemen</label>
        <select id="departemen_id" name="departemen_id" required class="form-select @error('departemen_id') is-invalid @enderror">
            <option value="">Pilih departemen</option>
            @foreach ($departemens as $departemen)
                <option value="{{ $departemen->id }}" @selected(old('departemen_id', $dosen->departemen_id ?? '') == $departemen->id)>{{ $departemen->nama }}</option>
            @endforeach
        </select>
        @error('departemen_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
