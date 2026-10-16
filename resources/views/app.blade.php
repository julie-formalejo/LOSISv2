<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>LOSISv2</title>

    <!-- Favicon -->
    <link
        rel="icon"
        href="{{ asset('assets/images/lendell/favicon.png') }}"
        type="image/png"
    >

    <!-- Bootstrap -->
    <link
        rel="stylesheet"
        href="{{ asset('assets/css/assets/bootstrap.min.css') }}"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}"
    >
    <link
        rel="stylesheet"
        href="{{ asset('assets/plugins/fontawesome/css/solid.min.css') }}"
    >
    <link
        rel="stylesheet"
        href="{{ asset('assets/plugins/fontawesome/css/regular.min.css') }}"
    >
    <link
        rel="stylesheet"
        href="{{ asset('assets/plugins/fontawesome/css/brands.min.css') }}"
    >

    <!-- SweetAlert2 -->
    <link
        rel="stylesheet"
        href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}"
    >

    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>
