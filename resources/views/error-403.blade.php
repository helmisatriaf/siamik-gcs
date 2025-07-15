<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 Not Found</title>
    <style>
        body {
            background-color: #f4f6f8;
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 10%;
            color: #333;
        }
        h1 {
            font-size: 72px;
            margin-bottom: 10px;
        }
        p {
            font-size: 18px;
            margin-bottom: 30px;
        }
        a {
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>404</h1>
    <p>Halaman yang kamu cari tidak ditemukan.</p>
    <a href="{{ url('/') }}">Kembali ke Beranda</a>
</body>
</html>
