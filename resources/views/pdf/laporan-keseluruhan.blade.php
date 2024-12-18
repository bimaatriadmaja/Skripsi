<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keseluruhan Karyawan</title>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Jost', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        h1,
        h2,
        h3 {
            text-align: center;
        }

        h1 {
            font-size: 2.5em;
            margin-top: 20px;
            color: #003366;
            font-weight: 600;
        }

        h2,
        h3 {
            font-size: 1.5em;
            color: #333;
            margin: 10px 0;
            font-weight: 500;
        }

        .header-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .header-container h1 {
            color: #0056b3;
            font-weight: 700;
        }

        .slogan {
            font-size: 1.2em;
            font-weight: 400;
            color: #6c757d;
        }

        .address {
            font-size: 1em;
            color: #444;
            margin-top: 5px;
            line-height: 1.4;
        }

        .divider {
            margin: 20px 0;
            border-top: 2px solid #ddd;
        }

        .table-container {
            margin-top: 30px;
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 1em;
            background-color: #f7f8fa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            color: #333;
        }

        td {
            color: #555;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
            font-size: 0.9em;
        }

        .bg-success {
            background-color: #28a745;
        }

        .bg-danger {
            background-color: #dc3545;
        }

        .bg-warning {
            background-color: #ffc107;
        }

        .bg-primary {
            background-color: #007bff;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding: 10px;
            background-color: #f7f8fa;
            font-size: 0.9em;
            color: #777;
        }

        .footer p {
            margin: 0;
        }

        .left-align {
            text-align: left;
            font-size: 1.2em;
        }

        .total-row td {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header-container">
        <h1>PG. DWI PUTRA</h1>
        <p class="slogan">Industri Genteng Press Berkualitas</p>
        <p class="address">Palihan, Pakisan, Cawas, Klaten | Telp. 0856 4743 3086</p>
    </div>
    <div class="divider"></div>
    <div class="container">
        <div class="text-center">
            <h2 style="margin-top: 30px;">Laporan Keseluruhan Karyawan</h2>
            <p><strong>Periode
                    {{ \Carbon\Carbon::parse(request('start_date'))->locale('id')->isoFormat('D MMMM YYYY') }} -
                    {{ \Carbon\Carbon::parse(request('end_date'))->locale('id')->isoFormat('D MMMM YYYY') }}</strong>
            </p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Status Hasil Kerja</th>
                    <th>Diproses</th>
                    <th>Disetujui & Gaji Belum Diambil</th>
                    <th>Disetujui & Gaji Sudah Diambil</th>
                    <th>Ditolak</th>
                </tr>
            </thead>
            <tbody>
                <tr class="total-row">
                    <td>Total</td>
                    <td>{{ $total_pending_approval }}</td>
                    <td>{{ $total_belum_dibayar }}</td>
                    <td>{{ $total_sudah_dibayar }}</td>
                    <td>{{ $total_ditolak }}</td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <th>Total Genteng (Gaji Belum Diambil)</th>
                    <th>Total Genteng (Gaji Sudah Diambil)</th>
                    <th>Gaji Belum Diambil</th>
                    <th>Gaji Sudah Diambil</th>
                </tr>
            </thead>
            <tbody>
                <tr class="total-row">
                    <td>{{ $total_genteng_gajiblmdiambil }} Biji</td>
                    <td>{{ $total_genteng_gajidiambil }} Biji</td>
                    <td>Rp {{ number_format($total_gaji, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($total_gaji_diambil, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="divider"></div>
    <h2 class="text-center" style="margin-top: 50px;">Rincian Per Karyawan</h2>
    @foreach ($data as $karyawan)
        <div style="margin-bottom: 30px;">
            <div class="section-title"><strong> Hasil Kerja dari {{ $karyawan->karyawan_name }}</strong></div>
            <p><strong>Jenis Genteng:</strong> {{ $karyawan->nama_jenis ?? 'Tidak Ada Data' }} | <strong>Gaji per Seribu:</strong> Rp {{ number_format($karyawan->gaji_per_seribu ?? 0, 0, ',', '.') }}</p>
            <table>
                <thead>
                    <tr>
                        <th>Status Hasil Kerja</th>
                        <th>Diproses</th>
                        <th>Disetujui & Gaji Belum Diambil</th>
                        <th>Disetujui & Gaji Sudah Diambil</th>
                        <th>Ditolak</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total</td>
                        <td>{{ $karyawan->jumlah_pending_approval }}</td>
                        <td>{{ $karyawan->jumlah_belum_dibayar }}</td>
                        <td>{{ $karyawan->jumlah_sudah_dibayar }}</td>
                        <td>{{ $karyawan->jumlah_ditolak }}</td>
                    </tr>
                </tbody>
            </table>
            <table>
                <thead>
                    <tr>
                        <th>Total Genteng (Gaji Belum Diambil)</th>
                        <th>Total Genteng (Gaji Sudah Diambil)</th>
                        <th>Gaji Belum Diambil</th>
                        <th>Gaji Sudah Diambil</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $karyawan->total_genteng_gajiblmdiambil }} Biji</td>
                        <td>{{ $karyawan->total_genteng_gajidiambil }} Biji</td>
                        <td>Rp {{ number_format($karyawan->total_gaji, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($karyawan->total_gaji_diambil, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach
    <div class="divider"></div>
    <div class="footer">
        <p>Terima kasih atas kerjasama Anda!</p>
    </div>
</body>

</html>
