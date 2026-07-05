<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rencana Anggaran - Instalasi Gizi RSUD Kabanjahe</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #2c3e50;
            margin: 0;
            padding: 15mm 12mm;
            background: #ffffff;
        }

        /* Kop Surat */
        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #6d5dfc;
            padding-bottom: 10px;
        }

        .kop-institusi {
            font-size: 20pt;
            font-weight: bold;
            color: #6d5dfc;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        .kop-instalasi {
            font-size: 14pt;
            font-weight: 600;
            color: #22d3ee;
            margin-bottom: 2px;
        }

        .kop-alamat {
            font-size: 8pt;
            color: #64748b;
            margin-top: 3px;
        }

        .garis-kop {
            height: 2px;
            background: linear-gradient(to right, #6d5dfc, #22d3ee, #a9d6e5);
            margin-top: 5px;
        }

        /* Judul Laporan */
        .judul-laporan {
            text-align: center;
            margin: 15px 0 20px 0;
        }

        .judul-utama {
            font-size: 16pt;
            font-weight: bold;
            color: #6d5dfc;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .judul-sub {
            font-size: 11pt;
            font-weight: 500;
            color: #22d3ee;
            margin-bottom: 3px;
        }

        .tanggal-cetak {
            font-size: 8pt;
            color: #64748b;
            font-style: italic;
        }

        /* Info Filter */
        .info-filter {
            background: #f8fafc;
            border-left: 5px solid #6d5dfc;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .info-filter table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-filter td {
            padding: 3px 8px;
            font-size: 9pt;
        }

        .info-filter .label {
            font-weight: 600;
            color: #2c3e50;
            width: 100px;
        }

        .info-filter .value {
            color: #4a5568;
        }

        /* Statistik */
        .stats-container {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-box {
            flex: 1;
            padding: 12px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .stat-box.primary {
            border-top: 3px solid #6d5dfc;
        }

        .stat-box.warning {
            border-top: 3px solid #f59e0b;
        }

        .stat-label {
            font-size: 8pt;
            color: #64748b;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .stat-value {
            font-size: 14pt;
            font-weight: bold;
            color: #2c3e50;
        }

        /* Tabel */
        .table-container {
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }

        th {
            background: #6d5dfc;
            color: white;
            font-weight: 600;
            padding: 10px 5px;
            text-align: center;
            border: 1px solid #5b4ce0;
            font-size: 8.5pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        td {
            padding: 8px 5px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background: #f8fafc;
        }

        .text-right {
            text-align: right;
            padding-right: 8px;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
            padding-left: 8px;
        }

        .fw-bold {
            font-weight: 600;
        }

        .kode-rekening {
            font-weight: 600;
            color: #2c3e50;
        }

        .nama-rekening {
            font-size: 7.5pt;
            color: #64748b;
        }

        .amount {
            color: #6d5dfc;
            font-weight: 600;
        }

        /* Footer */
        .footer {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 8pt;
            color: #64748b;
        }

        .watermark {
            position: fixed;
            bottom: 60px;
            right: 60px;
            font-size: 55px;
            color: #6d5dfc;
            opacity: 0.03;
            transform: rotate(-15deg);
            z-index: -1;
            font-weight: bold;
            text-transform: uppercase;
        }

        @media print {
            body {
                padding: 10mm 8mm;
            }

            .watermark {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="watermark">RSUD KABANJAHE</div>

    <!-- Kop Surat -->
    <div class="kop-surat">
        <div class="kop-institusi">INSTALASI GIZI</div>
        <div class="kop-instalasi">RSUD KABANJAHE</div>
        <div class="kop-alamat">Jalan Kapten Selamat Ketaren No. 1, Kabanjahe 22151, Sumatera Utara</div>
        <div class="garis-kop"></div>
    </div>

    <!-- Judul Laporan -->
    <div class="judul-laporan">
        <div class="judul-utama">LAPORAN RENCANA ANGGARAN</div>
        <div class="judul-sub">Data Rencana Anggaran yang Telah Disetujui</div>
        <div class="tanggal-cetak">Dicetak: {{ now()->format('d F Y H:i:s') }}</div>
    </div>

    <!-- Info Filter -->
    <div class="info-filter">
        <table>
            <tr>
                <td class="label">Tahun</td>
                <td class="value">: {{ $filters['tahun'] ?? 'Semua Tahun' }}</td>
                <td class="label">Total Data</td>
                <td class="value">: {{ $rencana->count() }} Rencana</td>
            </tr>
            <tr>
                <td class="label">Kode Rekening</td>
                <td class="value">:
                    @if (!empty($filters['kode_rekening_id']) && isset($kodeRekeningList))
                        @php
                            $rekening = $kodeRekeningList->firstWhere('id', $filters['kode_rekening_id']);
                        @endphp
                        {{ $rekening->kode ?? '' }} - {{ $rekening->nama_rekening ?? '' }}
                    @else
                        Semua Rekening
                    @endif
                </td>
                <td class="label"></td>
                <td class="value"></td>
            </tr>
            @if (!empty($filters['search']))
                <tr>
                    <td class="label">Pencarian</td>
                    <td class="value" colspan="3">: "{{ $filters['search'] }}"</td>
                </tr>
            @endif
        </table>
    </div>

    <!-- Statistik -->
    <div class="stats-container">
        <div class="stat-box primary">
            <div class="stat-label">Total Rencana Disetujui</div>
            <div class="stat-value">{{ $rencana->count() }}</div>
        </div>
        <div class="stat-box warning">
            <div class="stat-label">Total Anggaran</div>
            <div class="stat-value">Rp {{ number_format($rencana->sum('total_anggaran'), 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="7%">Tahun</th>
                    <th width="15%">Kode Rekening</th>
                    <th width="22%">Nama Rencana</th>
                    <th width="15%">Total Anggaran</th>
                    <th width="12%">Diajukan Oleh</th>
                    <th width="12%">Disetujui Oleh</th>
                    <th width="12%">Tgl Persetujuan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rencana as $index => $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $item->tahun }}</td>
                        <td class="text-left">
                            <span class="kode-rekening">{{ $item->kodeRekening->kode ?? '-' }}</span><br>
                            <span
                                class="nama-rekening">{{ Str::limit($item->kodeRekening->nama_rekening ?? '-', 25) }}</span>
                        </td>
                        <td class="text-left">
                            <span class="fw-bold">{{ Str::limit($item->nama_rencana, 35) }}</span>
                        </td>
                        <td class="text-right">
                            <span class="amount">Rp {{ number_format($item->total_anggaran, 0, ',', '.') }}</span>
                        </td>
                        <td class="text-left">{{ $item->pengaju->nama ?? '-' }}</td>
                        <td class="text-left">{{ $item->penyetuju->nama ?? '-' }}</td>
                        <td class="text-center">
                            @if ($item->tanggal_persetujuan)
                                {{ $item->tanggal_persetujuan->format('d/m/Y H:i') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center" style="padding: 30px;">
                            Tidak ada data rencana anggaran yang disetujui
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div>Dokumen ini dicetak secara elektronik</div>
        <div>Dicetak oleh: {{ auth()->user()->nama ?? 'System' }} | {{ now()->format('d/m/Y H:i:s') }}</div>
    </div>
</body>

</html>
