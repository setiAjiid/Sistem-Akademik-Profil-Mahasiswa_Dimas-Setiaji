<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi form tambah dan ubah dosen.
 */
class SimpanDosenRequest extends FormRequest
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
            'nip' => ['required', 'digits:18', Rule::unique('dosens', 'nip')->ignore($this->route('dosen'))],
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'departemen_id' => ['required', 'exists:departemens,id'],
        ];
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
            'email' => 'Format email tidak valid.',
            'max' => ':Attribute maksimal :max karakter.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nip' => 'NIP',
            'nama' => 'nama',
            'departemen_id' => 'departemen',
        ];
    }
}
