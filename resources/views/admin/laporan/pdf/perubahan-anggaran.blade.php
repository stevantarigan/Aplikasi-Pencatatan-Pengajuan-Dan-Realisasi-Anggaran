{{-- resources/views/admin/laporan/pdf/perubahan-anggaran.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Perubahan Anggaran - Instalasi Gizi RSUD Kabanjahe</title>
    <style>
        /* RESET & BASE */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000000;
            margin: 0;
            padding: 20mm 15mm;
            background: #ffffff;
        }

        /* KOP SURAT FORMAL */
        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #0b4f6c;
            padding-bottom: 10px;
        }

        .kop-institusi {
            font-size: 22pt;
            font-weight: bold;
            color: #0b4f6c;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        .kop-instalasi {
            font-size: 16pt;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 2px;
        }

        .kop-alamat {
            font-size: 9pt;
            color: #4a5568;
            margin-top: 3px;
        }

        .garis-kop {
            height: 2px;
            background: linear-gradient(to right, #0b4f6c, #2c7da0, #a9d6e5);
            margin-top: 8px;
        }

        /* JUDUL LAPORAN */
        .judul-laporan {
            text-align: center;
            margin: 25px 0 20px 0;
        }

        .judul-utama {
            font-size: 16pt;
            font-weight: bold;
            color: #0b4f6c;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .judul-sub {
            font-size: 12pt;
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .tanggal-cetak {
            font-size: 9pt;
            color: #4a5568;
            font-style: italic;
        }

        /* INFO FILTER - SEDERHANA */
        .info-filter {
            margin-bottom: 20px;
            padding: 8px 12px;
            background-color: #f8f9fa;
            border-left: 4px solid #0b4f6c;
            font-size: 9.5pt;
        }

        .info-filter table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-filter td {
            padding: 3px 8px;
        }

        .info-filter .label {
            font-weight: 600;
            width: 100px;
        }

        /* STATISTIK SEDERHANA */
        .statistik-row {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
        }

        .statistik-box {
            flex: 1;
            padding: 10px 8px;
            background-color: #f8f9fa;
            border: 1px solid #dde7f0;
            text-align: center;
        }

        .statistik-label {
            font-size: 8.5pt;
            color: #4a5568;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .statistik-nilai {
            font-size: 12pt;
            font-weight: bold;
            color: #0b4f6c;
        }

        .statistik-nilai.positif {
            color: #0b6e4f;
        }

        .statistik-nilai.negatif {
            color: #a61b2b;
        }

        /* TABEL UTAMA */
        .table-container {
            margin-bottom: 25px;
            border: 1px solid #d0ddee;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }

        th {
            background-color: #0b4f6c;
            color: white;
            font-weight: 600;
            padding: 8px 4px;
            text-align: center;
            border: 1px solid #1a5f7a;
            white-space: nowrap;
        }

        .sub-header th {
            background-color: #2c7da0;
            font-weight: 500;
            padding: 6px 4px;
        }

        td {
            padding: 6px 4px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        tfoot tr {
            background-color: #e9ecef;
            font-weight: 600;
        }

        /* ALIGNMENT */
        .text-left {
            text-align: left;
            padding-left: 6px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
            padding-right: 6px;
        }

        .bold {
            font-weight: 600;
        }

        /* BADGE PERUBAHAN */
        .badge-change {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8pt;
            font-weight: 600;
            min-width: 70px;
            text-align: center;
        }

        .badge-naik {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .badge-turun {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .badge-tetap {
            background-color: #e2e3e5;
            color: #383d41;
            border: 1px solid #d6d8db;
        }

        /* PROGRESS MINI */
        .progress-mini {
            width: 60px;
            height: 4px;
            background-color: #e9ecef;
            margin: 3px auto;
        }

        .progress-bar {
            height: 100%;
        }

        .bg-naik {
            background-color: #28a745;
        }

        .bg-turun {
            background-color: #dc3545;
        }

        /* KETERANGAN - TIDAK TEKNIS */
        .keterangan {
            margin: 15px 0 20px 0;
            padding: 12px 15px;
            background-color: #f0f7fa;
            border: 1px solid #b8d9e6;
            font-size: 9.5pt;
            border-radius: 4px;
        }

        .keterangan p {
            font-weight: 600;
            margin-bottom: 8px;
            color: #0b4f6c;
            font-size: 10pt;
        }

        .keterangan ul {
            list-style: none;
            padding-left: 5px;
            margin: 0;
        }

        .keterangan li {
            margin-bottom: 5px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .keterangan li:before {
            content: "✓";
            color: #0b4f6c;
            font-weight: bold;
            margin-right: 5px;
        }

        /* TANDA TANGAN */
        .ttd-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 35px;
            margin-bottom: 15px;
        }

        .ttd-box {
            text-align: center;
            width: 250px;
        }

        .ttd-tempat {
            margin-bottom: 5px;
            font-size: 9.5pt;
        }

        .ttd-jabatan {
            margin-bottom: 40px;
            font-weight: 500;
        }

        .ttd-garis {
            border-bottom: 1px solid #000;
            margin: 5px 0;
            width: 100%;
            height: 1px;
        }

        .ttd-nama {
            font-weight: 600;
            margin-top: 5px;
            font-size: 10pt;
        }

        .ttd-nip {
            font-size: 9pt;
            color: #4a5568;
        }

        /* FOOTER */
        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #cbd5e1;
            display: flex;
            justify-content: space-between;
            font-size: 8pt;
            color: #4a5568;
        }

        /* WATERMARK */
        .watermark {
            position: fixed;
            bottom: 80px;
            right: 80px;
            font-size: 65px;
            color: #0b4f6c;
            opacity: 0.03;
            transform: rotate(-15deg);
            z-index: -1;
            font-weight: bold;
            text-transform: uppercase;
        }

        @media print {
            body {
                padding: 15mm 10mm;
            }

            .watermark {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="watermark">RSUD KABANJAHE</div>

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <div class="kop-institusi">INSTALASI GIZI</div>
        <div class="kop-instalasi">RSUD KABANJAHE</div>
        <div class="kop-alamat">Jalan Kapten Selamat Ketaren No. 1, Kabanjahe 22151, Sumatera Utara</div>
        <div class="garis-kop"></div>
    </div>

    <!-- JUDUL LAPORAN -->
    <div class="judul-laporan">
        <div class="judul-utama">LAPORAN PERUBAHAN ANGGARAN</div>
        <div class="judul-sub">Perbandingan Rencana Awal dan Anggaran yang Disetujui</div>
        <div class="tanggal-cetak">Dicetak: {{ now()->format('d F Y H:i') }}</div>
    </div>

    <!-- INFO FILTER -->
    <div class="info-filter">
        <table>
            <tr>
                <td class="label">Tahun</td>
                <td>: {{ $filters['tahun'] ?? 'Semua Tahun' }}</td>
                <td class="label">Jumlah Data</td>
                <td>: {{ $statistik['totalRencana'] }} Rencana</td>
            </tr>
            <tr>
                <td class="label">Kode Rekening</td>
                <td>:
                    @if (!empty($filters['kode_rekening_id']) && isset($kodeRekeningList))
                        @php
                            $rekening = $kodeRekeningList->firstWhere('id', $filters['kode_rekening_id']);
                        @endphp
                        {{ $rekening->kode ?? '' }} - {{ $rekening->nama_rekening ?? '' }}
                    @else
                        Semua Rekening
                    @endif
                </td>
                <td class="label">Status</td>
                <td>: {{ ucfirst($filters['status_anggaran'] ?? 'Semua') }}</td>
            </tr>
        </table>
    </div>

    <!-- STATISTIK RINGKAS -->
    <div class="statistik-row">
        <div class="statistik-box">
            <div class="statistik-label">RENCANA AWAL</div>
            <div class="statistik-nilai">Rp {{ number_format($statistik['totalSebelum'], 0, ',', '.') }}</div>
        </div>
        <div class="statistik-box">
            <div class="statistik-label">ANGGARAN DISETUJUI</div>
            <div class="statistik-nilai">Rp {{ number_format($statistik['totalSesudah'], 0, ',', '.') }}</div>
        </div>
        <div class="statistik-box">
            <div class="statistik-label">SELISIH</div>
            <div class="statistik-nilai {{ $statistik['totalPerubahan'] >= 0 ? 'positif' : 'negatif' }}">
                {{ $statistik['totalPerubahan'] >= 0 ? '+' : '' }}Rp
                {{ number_format(abs($statistik['totalPerubahan']), 0, ',', '.') }}
            </div>
        </div>
        <div class="statistik-box">
            <div class="statistik-label">PERSENTASE</div>
            <div class="statistik-nilai {{ $statistik['persenPerubahan'] >= 0 ? 'positif' : 'negatif' }}">
                {{ $statistik['persenPerubahan'] >= 0 ? '+' : '' }}{{ number_format($statistik['persenPerubahan'], 2) }}%
            </div>
        </div>
    </div>

    <!-- TABEL PERUBAHAN ANGGARAN -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th rowspan="2" width="12%">KODE REKENING</th>
                    <th rowspan="2" width="15%">URAIAN</th>
                    <th colspan="8" width="58%">RINCIAN PERHITUNGAN</th>
                    <th rowspan="2" width="15%">BERTAMBAH / BERKURANG (Rp)</th>
                </tr>
                <tr class="sub-header">
                    <th colspan="4">SEBELUM</th>
                    <th colspan="4">SESUDAH</th>
                </tr>
                <tr class="sub-header">
                    <th></th>
                    <th></th>
                    <th>Vol</th>
                    <th>Sat</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah (Rp)</th>
                    <th>Vol</th>
                    <th>Sat</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah (Rp)</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rencana as $item)
                    @php
                        $sebelum = $item->total_anggaran;
                        $sesudah = $item->anggaran->total_anggaran ?? $sebelum;
                        $perubahan = $sesudah - $sebelum;

                        $volume = 12;
                        $satuan = 'Bulan';

                        $hargaSatuanSebelum = $volume > 0 ? $sebelum / $volume : 0;
                        $hargaSatuanSesudah = $volume > 0 ? $sesudah / $volume : 0;

                        $badgeClass = $perubahan > 0 ? 'badge-naik' : ($perubahan < 0 ? 'badge-turun' : 'badge-tetap');
                        $badgeText = $perubahan > 0 ? '+' : ($perubahan < 0 ? '-' : '=');
                        $progressClass = $perubahan >= 0 ? 'bg-naik' : 'bg-turun';

                        $persenItem = $sebelum > 0 ? round(($perubahan / $sebelum) * 100, 1) : 0;
                    @endphp
                    <tr>
                        <td class="text-left">
                            <span class="bold">{{ $item->kodeRekening->kode ?? '-' }}</span><br>
                            <span
                                style="font-size: 8pt;">{{ Str::limit($item->kodeRekening->nama_rekening ?? '', 18) }}</span>
                        </td>
                        <td class="text-left">
                            <span class="bold">{{ Str::limit($item->nama_rencana, 22) }}</span><br>
                            <span style="font-size: 8pt;">Tahun: {{ $item->tahun }}</span>
                        </td>
                        <td class="text-center">{{ $volume }}</td>
                        <td class="text-center">{{ $satuan }}</td>
                        <td class="text-right">Rp {{ number_format($hargaSatuanSebelum, 0, ',', '.') }}</td>
                        <td class="text-right bold">Rp {{ number_format($sebelum, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $volume }}</td>
                        <td class="text-center">{{ $satuan }}</td>
                        <td class="text-right">Rp {{ number_format($hargaSatuanSesudah, 0, ',', '.') }}</td>
                        <td class="text-right bold">Rp {{ number_format($sesudah, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="badge-change {{ $badgeClass }}">
                                {{ $badgeText }} Rp {{ number_format(abs($perubahan), 0, ',', '.') }}
                            </span>
                            <div class="progress-mini">
                                <div class="progress-bar {{ $progressClass }}"
                                    style="width: {{ min(100, abs($persenItem)) }}%;"></div>
                            </div>
                            <span style="font-size: 8pt;">{{ $persenItem }}%</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center" style="padding: 25px;">
                            Tidak ada data perubahan anggaran
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right bold">TOTAL</td>
                    <td></td>
                    <td></td>
                    <td class="text-right bold">Rp {{ number_format($statistik['totalSebelum'], 0, ',', '.') }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right bold">Rp {{ number_format($statistik['totalSesudah'], 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span
                            class="badge-change {{ $statistik['totalPerubahan'] >= 0 ? 'badge-naik' : 'badge-turun' }}">
                            {{ $statistik['totalPerubahan'] >= 0 ? '+' : '-' }} Rp
                            {{ number_format(abs($statistik['totalPerubahan']), 0, ',', '.') }}
                        </span>
                        <div class="progress-mini">
                            <div class="progress-bar {{ $statistik['totalPerubahan'] >= 0 ? 'bg-naik' : 'bg-turun' }}"
                                style="width: {{ min(100, abs($statistik['persenPerubahan'])) }}%;"></div>
                        </div>
                        <span style="font-size: 8pt; font-weight: 600;">
                            {{ number_format(abs($statistik['persenPerubahan']), 2) }}%
                        </span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- KETERANGAN - TIDAK TEKNIS -->
    <div class="keterangan">
        <p>INFORMASI PENTING:</p>
        <ul>
            <li>Kolom SEBELUM menunjukkan nilai rencana awal yang diajukan</li>
            <li>Kolom SESUDAH menunjukkan nilai anggaran yang telah disetujui</li>
            <li>Perhitungan menggunakan asumsi 12 bulan (satu tahun anggaran)</li>
            <li>Tanda (+) berarti anggaran bertambah, tanda (-) berarti anggaran berkurang</li>
        </ul>
    </div>

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
        <div>Dokumen ini dicetak secara elektronik</div>
        <div>Dicetak oleh: {{ auth()->user()->nama ?? 'System' }} | {{ now()->format('d/m/Y H:i:s') }}</div>
    </div>
</body>

</html>
