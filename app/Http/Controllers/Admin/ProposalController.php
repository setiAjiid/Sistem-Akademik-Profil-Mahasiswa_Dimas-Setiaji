<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Area dosen dan asdos: meninjau proposal dan memberi nilai.
 * Rutenya dilindungi 'auth' dan 'cek.peran:dosen,asdos' di routes/web.php.
 */
class ProposalController extends Controller
{
    public function index(): View
    {
        return view('admin.proposal.index', [
            'proposals' => Proposal::with('pembuat', 'penilai')->latest()->get(),
        ]);
    }

    public function show(Proposal $proposal): View
    {
        $proposal->load('pembuat', 'penilai');

        return view('admin.proposal.show', compact('proposal'));
    }

    /** Simpan nilai dan catatan. Gate dicek lagi di sini supaya tidak bisa ditembus lewat request manual. */
    public function nilai(Request $request, Proposal $proposal): RedirectResponse
    {
        Gate::authorize('input-nilai');

        $data = $request->validate([
            'nilai' => ['required', 'integer', 'between:0,100'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'required' => ':Attribute wajib diisi.',
            'integer' => ':Attribute harus berupa angka bulat.',
            'between' => ':Attribute harus antara :min sampai :max.',
            'max' => ':Attribute maksimal :max karakter.',
        ]);

        $proposal->update($data + ['dinilai_oleh' => $request->user()->id]);

        return redirect()
            ->route('admin.proposal.show', $proposal)
            ->with('status', 'Nilai berhasil disimpan.');
    }
}
