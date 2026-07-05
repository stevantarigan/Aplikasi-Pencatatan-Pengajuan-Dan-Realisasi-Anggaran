<?php
// app/Http/Controllers/MakananController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Makanan;
use Illuminate\Support\Facades\Validator;

class MakananController extends Controller
{
    /**
     * Menampilkan halaman pengaturan harga makanan
     */
    public function index()
    {
        $makanan = Makanan::first();

        if (!$makanan) {
            $makanan = Makanan::create([
                'nama' => 'Makanan Pasien',
                'harga' => 50000,
                'keterangan' => 'Harga makanan standar per pasien'
            ]);
        }

        return view('admin.makanan.index', compact('makanan'));
    }

    /**
     * Update harga makanan
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'harga' => 'required|numeric|integer|min:1000|max:1000000'
        ], [
            'harga.required' => 'Harga makanan tidak boleh kosong',
            'harga.numeric' => 'Harga makanan harus berupa angka',
            'harga.integer' => 'Harga makanan harus berupa bilangan bulat',
            'harga.min' => 'Harga makanan minimal Rp 1.000',
            'harga.max' => 'Harga makanan maksimal Rp 1.000.000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update harga
        $makanan = Makanan::updateHarga($request->harga);

        // Update keterangan jika ada
        if ($request->filled('keterangan')) {
            $makanan->keterangan = $request->keterangan;
            $makanan->save();
        }

        return redirect()->route('admin.makanan.index')
            ->with('success', 'Harga makanan berhasil diperbarui menjadi Rp ' . number_format($request->harga, 0, ',', '.') . '!');
    }
    public function getHarga()
    {
        $makanan = Makanan::first();

        if (!$makanan) {
            $makanan = Makanan::create([
                'nama' => 'Makanan Pasien',
                'harga' => 50000,
                'keterangan' => 'Harga makanan standar per pasien'
            ]);
        }

        return response()->json([
            'success' => true,
            'harga' => $makanan->harga,
            'harga_formatted' => $makanan->harga_formatted
        ]);
    }
}