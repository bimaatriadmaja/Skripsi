<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Kerja Karyawan</title>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Jost', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff; /* Set background color for the whole body */
        }

        h1, h2, h3 {
            text-align: center;
        }

        h1 {
            font-size: 2.5em;
            margin-top: 20px;
            color: #003366;
            font-weight: 600;
        }

        h2, h3, h4 {
            font-size: 1.5em;
            color: #333;
            margin: 10px 0;
            font-weight: 500;
        }

        h3 {
            font-size: 1.2em;
            color: #555;
            margin-bottom: 10px;
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

        .table-container {
            margin-top: 30px;
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 1em;
            background-color: #f7f8fa; /* Set the table background to match body */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
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

        .bg-success { background-color: #28a745; }
        .bg-danger { background-color: #dc3545; }
        .bg-warning { background-color: #ffc107; }
        .bg-primary { background-color: #007bff; }

        .left-align {
            text-align: left;
            font-size: 1.2em;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding: 10px;
            background-color: #f7f8fa; /* Set footer background to match body */
            font-size: 0.9em;
            color: #777;
        }

        .footer p {
            margin: 0;
        }

        .divider {
            margin: 20px 0;
            border-top: 2px solid #ddd;
        }
    </style>
</head>
<body>

    <!-- Header Instansi -->
    <div class="header-container">
        <h1>PG. DWI PUTRA</h1>
        <p class="slogan">Industri Genteng Press Berkualitas</p>
        <p class="address">Palihan, Pakisan, Cawas, Klaten | Telp. 0856 4743 3086</p>
    </div>

    <!-- Divider -->
    <div class="divider"></div>

    <!-- Nama Karyawan dan Periode -->
    <h4 class="left-align">Hasil Kerja: {{ $karyawan->name }}</h4>
    <h4 class="left-align">Periode: {{ $startDateFormatted }} - {{ $endDateFormatted }}</h4>

    <!-- Tabel Hasil Kerja -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tanggal Kerja</th>
                    <th>Jumlah Genteng</th>
                    <th>Catatan</th>
                    <th>Status</th>
                    <th>Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hasilKerja as $hasil)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($hasil->tanggal_kerja)->translatedformat('j F Y') }}</td>
                        <td>{{ $hasil->jumlah_genteng }}</td>
                        <td>{{ $hasil->catatan }}</td>
                        <td>
                            @if ($hasil->status == 'approved')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($hasil->status == 'rejected')
                                <span class="badge bg-danger">Ditolak</span>
                            @elseif($hasil->status == 'pending')
                                <span class="badge bg-warning">Ditunda</span>
                            @endif
                        </td>
                        <td>
                            @if ($hasil->payment_status == 'paid')
                                <span class="badge bg-primary">Sudah Dibayar</span>
                            @else
                                <span class="badge bg-danger">Belum Dibayar</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Divider -->
    <div class="divider"></div>

    <!-- Footer -->
    <div class="footer">
        <p>Terima kasih atas kerjasama Anda!</p>
    </div>

</body>
</html>
