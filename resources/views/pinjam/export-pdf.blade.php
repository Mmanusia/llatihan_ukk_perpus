<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        .filter-info {
            margin-bottom: 20px;
            font-size: 12px;
            background-color: #f5f5f5;
            padding: 10px;
            border-left: 4px solid #0d6efd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #0d6efd;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            border: 1px solid #0d6efd;
        }
        td {
            padding: 10px 12px;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-dikembalikan {
            background-color: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 3px;
            font-weight: bold;
        }
        .status-belum {
            background-color: #fff3cd;
            color: #856404;
            padding: 4px 8px;
            border-radius: 3px;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PEMINJAMAN BUKU</h1>
        <p>Perpustakaan Hash</p>
        <p>Tanggal Cetak: {{ $tanggal_cetak }}</p>
    </div>

    <div class="filter-info">
        <strong>Periode:</strong> 
        @if($tanggal_mulai !== 'Semua' && $tanggal_akhir !== 'Semua')
            {{ \Carbon\Carbon::parse($tanggal_mulai)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d F Y') }}
        @else
            Semua Data
        @endif
    </div>

    @if($pinjams->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Nama Peminjam</th>
                <th style="width: 25%;">Judul Buku</th>
                <th style="width: 15%;">Tanggal Pinjam</th>
                <th style="width: 15%;">Tanggal Kembali</th>
                <th style="width: 20%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pinjams as $pinjam)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $pinjam->user->nama_lengkap ?? $pinjam->user->username ?? '-' }}</td>
                <td>{{ $pinjam->buku->judul ?? '-' }}</td>
                <td>{{ $pinjam->tanggal_peminjaman ? \Carbon\Carbon::parse($pinjam->tanggal_peminjaman)->format('d M Y') : '-' }}</td>
                <td>{{ $pinjam->tanggal_pengembalian ? \Carbon\Carbon::parse($pinjam->tanggal_pengembalian)->format('d M Y') : '-' }}</td>
                <td>
                    @if($pinjam->status_peminjaman === 'Pinjam')
                        <span class="status-belum">Belum Dikembalikan</span>
                    @else
                        <span class="status-dikembalikan">Sudah Dikembalikan</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total Peminjaman: <strong>{{ $pinjams->count() }}</strong></p>
        <p style="margin-top: 30px;">Dicetak oleh Sistem Perpustakaan</p>
    </div>
    @else
    <div class="no-data">
        <p>Tidak ada data peminjaman dalam periode yang dipilih.</p>
    </div>
    @endif
</body>
</html>
