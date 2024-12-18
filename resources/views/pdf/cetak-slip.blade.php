<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Kerja Karyawan</title>
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
            <h2>Slip Gaji - {{ $karyawan->name ?? 'Nama Karyawan Tidak Tersedia' }}</h2>
            <p><strong>Periode: {{ $gajiInfo['periode'] }}</strong></p>
            <p class="text-center" style="margin-bottom: 20px">
                <strong>Jenis Genteng:</strong> {{ $gajiInfo['jenisGenteng'] }} |
                <strong>Gaji per Seribu:</strong> Rp {{ $gajiInfo['gajiPerSeribu'] }}
            </p>
            <p><strong>Tanggal Pengambilan Gaji: </strong>{{ $tanggalPengambilan }}</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Total Genteng Yang Dihasilkan</th>
                    <th>Total Gaji Yang Diambil</th>
                </tr>
            </thead>
            <tbody>
                <tr class="total-row">
                    <td>{{ $gajiInfo['totalGenteng'] }} Biji</td>
                    <td>Rp {{ $gajiInfo['totalGaji'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="divider"></div>
    <div class="footer">
        <p>Terima kasih atas kerjasama Anda!</p>
    </div>
</body>

</html>
