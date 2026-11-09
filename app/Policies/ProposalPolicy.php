<?php

namespace App\Policies;

use App\Models\Proposal;
use App\Models\User;

/**
 * Aturan siapa boleh berbuat apa pada Proposal.
 * Laravel otomatis menemukan policy ini karena namanya ProposalPolicy untuk model Proposal.
 */
class ProposalPolicy
{
    /** Hanya mahasiswa yang membuat proposal. */
    public function create(User $user): bool
    {
        return $user->punyaPeran(User::ROLE_MAHASISWA);
    }

    /** Hanya mahasiswa pembuat proposal yang boleh menyunting judul dan isinya. */
    public function update(User $user, Proposal $proposal): bool
    {
        return $user->punyaPeran(User::ROLE_MAHASISWA)
            && $user->id === $proposal->user_id;
    }
}
