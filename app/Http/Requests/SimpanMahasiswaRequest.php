<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi form tambah dan ubah mahasiswa.
 */
class SimpanMahasiswaRequest extends FormRequest
{
    /** Akses sudah dijaga middleware cek.peran di rute. */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            // Saat ubah data, NRP milik mahasiswa itu sendiri tidak dianggap duplikat.
            'nrp' => ['required', 'digits:10', Rule::unique('mahasiswas', 'nrp')->ignore($this->route('mahasiswa'))],
            'nama' => ['required', 'string', 'max:100'],
            'departemen_id' => ['required', 'exists:departemens,id'],
            'angkatan' => ['required', 'integer', 'between:2000,'.now()->year],
            'ipk' => ['nullable', 'numeric', 'between:0,4'],
            'is_active' => ['boolean'],
        ];
    }

    /** Checkbox yang tidak dicentang tidak ikut terkirim, jadi dianggap false. */
    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active')]);
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => ':Attribute wajib diisi.',
            'digits' => ':Attribute harus berupa :digits digit angka.',
            'unique' => ':Attribute sudah terdaftar.',
            'exists' => ':Attribute yang dipilih tidak valid.',
            'between' => ':Attribute harus antara :min sampai :max.',
            'max' => ':Attribute maksimal :max karakter.',
            'numeric' => ':Attribute harus berupa angka.',
            'integer' => ':Attribute harus berupa angka bulat.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nrp' => 'NRP',
            'nama' => 'nama',
            'departemen_id' => 'departemen',
            'angkatan' => 'angkatan',
            'ipk' => 'IPK',
        ];
    }
}
