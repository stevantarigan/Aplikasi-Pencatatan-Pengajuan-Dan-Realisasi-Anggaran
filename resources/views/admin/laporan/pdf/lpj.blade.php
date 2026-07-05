<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pertanggungjawaban (LPJ) - Instalasi Gizi RSUD Kabanjahe</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
            padding: 15mm 10mm;
            background: #fff;
        }

        /* KOP */
        .kop-surat {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 3px solid #0b4f6c;
            padding-bottom: 8px;
        }

        .kop-institusi {
            font-size: 20pt;
            font-weight: bold;
            color: #0b4f6c;
        }

        .kop-instalasi {
            font-size: 14pt;
            font-weight: 600;
            color: #2c3e50;
        }

        .kop-alamat {
            font-size: 8pt;
            color: #4a5568;
            margin-top: 3px;
        }

        .garis-kop {
            height: 2px;
            background: linear-gradient(to right, #0b4f6c, #2c7da0, #a9d6e5);
            margin-top: 5px;
        }

        /* JUDUL */
        .judul-laporan {
            text-align: center;
            margin: 15px 0;
        }

        .judul-utama {
            font-size: 16pt;
            font-weight: bold;
            color: #0b4f6c;
            text-transform: uppercase;
        }

        .judul-sub {
            font-size: 11pt;
            color: #2c3e50;
        }

        .tanggal-cetak {
            font-size: 8pt;
            color: #666;
        }

        /* FILTER */
        .info-filter {
            margin-bottom: 15px;
            padding: 8px 12px;
            background: #f8f9fa;
            border-left: 4px solid #0b4f6c;
            font-size: 9pt;
        }

        .info-filter table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-filter td {
            padding: 3px 6px;
        }

        .label {
            font-weight: 600;
        }

        /* STATISTIK */
        .statistik-row {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .statistik-box {
            flex: 1;
            background: #f8f9fa;
            border: 1px solid #dde7f0;
            text-align: center;
            padding: 10px 5px;
        }

        .statistik-label {
            font-size: 8pt;
            text-transform: uppercase;
            color: #666;
        }

        .statistik-nilai {
            font-size: 12pt;
            font-weight: bold;
            color: #0b4f6c;
        }

        /* CARD */
        .anggaran-card {
            margin-bottom: 25px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            page-break-inside: avoid;
        }

        .anggaran-header {
            background: #0b4f6c;
            color: #fff;
            padding: 8px 12px;
            font-weight: bold;
        }

        .anggaran-body {
            padding: 10px;
        }

        /* TABLE INFO */
        .info-anggaran {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9.5pt;
        }

        .info-anggaran td {
            border: 1px solid #e2e8f0;
            padding: 5px;
        }

        .info-anggaran .label {
            background: #f8fafc;
            font-weight: 600;
            width: 130px;
        }

        .info-anggaran .nilai {
            font-weight: 600;
            color: #0b4f6c;
        }

        /* TABLE REALISASI */
        .table-realisasi {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-top: 10px;
        }

        .table-realisasi th {
            background: #2c7da0;
            color: #fff;
            padding: 6px;
            border: 1px solid #1a5f7a;
        }

        .table-realisasi td {
            border: 1px solid #cbd5e1;
            padding: 5px;
        }

        /* SISA */
        .sisa-anggaran {
            margin-top: 10px;
            padding: 8px 12px;
            background: #f0f7fa;
            border-left: 4px solid #0b4f6c;
            display: flex;
            justify-content: space-between;
            font-size: 9.5pt;
        }

        .sisa-nilai.positif {
            color: #059669;
            font-weight: 700;
        }

        .sisa-nilai.negatif {
            color: #dc2626;
            font-weight: 700;
        }

        /* TANDA TANGAN */
        .ttd-container {
            display: flex;
            justify-content: space-between;
            margin: 40px 0 20px 0;
        }

        .ttd-box {
            width: 45%;
            text-align: center;
        }

        .ttd-garis {
            margin-top: 50px;
            border-top: 1px solid #000;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .ttd-nama {
            margin-top: 5px;
            font-weight: bold;
        }

        .ttd-jabatan {
            font-size: 8pt;
            margin-bottom: 30px;
        }

        /* ALIGN */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* FOOTER */
        .footer {
            margin-top: 15px;
            border-top: 1px solid #cbd5e1;
            padding-top: 6px;
            display: flex;
            justify-content: space-between;
            font-size: 7pt;
            color: #666;
        }

        /* WATERMARK */
        .watermark {
            position: fixed;
            bottom: 60px;
            right: 60px;
            font-size: 55px;
            opacity: 0.03;
            transform: rotate(-15deg);
            font-weight: bold;
            color: #0b4f6c;
        }
    </style>
</head>

<body>
    <div class="watermark">RSUD KABANJAHE</div>

    <!-- KOP -->
    <div class="kop-surat">
        <div class="kop-institusi">INSTALASI GIZI</div>
        <div class="kop-instalasi">RSUD KABANJAHE</div>
        <div class="kop-alamat">Jl. Kapten Selamat Ketaren No. 1, Kabanjahe</div>
        <div class="garis-kop"></div>
    </div>

    <!-- JUDUL -->
    <div class="judul-laporan">
        <div class="judul-utama">LAPORAN PERTANGGUNGJAWABAN (LPJ)</div>
        <div class="judul-sub">Realisasi Anggaran Final per Bulan</div>
        <div class="tanggal-cetak">Dicetak: {{ now()->format('d F Y H:i:s') }}</div>
    </div>

    <!-- FILTER -->
    <div class="info-filter">
        <table>
            <tr>
                <td class="label">Tahun</td>
                <td>: {{ $filters['tahun'] ?? '-' }}</td>
                <td class="label">Total Anggaran (Final)</td>
                <td>: Rp {{ number_format($statistik['totalAnggaran'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Rekening</td>
                <td>
                    : @php
                        $rekening =
                            isset($filters['kode_rekening_id']) && isset($kodeRekeningList)
                                ? $kodeRekeningList->firstWhere('id', $filters['kode_rekening_id'])
                                : null;
                    @endphp
                    {{ $rekening->kode ?? 'Semua' }} - {{ $rekening->nama_rekening ?? '' }}
                </td>
                <td class="label">Realisasi</td>
                <td>: Rp {{ number_format($statistik['totalRealisasi'], 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- STATISTIK -->
    <div class="statistik-row">
        <div class="statistik-box">
            <div class="statistik-label">Rencana</div>
            <div class="statistik-nilai">{{ $statistik['totalRencana'] }}</div>
        </div>
        <div class="statistik-box">
            <div class="statistik-label">Pengajuan</div>
            <div class="statistik-nilai">{{ $statistik['totalPengajuan'] }}</div>
        </div>
        <div class="statistik-box">
            <div class="statistik-label">Persentase</div>
            <div class="statistik-nilai">{{ $statistik['persentaseRealisasi'] }}%</div>
        </div>
    </div>

    <!-- DETAIL -->
    @foreach ($rencana as $index => $item)
        @php
            // === PERBAIKAN: Ambil anggaran final dari tabel Anggaran ===
            $anggaranFinal = $item->anggaran->total_anggaran ?? ($item->total_anggaran ?? 0);

            $totalPengajuan = 0;
            $totalRealisasi = 0;
            $realisasiPerBulan = [];

            // Ambil pengajuan unik (hindari duplikasi) dan null-safe
            $pengajuanList = optional($item->anggaran)->pengajuan->unique('id') ?? collect();

            foreach ($pengajuanList as $pengajuan) {
                $totalPengajuan += $pengajuan->jumlah_diajukan ?? 0;

                if ($pengajuan->status === 'direalisasi') {
                    $realisasi = $pengajuan->jumlah_realisasi ?? 0;
                    $totalRealisasi += $realisasi;

                    $bulan = (int) $pengajuan->bulan;

                    if (!isset($realisasiPerBulan[$bulan])) {
                        $realisasiPerBulan[$bulan] = [
                            'bulan' => $bulan,
                            'nama_bulan' => $statistik['namaBulan'][$bulan] ?? 'Bulan ' . $bulan,
                            'jumlah_diajukan' => 0,
                            'jumlah_realisasi' => 0,
                            'sisa_pengajuan' => 0,
                        ];
                    }

                    $realisasiPerBulan[$bulan]['jumlah_diajukan'] += $pengajuan->jumlah_diajukan ?? 0;
                    $realisasiPerBulan[$bulan]['jumlah_realisasi'] += $realisasi;
                }
            }

            // Hitung sisa pengajuan per bulan
            foreach ($realisasiPerBulan as $bulan => $data) {
                $realisasiPerBulan[$bulan]['sisa_pengajuan'] = $data['jumlah_diajukan'] - $data['jumlah_realisasi'];
            }

            // === PERBAIKAN: Sisa Anggaran Final (Anggaran Final - Realisasi) ===
            $sisaAnggaranTotal = $anggaranFinal - $totalRealisasi;
        @endphp

        <div class="anggaran-card">
            <div class="anggaran-header">
                ANGGARAN {{ $index + 1 }}: {{ $item->nama_rencana }}
            </div>

            <div class="anggaran-body">
                <table class="info-anggaran">
                    <tr>
                        <td class="label">Kode Rekening</td>
                        <td>{{ optional($item->kodeRekening)->kode ?? '-' }} -
                            {{ optional($item->kodeRekening)->nama_rekening ?? '-' }}</td>
                        <td class="label">Tahun</td>
                        <td>{{ $item->tahun }}</td>
                    </tr>
                    <tr>
                        <td class="label">Total Anggaran (Final)</td>
                        <td class="nilai">Rp {{ number_format($anggaranFinal, 0, ',', '.') }}</td>
                        <td class="label">Status Anggaran</td>
                        <td>{{ optional($item->anggaran)->status ?? '-' }}</td>
                    </tr>
                </table>

                <table class="table-realisasi">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bulan</th>
                            <th>Diajukan (Rp)</th>
                            <th>Realisasi (Rp)</th>
                            <th>Sisa Pengajuan (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($realisasiPerBulan as $data)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $data['nama_bulan'] }}</td>
                                <td class="text-right">Rp {{ number_format($data['jumlah_diajukan'], 0, ',', '.') }}
                                </td>
                                <td class="text-right">Rp {{ number_format($data['jumlah_realisasi'], 0, ',', '.') }}
                                </td>
                                <td class="text-right">Rp {{ number_format($data['sisa_pengajuan'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data realisasi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="sisa-anggaran">
                    <span>Sisa Anggaran Final (Anggaran - Realisasi)</span>
                    <span class="sisa-nilai {{ $sisaAnggaranTotal >= 0 ? 'positif' : 'negatif' }}">
                        Rp {{ number_format(abs($sisaAnggaranTotal), 0, ',', '.') }}
                        ({{ $sisaAnggaranTotal >= 0 ? 'Sisa' : 'Defisit' }})
                    </span>
                </div>

            </div>
        </div>
    @endforeach

    <!-- TANDA TANGAN -->
    <div class="ttd-container">
        <div class="ttd-box">
            <div class="ttd-tempat">Kabanjahe, {{ now()->format('d F Y') }}</div>
            <div class="ttd-jabatan">Kepala Instalasi Gizi</div>
            <div class="ttd-garis"></div>
            <div class="ttd-nama">( _____________________ )</div>
            <div class="ttd-nip">NIP. ____________________</div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <div>Dokumen otomatis sistem</div>
        <div>{{ auth()->user()->nama ?? 'System' }}</div>
    </div>

</body>

</html>
