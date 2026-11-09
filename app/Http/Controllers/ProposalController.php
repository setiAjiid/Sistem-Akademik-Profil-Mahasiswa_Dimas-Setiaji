<?php

namespace App\Http\Controllers;

use App\Http\Requests\SimpanProposalRequest;
use App\Models\Proposal;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Area mahasiswa: mengisi dan menyunting rencana proyek kelompok AI.
 * Rutenya dilindungi 'auth' dan 'cek.peran:mahasiswa' di routes/web.php.
 */
class ProposalController extends Controller
{
    /** Daftar proposal milik mahasiswa yang sedang login. */
    public function index(Request $request): View
    {
        return view('proposal.index', [
            'proposals' => $request->user()->proposals()->latest()->get(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Proposal::class);

        return view('proposal.create');
    }

    public function store(SimpanProposalRequest $request): RedirectResponse
    {
        $this->authorize('create', Proposal::class);

        $request->user()->proposals()->create($request->validated());

        return redirect()
            ->route('proposal.index')
            ->with('status', 'Proposal berhasil dikirim.');
    }

    public function edit(Proposal $proposal): View
    {
        // Policy: hanya pembuat proposal yang boleh menyunting. Selain itu dijawab 403.
        $this->authorize('update', $proposal);

        return view('proposal.edit', compact('proposal'));
    }

    public function update(SimpanProposalRequest $request, Proposal $proposal): RedirectResponse
    {
        $this->authorize('update', $proposal);

        $proposal->update($request->validated());

        return redirect()
            ->route('proposal.index')
            ->with('status', 'Proposal berhasil diperbarui.');
    }
}
