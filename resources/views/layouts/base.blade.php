<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <!-- marcador para llenar el contenido de las vistas -->
    @yield('content')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>

</html>