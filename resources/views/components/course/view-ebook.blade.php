<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Great Crystal School - E-Book/Material | {{$data->title}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/style.css') }}">
    <link rel="stylesheet" href="{{ asset('template') }}/dist/css/adminlte.min.css">
    <link rel="icon" href="{{ asset('great.png') }}" type="image/x-icon">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('template') }}/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('fontawesome') }}/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Caveat+Brush&family=Chewy&family=DynaPuff&family=Lora:ital,wght@0,400..700;1,400..700&family=Patrick+Hand&family=Vollkorn:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>

<style>
    body {
        height: 100vh;
    }
</style>


<body>
    {!! $data->file_path !!}
</body>

</html>
