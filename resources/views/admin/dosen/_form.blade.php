{{-- Form bersama tambah dan ubah dosen. $dosen hanya ada saat mengubah. --}}
@csrf

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <x-input-label for="nip" value="NIP (18 digit)" />
        <x-text-input id="nip" name="nip" type="text" inputmode="numeric" maxlength="18" class="mt-1 block w-full"
            :value="old('nip', $dosen->nip ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('nip')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="nama" value="Nama" />
        <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full"
            :value="old('nama', $dosen->nama ?? '')" required />
        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" value="Email (opsional)" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
            :value="old('email', $dosen->email ?? '')" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="departemen_id" value="Departemen" />
        <select id="departemen_id" name="departemen_id" required
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">Pilih departemen</option>
            @foreach ($departemens as $departemen)
                <option value="{{ $departemen->id }}" @selected(old('departemen_id', $dosen->departemen_id ?? '') == $departemen->id)>
                    {{ $departemen->nama }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('departemen_id')" class="mt-2" />
    </div>
</div>
