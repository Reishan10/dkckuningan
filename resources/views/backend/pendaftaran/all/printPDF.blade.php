<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Pendaftaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 5px;
        }

        th {
            text-align: center;
            /* Menengahkan teks di kolom "No" */
            text-transform: uppercase;
        }

        h2 {
            font-size: 18px;
            margin-top: 0;
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            font-size: 12px;
            text-align: center;
        }

        /* Menengahkan nomor di dalam kolom "No" */
        td:first-child {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>PENETAPAN PRAMUKA GARUDA TAHUN 2023</h2>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Pangkalan</th>
                <th>Golongan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pendaftaran as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->user->name }}</td>
                    <td>{{ $row->pangkalan }}</td>
                    <td>{{ $row->golongan_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
