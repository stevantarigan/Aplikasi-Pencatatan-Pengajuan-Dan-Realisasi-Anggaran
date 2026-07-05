<?php

namespace App\Http\Controllers;

use App\Models\KodeRekening;
use Illuminate\Http\Request;

class KodeRekeningController extends Controller
{
    /**
     * Display a listing of the COA (Chart of Account).
     */
    public function index(Request $request)
    {
        $query = KodeRekening::query();

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('kode', 'LIKE', $searchTerm)
                    ->orWhere('nama_rekening', 'LIKE', $searchTerm);
            });
        }

        // Apply type filter
        if ($request->filled('tipe') && in_array($request->tipe, ['utama', 'turunan'])) {
            $query->where('tipe', $request->tipe);
        }

        // Order by kode and paginate
        $rekening = $query->orderBy('kode')
            ->paginate(10);

        $rekening->appends(request()->all());
        return view('admin.daftarCOA', compact('rekening'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.coa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:kode_rekening,kode',
            'nama_rekening' => 'required|string|max:255',
            'tipe' => 'required|in:utama,turunan',
        ]);

        KodeRekening::create([
            'kode' => $request->kode,
            'nama_rekening' => $request->nama_rekening,
            'tipe' => $request->tipe,
        ]);

        // PERBAIKAN: Ganti redirect ke admin.daftarCOA
        return redirect()->route('admin.daftarCOA')
            ->with('success', 'COA berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KodeRekening $coa)
    {
        return view('admin.coa.show', compact('coa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KodeRekening $coa)
    {
        return view('admin.coa.edit', compact('coa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KodeRekening $coa)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:kode_rekening,kode,' . $coa->id,
            'nama_rekening' => 'required|string|max:255',
            'tipe' => 'required|in:utama,turunan',
        ]);

        $coa->update([
            'kode' => $request->kode,
            'nama_rekening' => $request->nama_rekening,
            'tipe' => $request->tipe,
        ]);

        // PERBAIKAN: Ganti redirect ke admin.daftarCOA
        return redirect()->route('admin.daftarCOA')
            ->with('success', 'COA berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KodeRekening $coa)
    {
        $coa->delete();

        return response()->json([
            'success' => true,
            'message' => 'COA berhasil dihapus.'
        ]);
    }

    /**
     * Get COA data for API (for select2, etc.)
     */
    public function getData(Request $request)
    {
        $search = $request->get('search', '');
        $tipe = $request->get('tipe', '');

        $query = KodeRekening::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'LIKE', "%{$search}%")
                    ->orWhere('nama_rekening', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($tipe)) {
            $query->where('tipe', $tipe);
        }

        $data = $query->orderBy('kode')
            ->limit(50)
            ->get(['id', 'kode', 'nama_rekening', 'tipe']);

        return response()->json($data);
    }
}