<?php

namespace App\Http\Controllers;

use App\Models\DataPasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function daftarPasien(Request $request)
    {
        $query = DataPasien::query();

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $bulanMap = array_flip($this->getBulanList());
            $bulanAngka = $bulanMap[$request->bulan] ?? null;
            if ($bulanAngka) {
                $query->where('bulan', $bulanAngka);
            }
        }

        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tahun', 'like', "%{$search}%")
                    ->orWhere('jumlah_pasien', 'like', "%{$search}%");
            });
        }

        // 🔴 CLONE QUERY UNTUK STATISTIK (agar tidak mengganggu query asli)
        $statQuery = clone $query;

        // Statistik berdasarkan filter
        $totalData = $statQuery->count();
        $totalPasien = $statQuery->sum('jumlah_pasien');
        $bulanAktif = $statQuery->select('bulan', 'tahun')->distinct()->count();
        $rataRata = $totalData > 0 ? round($totalPasien / $totalData) : 0;

        // Data untuk filter tahun (semua tahun yang ada di database)
        $tahunList = DataPasien::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Data untuk filter bulan
        $bulanList = $this->getBulanList();

        // Data dengan paginasi (menggunakan query asli)
        $dataPasien = $query->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.daftarPasien', compact(
            'dataPasien',
            'totalData',
            'totalPasien',
            'bulanAktif',
            'rataRata',
            'tahunList',
            'bulanList'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pasien.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:' . date('Y'),
            'jumlah_pasien' => 'required|integer|min:0'
        ], [
            'bulan.required' => 'Bulan harus dipilih',
            'bulan.min' => 'Bulan tidak valid',
            'bulan.max' => 'Bulan tidak valid',
            'tahun.required' => 'Tahun harus diisi',
            'tahun.min' => 'Tahun minimal 2000',
            'tahun.max' => 'Tahun maksimal ' . date('Y'),
            'jumlah_pasien.required' => 'Jumlah pasien harus diisi',
            'jumlah_pasien.min' => 'Jumlah pasien tidak boleh negatif'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek duplikasi data
        $existing = DataPasien::where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->with('error', 'Data untuk periode ' . $this->getNamaBulan($request->bulan) . ' ' . $request->tahun . ' sudah ada')
                ->withInput();
        }

        DataPasien::create([
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'jumlah_pasien' => $request->jumlah_pasien
        ]);

        return redirect()->route('admin.pasien.index', ['tahun' => $request->tahun])
            ->with('success', 'Data pasien berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dataPasien = DataPasien::findOrFail($id);
        return view('admin.pasien.edit', compact('dataPasien'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dataPasien = DataPasien::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:' . date('Y'),
            'jumlah_pasien' => 'required|integer|min:0'
        ], [
            'bulan.required' => 'Bulan harus dipilih',
            'bulan.min' => 'Bulan tidak valid',
            'bulan.max' => 'Bulan tidak valid',
            'tahun.required' => 'Tahun harus diisi',
            'tahun.min' => 'Tahun minimal 2000',
            'tahun.max' => 'Tahun maksimal ' . date('Y'),
            'jumlah_pasien.required' => 'Jumlah pasien harus diisi',
            'jumlah_pasien.min' => 'Jumlah pasien tidak boleh negatif'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek duplikasi data kecuali data yang sedang diedit
        $existing = DataPasien::where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->where('id', '!=', $id)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->with('error', 'Data untuk periode ' . $this->getNamaBulan($request->bulan) . ' ' . $request->tahun . ' sudah ada')
                ->withInput();
        }

        $dataPasien->update([
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'jumlah_pasien' => $request->jumlah_pasien
        ]);

        return redirect()->route('admin.pasien.index', ['tahun' => $request->tahun])
            ->with('success', 'Data pasien berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dataPasien = DataPasien::findOrFail($id);
        $tahun = $dataPasien->tahun;
        $dataPasien->delete();

        return redirect()->route('admin.pasien.index', ['tahun' => $tahun])
            ->with('success', 'Data pasien berhasil dihapus');
    }

    /**
     * Get nama bulan in Indonesian
     */
    private function getNamaBulan($bulan)
    {
        $bulanList = $this->getBulanList();
        return $bulanList[$bulan] ?? 'Tidak Diketahui';
    }

    /**
     * Get bulan list
     */
    private function getBulanList()
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
    }
}