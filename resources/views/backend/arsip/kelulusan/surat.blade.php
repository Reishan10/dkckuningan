<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $sk->name }}</title>
    <meta name="author" content="Windows User" />
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            text-indent: 0;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 15mm 5mm;
        }

        p,
        h1,
        .s1,
        .s2 {
            color: black;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 11pt;
            margin: 0pt;
        }

        h1 {
            font-weight: bold;
        }

        li {
            display: block;
        }

        #l1,
        #l2,
        #l3 {
            padding-left: 0pt;
            counter-reset: c1 1;
        }

        #l1>li>*:first-child:before {
            counter-increment: c1;
            content: counter(c1, lower-latin)". ";
        }

        #l1>li:first-child>*:first-child:before {
            counter-increment: c1 0;
        }

        #l2>li>*:first-child:before {
            counter-increment: d1;
            content: counter(d1, decimal)". ";
        }

        #l2>li:first-child>*:first-child:before {
            counter-increment: d1 1;
        }

        #l3>li>*:first-child:before {
            counter-increment: e1;
            content: counter(e1, decimal)". ";
        }

        #l3>li:first-child>*:first-child:before {
            counter-increment: e1 1;
        }

        table,
        tbody {
            vertical-align: top;
            overflow: visible;
        }

        .table-lampiran {
            width: 90%;
            margin: auto;
            font-size: 11pt;
            border-collapse: collapse;
        }

        .table-lampiran th,
        .table-lampiran td {
            border: 1px solid rgb(37, 37, 37);
            padding: 8px;
        }

        .table-lampiran thead {
            position: sticky;
            top: 0;
            display: table-header-group;
        }
    </style>


</head>

<body>
    <p style="padding-top: 5pt;text-indent: 0pt;text-align: center;"><img width="36" height="68"
            src="data:image/png;base64,<?= base64_encode(file_get_contents(base_path('public/assets/img/logo_pramuka.png'))) ?>" />
    </p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="padding-top: 4pt;text-indent: 0pt;text-align: center;">KWARTIR CABANG GERAKAN PRAMUKA
        KUNINGAN</p>
    <p style="padding-top: 8pt;text-indent: 0pt;text-align: center;">SURAT KEPUTUSAN</p>
    <p style="padding-top: 1pt;text-indent: 0pt;line-height: 115%;text-align: center;">KWARTIR CABANG
        GERAKAN PRAMUKA KUNINGAN</p>
    <p style="padding-top: 1pt;text-indent: 0pt;line-height: 115%;text-align: center;">NOMOR : TAHUN {{ $sk->tahun }}
    </p>
    <p style="padding-top: 6pt;text-indent: 0pt;text-align: center;">TENTANG</p>
    <h1 style="padding-top: 7pt;text-indent: 0pt;text-align: center;">PENETAPAN PRAMUKA GARUDA TAHUN
        {{ $sk->tahun }}</h1>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: center;">Ketua Kwartir Cabang Gerakan Pramuka Kuningan,
    </p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <table style="border-collapse:collapse;margin-left:8.604pt; margin: auto; " cellspacing="0">
        <tr style="height:167pt">
            <td style="width:86pt">
                <p class="s1" style="padding-left: 2pt;text-indent: 0pt;line-height: 12pt;text-align: left;">
                    Menimbang
                </p>
            </td>
            <td style="width:16pt">
                <p class="s1" style="text-indent: 0pt;line-height: 12pt;text-align: right;">:</p>
            </td>
            <td style="width:360pt">
                <ol id="l1">
                    <li data-list-text="a.">
                        <p class="s1" style="padding-left: 21pt;text-indent: -14pt;text-align: justify;">Untuk
                            meningkatkan kualitas peserta didik dan menggiatkan setiap Pramuka untuk berusaha
                            meningkatkan kecakapan dan keterampilan, sikap dan tindakannya sehingga dapat
                            mempersiapkan
                            diri menjadi tenaga pembangunan Bangsa dan Negara;</p>
                    </li>
                    <li data-list-text="b.">
                        <p class="s1"
                            style="padding-top: 5pt;padding-left: 21pt;text-indent: -14pt;text-align: justify;">Untuk
                            mewujudkan usaha kegiatan pendidikan bagi para remaja untuk menerapkan prinsip dasar
                            kepramukaan dan metode kepramukaan;</p>
                    </li>
                    <li data-list-text="c.">
                        <p class="s1"
                            style="padding-top: 5pt;padding-left: 21pt;text-indent: -14pt;text-align: justify;">Untuk
                            menarik minat Pramuka, anak – anak dan pemuda lain agar mengikuti jejak Pramuka
                            Garuda maka perlu ditetapkan Surat Keputusan Ketua Kwartir Gerakan Pramuka Cabang
                            Kuningan;
                        </p>
                    </li>
                </ol>
            </td>
        </tr>
        <tr style="height:210pt">
            <td style="width:86pt">
                <p style="text-indent: 0pt;text-align: left;"><br /></p>
                <p class="s1" style="padding-left: 2pt;text-indent: 0pt;text-align: left;">Mengingat</p>
            </td>
            <td style="width:16pt">
                <p style="text-indent: 0pt;text-align: left;"><br /></p>
                <p class="s1" style="padding-right: 5pt;text-indent: 0pt;text-align: right;">:</p>
            </td>
            <td style="width:360pt">
                <p style="text-indent: 0pt;text-align: left;"><br /></p>
                <ol id="l2">
                    <li data-list-text="1.">
                        <p class="s1" style="padding-left: 21pt;text-indent: -14pt;text-align: justify;">Undang-Undang Republik Indonesia Nomor 12 Tahun 2010 tentang Gerakan Pramuka;</p>
                    </li>
                    <li data-list-text="2.">
                        <p class="s1"
                            style="padding-top: 5pt;padding-left: 21pt;text-indent: -14pt;text-align: justify;">Keputusan Presiden Republik Indonesia Nomor 238 Tahun 1961 tentang Gerakan Pramuka;</p>
                    </li>
                    <li data-list-text="3.">
                        <p class="s1"
                            style="padding-top: 5pt;padding-left: 21pt;text-indent: -14pt;text-align: justify;">Keputusan Musyawarah Nasional Gerakan Pramuka Tahun 2018 Nomor 7/MUNAS/2018 tentang
                            Anggaran
                            Dasar dan Anggaran Rumah Tangga Gerakan Pramuka;</p>
                    </li>
                    <li data-list-text="4.">
                        <p class="s1"
                            style="padding-top: 5pt;padding-left: 21pt;text-indent: -14pt;text-align: justify;">Keputusan Kwartir Nasional Gerakan Pramuka Nomor 220 tahun 2007 tentang Petunjuk
                            Penyelenggaraan Pokok – Pokok Organisasi Gerakan Pramuka;</p>
                        <p style="text-indent: 0pt;text-align: left;"><br /></p>
                    </li>
                    <li data-list-text="5.">
                        <p class="s1" style="padding-left: 21pt;text-indent: -14pt;text-align: justify;">Keputusan
                            Kwartir Nasional Gerakan Pramuka Nomor : 38 Tahun 2017 tentang Petunjuk
                            Penyelenggaraan Pramuka Garuda;</p>
                    </li>
                </ol>
            </td>
        </tr>
        <tr style="height:53pt">
            <td style="width:86pt">
                <p class="s1" style="padding-top: 9pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">
                    Memperhatikan</p>
            </td>
            <td style="width:16pt">
                <p class="s1" style="padding-top: 9pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">:
                </p>
            </td>
            <td style="width:360pt">
                <p class="s1"
                    style="padding-top: 10pt;padding-left: 5pt;text-indent: 0pt;line-height: 108%;text-align: left;">Saran dan Pertimbangan Tim Penilai Pramuka Garuda Kwartir Cabang Kuningan.</p>
            </td>
        </tr>
        <tr style="height:34pt">
            <td style="width:86pt">
                <p style="text-indent: 0pt;text-align: left;"><br /></p>
            </td>
            <td style="width:16pt">
                <p style="text-indent: 0pt;text-align: left;"><br /></p>
            </td>
            <td style="width:360pt">
                <p style="text-indent: 0pt;text-align: left;"><br /></p>
                <h1 class="s2"
                    style="padding-left: 70pt;text-indent: 0pt;text-align: left;line-height: 50px;font-weight: bold;">M
                    E
                    M U T U S K A N :
                </h1>
            </td>
        </tr>
        <tr style="height:23pt">
            <td style="width:86pt">
                <p class="s1" style="padding-top: 4pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">
                    Menetapkan
                </p>
            </td>
            <td style="width:16pt">
                <p class="s1" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">:
                </p>
            </td>
            <td style="width:360pt">
                <p style="text-indent: 0pt;text-align: left;"><br /></p>
            </td>
        </tr>
        <tr style="height:62pt">
            <td style="width:86pt">
                <p class="s1" style="padding-top: 4pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">
                    Pertama
                </p>
            </td>
            <td style="width:16pt">
                <p class="s1" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">
                    :
                </p>
            </td>
            <td style="width:360pt">
                <p class="s1"
                    style="padding-top: 4pt;padding-left: 5pt;padding-right: 2pt;text-indent: 0pt;line-height: 114%;text-align: justify;">Kepada Anggota Pramuka Penggalang, Penegak dan Pandega pada lingkungan Gerakan Pramuka Kwartir
                    Cabang Kuningan untuk namanya yang tercantum dalam lampiran surat keputusan ini diberikan Tanda
                    Penghargaan Gerakan Pramuka berupa Piagam dan Lencana Pramuka Garuda yang menandakan kesetiaan,
                    kepatuhan, kerajinan, ketekunan dan kesungguhan serta
                    ketertiban sebagai anggota Gerakan Pramuka.</p>
                </p>
            </td>
        </tr>
        <tr style="height:54pt;">
            <td style="width:64pt">
                <p style="text-indent: 0pt;text-align: left;"><br /></p>
                <p class="s1" style="padding-left: 2pt;text-indent: 0pt;text-align: left;">Kedua</p>
            </td>
            <td style="width:38pt">
                <p style="text-indent: 0pt;text-align: left;"><br /></p>
                <p class="s1" style="padding-right: 5pt;text-indent: 0pt;text-align: right;">:</p>
            </td>
            <td style="width:360pt">
                <p style="text-indent: 0pt;text-align: left;"><br /></p>
                <p class="s1" style="padding-left: 5pt;text-indent: 0pt;text-align: justify;">Surat Keputusan
                    ini
                    berlaku
                    sejak tanggal ditetapkan dan apabila terdapat kekeliruan akan diadakan perubahan serta perbaikan
                    sebagaimana mestinya.</p>
            </td>
        </tr>
    </table>
    <p style="text-indent: 0pt;text-align: right;"><br /></p>
    <p style="padding-top: 4pt;padding-left: 280pt;text-indent: 0pt;text-align: left;">Ditetapkan di :
        {{ $sk->lokasi }}</p>
    <p style="padding-top: 1pt;padding-left: 280pt;text-indent: 0pt;text-align: left;">Pada tanggal :
        {{ \Carbon\Carbon::parse($sk->tanggal_penetapan)->isoFormat('D MMMM YYYY') }}</p>
    <p style="padding-top: 2pt;padding-left: 280pt;text-indent: 0pt;line-height: 114%;text-align: left;">Kwartir
        Cabang
        Gerakan Pramuka Kuningan <br>Ketua,</p>
    <p style="text-indent: 0pt;padding-left: 280pt;text-align: left;">
        <img src="data:image/png;base64,<?= base64_encode(file_get_contents(base_path('public/assets/img/ttd.png'))) ?>"
            width="350" />
    </p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <h1 style="padding-left: 280pt;text-indent: 0pt;text-align: left;">RANA SUPARMAN, S.Sos.</h1>
    <p style="padding-top: 2pt;padding-left: 280pt;text-indent: 0pt;text-align: left;">NTA : 09.08.00.001</p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="padding-left: 65pt;text-indent: 0pt;text-align: left;">Tembusan Surat Keputusan ini disampaikan
        kepada :
    </p>
    <ol id="l3" style="padding-left: 55pt;">
        <li data-list-text="1.">
            <p style="padding-top: 1pt;padding-left: 29pt;text-indent: -18pt;text-align: left;">Yth. Ketua Kwartir
                Daerah Gerakan Pramuka Jawa Barat.</p>
        </li>
        <li data-list-text="2.">
            <p style="padding-top: 1pt;padding-left: 29pt;text-indent: -18pt;text-align: left;">Yth. Bupati selaku
                Ketua
                Majelis Pembimbing Cabang Gerakan Pramuka Kuningan.</p>
        </li>
        <li data-list-text="3.">
            <p style="padding-top: 1pt;padding-left: 29pt;text-indent: -18pt;line-height: 115%;text-align: left;">Yth.Para Camat se-Kuningan Selaku Ketua Majelis Pembimbing Ranting Gerakan Pramuka.</p>
        </li>
        <li data-list-text="4.">
            <p style="padding-left: 29pt;text-indent: -18pt;line-height: 12pt;text-align: left;">Yth. Para Ketua
                Kwartir
                Ranting Gerakan Pramuka se-Kuningan.</p>
        </li>
    </ol>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <table style="border-collapse:collapse;margin:auto;" cellspacing="0">
        <tr style="height:14pt">
            <td style="width:61pt">
                <p class="s1" style="padding-left: 2pt;text-indent: 0pt;line-height: 12pt;text-align: left;">
                    Lampiran
                </p>
            </td>
            <td style="width:21pt">
                <p class="s1" style="padding-right: 5pt;text-indent: 0pt;line-height: 12pt;text-align: right;">:
                </p>
            </td>
            <td style="width:311pt">
                <p class="s1" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;">
                    Surat
                    Keputusan Kwartir Cabang Gerakan Pramuka Kuningan</p>
            </td>
        </tr>
        <tr style="height:15pt">
            <td style="width:61pt">
                <p class="s1" style="padding-left: 2pt;text-indent: 0pt;text-align: left;">Nomor</p>
            </td>
            <td style="width:21pt">
                <p class="s1" style="padding-right: 5pt;text-indent: 0pt;text-align: right;">:</p>
            </td>
            <td style="width:311pt">
                <p class="s1" style="padding-left: 5pt;text-indent: 0pt;text-align: left;">
                    {{ $sk->nomor_lampiran }}</p>
            </td>
        </tr>
        <tr style="height:15pt">
            <td style="width:61pt">
                <p class="s1" style="padding-left: 2pt;text-indent: 0pt;text-align: left;">Tanggal</p>
            </td>
            <td style="width:21pt">
                <p class="s1" style="padding-right: 5pt;text-indent: 0pt;text-align: right;">:</p>
            </td>
            <td style="width:311pt">
                <p class="s1" style="padding-left: 5pt;text-indent: 0pt;text-align: left;">
                    {{ \Carbon\Carbon::parse($sk->tanggal_lampiran)->isoFormat('D MMMM YYYY') }}</p>
            </td>
        </tr>
        <tr style="height:14pt">
            <td style="width:61pt">
                <p class="s1" style="padding-left: 2pt;text-indent: 0pt;line-height: 12pt;text-align: left;">
                    Tentang</p>
            </td>
            <td style="width:21pt">
                <p class="s1" style="padding-right: 5pt;text-indent: 0pt;line-height: 12pt;text-align: right;">:
                </p>
            </td>
            <td style="width:311pt">
                <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;font-weight: bold;">
                    {{ $sk->tentang_lampiran }}</p>
            </td>
        </tr>
    </table>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <h1 style="padding-top: 4pt;line-height: 114%;text-align: center;">PENETAPAN
        PRAMUKA GARUDA</h1>
    <h1 style="padding-top: 4pt;line-height: 114%;text-align: center;">TAHUN {{ $sk->tahun }}</h1>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <table class="table-lampiran">
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA LENGKAP</th>
                <th>PANGKALAN</th>
                <th>GOLONGAN</th>
            </tr>
        </thead>
        @foreach ($user as $row)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->pendaftaran->pangkalan }}</td>
                <td>{{ $row->pendaftaran->golongan->name }}</td>
            </tr>
        @endforeach
    </table>
</body>

</html>
