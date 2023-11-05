<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url(https://fonts.googleapis.com/css?family=Nunito:300,400,500,600,700&display=swap);

        body {
            font-family: Nunito, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            color: #666;
            font-size: 16px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .header {
            background-color: #c7651f;
            text-align: center;
            padding: 10px 0;
        }

        .footer {
            background-color: #c7651f;
            text-align: center;
            padding: 10px 0;
        }

        td {
            font-size: 16px;
            vertical-align: top;
            padding: 8px 0;
        }

        td:first-child {
            color: #666;
            width: 30%;
        }

        strong {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2 style="color: #ffffff;">Selamat Telah Lolos Tahap 1 <br>Pramuka Garuda</h2>
        </div>

        <p>Anda telah berhasil lolos dalam pendaftaran Pramuka Garuda tahap 1. Berikut adalah detail pendaftaran Anda :
        </p>
        <table>
            <tr>
                <td>Nomor Tanda Anggota</td>
                <td>:</td>
                <td><strong>{{ $pendaftaran->nta }}</strong></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><strong>{{ $pendaftaran->user->name }}</strong></td>
            </tr>
            <tr>
                <td>Tanggal Lahir</td>
                <td>:</td>
                <td>
                    <strong>
                        {{ $formattedDate = \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->locale('id')->isoFormat('D MMMM Y') }}</strong>
                </td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><strong>{{ $pendaftaran->alamat }}</strong></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td><strong>{{ $pendaftaran->jenis_kelamin }}</strong></td>
            </tr>
            <tr>
                <td>Kwartir Ranting</td>
                <td>:</td>
                <td><strong>{{ $pendaftaran->kwaran }}</strong></td>
            </tr>
            <tr>
                <td>Pangkalan</td>
                <td>:</td>
                <td><strong>{{ $pendaftaran->pangkalan }}</strong></td>
            </tr>
            <tr>
                <td>Gugus Depan</td>
                <td>:</td>
                <td><strong>{{ $pendaftaran->gudep }}</strong></td>
            </tr>
            <tr>
                <td>Golongan</td>
                <td>:</td>
                <td><strong>{{ $pendaftaran->golongan->name }}</strong></td>
            </tr>
            <tr>
                <td>Status</td>
                <td>:</td>
                <td><strong>{{ $pendaftaran->tahap_1 }}</strong></td>
            </tr>
        </table>

        <div class="footer">
            <p style="color: #ffffff;">Terima kasih telah mendaftar Pramuka Garuda!</p>
        </div>
    </div>
</body>

</html>
