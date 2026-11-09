<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MahasiswaResource;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * API JSON read-only untuk data mahasiswa.
 */
class MahasiswaController extends Controller
{
    /**
     * GET /api/mahasiswa
     * Parameter opsional: ?departemen_id=1 dan ?per_page=20 (maksimal 50).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min(max($request->integer('per_page', 10), 1), 50);

        $mahasiswas = Mahasiswa::with('departemen')
            ->when($request->integer('departemen_id'), fn ($query, $id) => $query->where('departemen_id', $id))
            ->orderBy('nama')
            ->paginate($perPage)
            ->withQueryString();

        return MahasiswaResource::collection($mahasiswas);
    }

    /** GET /api/mahasiswa/{mahasiswa} */
    public function show(Mahasiswa $mahasiswa): MahasiswaResource
    {
        return new MahasiswaResource($mahasiswa->load('departemen'));
    }
}
