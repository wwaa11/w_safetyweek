<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>PR9 Safety Week</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ url('images/logo.ico') }}">
    <link rel="stylesheet" href="{{ asset('font-awesome/css/all.min.css') }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/tableToExcel.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @vite('resources/css/app.css')
</head>
<style>
    @font-face {
        font-family: 'Prompt';
        src: url({{ asset('font/Prompt.ttf') }});
    }
</style>

<body style="font-family: 'Prompt', sans-serif;">
    <div class="w-full bg-gray-100 min-h-svh flex">
        @yield('content')
    </div>
</body>
<script></script>
@yield('scripts')

</html>
