<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi form proposal, dipakai saat membuat maupun menyunting.
 */
class SimpanProposalRequest extends FormRequest
{
    /** Izin sebenarnya dicek di controller lewat Policy. */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:120'],
            'deskripsi' => ['required', 'string', 'min:20', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => ':Attribute wajib diisi.',
            'min' => ':Attribute minimal :min karakter.',
            'max' => ':Attribute maksimal :max karakter.',
        ];
    }
}
